<?php

require 'setup.php';
require './includes/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["name"]))
        {
            apologize("I'm sure you have a name!");
        }
        else if (empty($_POST["email"]))
        {
            apologize("You must throw in a valid email.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("No password? How are you supposed to get in, huh?");
        }
        else if (empty($_POST["checkpassword"]))
        {
            apologize("Your passwords don't match. :(");
        }
        
        else if ($_POST["checkpassword"] != $_POST["password"])
        {
            apologize("Your passwords don't match. :()'");
        }

        // query database for user
        $rows = query("SELECT * FROM local_users WHERE email = ?", $_POST["email"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            apologize("Oooops, someone already registered with that email!");
        }
        else
        {
        query("INSERT INTO local_users (email,name,hash) VALUES (?,?,?)",$_POST["email"],$_POST["name"],crypt($_POST["password"]));
        redirect('./login.php');
        }



    }
    // if form was submitted
    



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
    


		<h1 style="font-weight:bold;">Register</h1>

        <div id="main">

			<form method="post">

        <div class="form-group">
            <input autofocus class="form-control" name="name" placeholder="Your name" type="text"/>
        </div>
        <div class="form-group">
            <input autofocus class="form-control" name="email" placeholder="E-mail" type="text"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="checkpassword" placeholder="Password Again!" type="password"/>
        </div>
        <div>
            <button type="submit" class="logoutButton">Register</button>
        </div>

</form>

            	<div>
    <p class="note">Get <a class="note" href="login.php">Back</a>, Jojo</p>
</div>

        </div>


        <footer>
	   
        </footer>
        
    </body>
</html>

