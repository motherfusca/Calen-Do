<?php

require 'setup.php';
require './includes/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["email"]))
        {
            apologize("An email, that's all we ask!");
        }
        else if (empty($_POST["password"]))
        {
            apologize("No password? How do you expect to get in, huh?");
        }

        // query database for user
        $rows = query("SELECT * FROM local_users WHERE email = ?", $_POST["email"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["password"], $row["hash"]) == $row["hash"])
            {
                // remember that user's now logged in by storing user's ID in session
                $person = ORM::for_table('glogin_users')->where('email', $row['email'])->find_one();
	
	if(!$person){
		// No such person was found. Register!
		
		$person = ORM::for_table('glogin_users')->create();
		
		// Set the properties that are to be inserted in the db
		$person->email = $row['email'];
		$person->name = $row['name'];
		$person->hash = $row['hash'];
		if(isset($info['picture'])){
			// If the user has set a public google account photo
			$person->photo = $info['picture'];
		}
		else{
			// otherwise use the default
			$person->photo = 'assets/img/default_avatar.jpg';
		}
		
		// insert the record to the database
		$person->save();
	}
		// Save the user id to the session
	$_SESSION['user_id'] = $person->id();
	$_SESSION['person'] = urlencode(serialize($person));
	redirect('./');
		}
	}

        // else apologize
        apologize("Sorreeyy, wrong email and/or password.");
    }
    // if form was submitted
    


// Create a new Google API client
$client = new apiClient();
//$client->setApplicationName("Tutorialzine");

// Configure it
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setDeveloperKey($api_key);
$client->setRedirectUri($redirect_url);
$client->setApprovalPrompt(false);
$oauth2 = new apiOauth2Service($client);
$calendar = new apiCalendarService($client);
$tasksService = new apiTasksService($client);
$tasks = $tasksService->tasks;


// The code parameter signifies that this is
// a redirect from google, bearing a temporary code
if (isset($_GET['code'])) {

	// This method will obtain the actuall access token from Google,
	// so we can request user info
	$client->authenticate();
		// Get the user data
	
	
	$_SESSION['access_token'] = $client->getAccessToken();
	

	
    
	$info = $oauth2->userinfo->get();
	// Find this person in the database
	$person = ORM::for_table('glogin_users')->where('email', $info['email'])->find_one();
	
	if(!$person){
		// No such person was found. Register!
		
		$person = ORM::for_table('glogin_users')->create();
		
		// Set the properties that are to be inserted in the db
		$person->email = $info['email'];
		$person->name = $info['name'];
		
		if(isset($info['picture'])){
			// If the user has set a public google account photo
			$person->photo = $info['picture'];
		}
		else{
			// otherwise use the default
			$person->photo = 'assets/img/default_avatar.jpg';
		}
		
		// insert the record to the database
		$person->save();
	}
	
	// Save the user id to the session
	$_SESSION['user_id'] = $person->id();
	$_SESSION['person'] = urlencode(serialize($person));
	
	// Redirect to the base demo URL
	header("Location: $redirect_url");
	exit;
}

// Handle logout
if (isset($_GET['logout'])) {
	unset($_SESSION['user_id']);
}

$person = null;
if(isset($_SESSION['user_id'])){
	// Fetch the person from the database
	$person = ORM::for_table('glogin_users')->find_one($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Calen-do</title>
        
        <!-- The stylesheets -->
        <link rel="stylesheet" href="assets/css/styles.css" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700" />
        
        <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    
    <body>
    <header>

    <h2><a href="login.php" class="logo" style="color:greenyellow;">Calen-do</a></h2><p>Beta</p>
    <a href="about.php" style="font:20px 'Open Sans Condensed', sans-serif;float:right;margin-top:-21px;margin-right: 30px;color:greenyellow">About</a>
    </header>
    


		<h1 style="font-weight:bold;">Login</h1>

        <div id="main">
			<?php if($person):?>
			<a href="?logout" class="logoutButton">Logout</a>
			<?php
			if (isset($_SESSION['access_token']))
			{
            $client->setAccessToken($_SESSION['access_token']);
            $newOnes = query("SELECT * FROM tasks WHERE etag NOT LIKE '".'"%"'."' AND type='CAL' AND rg=?",$person->email);
            if (isset($newOnes))
            {
            foreach ($newOnes as $new)
            {
                $newEvent = new Event();
                $newEvent->setSummary($new['task']);
                $combined_start_date_and_time = $new['date'].'T'.$new['time'].':00-03:00';
                $endTime = date_create($new['time']);
                date_add($endTime, date_interval_create_from_date_string('1 hour'));
                $endTimef = date_format($endTime, 'H:i');
                $combined_end_date_and_time = $new['date'].'T'.$endTimef.':00-03:00';
                $start = new EventDateTime();
                $start->setDateTime($combined_start_date_and_time);
                $start->setTimeZone('America/Sao_Paulo');
                $newEvent->setStart($start);
                $end = new EventDateTime();
                $end->setDateTime($combined_end_date_and_time);
                $end->setTimeZone('America/Sao_Paulo');
                $newEvent->setEnd($end);
                $createdEvent = $calendar->events->insert($person->email, $newEvent);

                //$delete = query("DELETE FROM tasks WHERE id=".$new['id']."");

            
            }
            }
            query("DELETE FROM tasks WHERE deleted='YES' AND type='TASK' AND rg=?",$person->email);
            $deleted = query("SELECT * FROM tasks WHERE deleted='YES' AND type='CAL' AND rg=?",$person->email);
            
            if (count($deleted)>0)
            {
            foreach ($deleted as $del)
            {
            	$calendar->events->delete($person->email, $del['eventId']);
            $delete = query("DELETE FROM tasks WHERE deleted='YES'AND rg=?",$person->email);
            
            }
            
            }

            
            
            query("DELETE FROM tasks WHERE type='CAL' AND rg=?",$person->email);
            $optParams = array('timeMin' => '2014-05-01T00:00:00.000Z');
			$events = $calendar->events->listEvents($person->email,$optParams);
            $rows = query("SELECT * FROM glogin_users WHERE email ='".$person->email."'");
            $user = $rows[0];
            
            $today = new DateTime();
            $todayf = $today->format('Y-m-d');

                foreach($events['items'] as $item)
                {
                    if (isset($item['start']['dateTime']) && (isset($item['summary'])))
                    {
                        $date = new DateTime($item['start']['dateTime']);
                        $fdate = $date->format('Y-m-d');
                        $ftime = $date->format('H:i');
                        

                        if(isset($ftime))
                        {
                            query("INSERT INTO tasks (rg, date, time, task, type, etag, eventId, SYNC) VALUES (?,?,?,?,?,?,?,?)", $user["email"], $fdate, $ftime, $item['summary'], 2, $item['etag'],$item['id'],'YES');
                        }
		            }
		        }

		     //$sync =query("SELECT * FROM tasks WHERE SYNC='YES'");

		  

			}
			redirect("calendar.php");
			?>
			
				
			<?php else:?>
			<form method="post">

        <div class="form-group">
            <input autofocus class="form-control" name="email" placeholder="E-mail" type="text"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div>
            <button type="submit" class="logoutButton">Log In</button>
        </div>

</form>
<div>

            	<a href="<?php echo $client->createAuthUrl()?>" class="googleLoginButton">Sign in with Google</a>
            	</div>
            	<div>
    <p class="note"><a class="note" href="register.php">Register</a> for an account</p>
</div>
            <?php endif;?>

        </div>


        <footer>
	   
        </footer>
        
    </body>
</html>

