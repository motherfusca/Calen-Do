<?php

    require_once './includes/config.php';
    $today = new DateTime();
    $ano = date('Y');
    $todayf = $today->format('Y-m-d');
    $userDate = new DateTime();
    $userDatef = $userDate->format('Y-m-d');
    $userTime = new DateTime('00:00');;
    $userTimef = $userTime->format('H:i');


    $person = unserialize(urldecode($_SESSION['person']));
            
    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        	if (empty($_POST["input"]))
        {
            echo "<script type='text/javascript'>alert('Please fill in the form');</script>";

    	    }
        else
    	    {
            	$tasks_array = explode(",", $_POST["input"]);
              
            $userDateTimeArrayTryOne = explode("-", $tasks_array[0]);
            $userDateTimeArrayTryTwo = explode("/", $tasks_array[0]);
            $userDateTimeArrayTryThree = explode(":", $tasks_array[0]);

            if (count($userDateTimeArrayTryOne) == 2)
            {
                
                $tasks_array[0] = $userDateTimeArrayTryOne[0].'-'.$userDateTimeArrayTryOne[1].'-'.$ano;
            
            }
            if (count($userDateTimeArrayTryTwo) == 2)
            {

                $tasks_array[0] = $userDateTimeArrayTryTwo[0].'/'.$userDateTimeArrayTryTwo[1].'/'.$ano;
            
            }
            if (count($userDateTimeArrayTryOne) == 3)
            {

                if (strlen($userDateTimeArrayTryOne[2]) == 4)
                {
                    $tasks_array[0] = $userDateTimeArrayTryOne[0].'-'.$userDateTimeArrayTryOne[1].'-'.$userDateTimeArrayTryOne[2]; 
                }
                else if (strlen($userDateTimeArrayTryOne[2]) == 2)
                {
                    $fullYear = '20'.$userDateTimeArrayTryOne[2];
                    $tasks_array[0] = $userDateTimeArrayTryOne[0].'-'.$userDateTimeArrayTryOne[1].'-'.$fullYear;
                }
                
            
            }
            if (count($userDateTimeArrayTryTwo) == 3)
            {

                if (strlen($userDateTimeArrayTryTwo[2]) == 4)
                {
                    $tasks_array[0] = $userDateTimeArrayTryTwo[0].'-'.$userDateTimeArrayTryTwo[1].'-'.$userDateTimeArrayTryTwo[2]; 
                }
                else if (strlen($userDateTimeArrayTryTwo[2]) == 2)
                {
                    $fullYear = '20'.$userDateTimeArrayTryTwo[2];
                    $tasks_array[0] = $userDateTimeArrayTryTwo[0].'-'.$userDateTimeArrayTryTwo[1].'-'.$fullYear;
                }
                
            
            }

            

            
            $userDateTimeArrayTryFour = ('0');
            $userDateTimeArrayTryFive = ('0');
            $userDateTimeArrayTrySix = ('0');

            if (count($tasks_array) == 2)
            {

                if ((count($userDateTimeArrayTryOne) >= 2) || (count($userDateTimeArrayTryTwo) >= 2) )
                {
                    
                    $dt = str_replace('/', '-', $tasks_array[0]);
                    try 
                    {
                        $userDate = DateTime::createFromFormat('d-m-Y', $dt);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userDatef = $userDate->format('Y-m-d');
                    
                }
                else if (count($userDateTimeArrayTryThree) == 2)
                {
                    
                    
                    try 
                    {
                        $userTime = new DateTime($tasks_array[0]);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userTimef = $userTime->format('H:i');
                
                }

            }

            if (count($tasks_array) == 3)
            {
                $userDateTimeArrayTryFour = explode("-", $tasks_array[1]);
                $userDateTimeArrayTryFive = explode("/", $tasks_array[1]);
                $userDateTimeArrayTrySix = explode(":", $tasks_array[1]);
                
            if (count($userDateTimeArrayTryFour) == 2)
            {

                $tasks_array[1] = $userDateTimeArrayTryFour[0].'-'.$userDateTimeArrayTryFour[1].'-'.$ano;
            
            }
            if (count($userDateTimeArrayTryFive) == 2)
            {

                $tasks_array[1] = $userDateTimeArrayTryFive[0].'-'.$userDateTimeArrayTryFive[1].'-'.$ano;
            
            }
            if (count($userDateTimeArrayTryFour) == 3)
            {

                if (strlen($userDateTimeArrayTryFour[2]) == 4)
                {
                    $tasks_array[1] = $userDateTimeArrayTryFour[0].'-'.$userDateTimeArrayTryFour[1].'-'.$userDateTimeArrayTryFour[2]; 
                }
                else if (strlen($userDateTimeArrayTryFour[2]) == 2)
                {
                    $fullYear = '20'.$userDateTimeArrayTryFour[2];
                    $tasks_array[1] = $userDateTimeArrayTryFour[0].'-'.$userDateTimeArrayTryFour[1].'-'.$fullYear;
                }
                
            
            }
            if (count($userDateTimeArrayTryFive) == 3)
            {

                if (strlen($userDateTimeArrayTryFive[2]) == 4)
                {
                    $tasks_array[1] = $userDateTimeArrayTryFive[0].'-'.$userDateTimeArrayTryFive[1].'-'.$userDateTimeArrayTryFive[2]; 
                }
                else if (strlen($userDateTimeArrayTryFive[2]) == 2)
                {
                    $fullYear = '20'.$userDateTimeArrayTryFive[2];
                    $tasks_array[1] = $userDateTimeArrayTryFive[0].'/'.$userDateTimeArrayTryFive[1].'/'.$fullYear;
                }
                
            
            }


                if ((count($userDateTimeArrayTryOne) > 1) || (count($userDateTimeArrayTryTwo) > 1) && (count($userDateTimeArrayTrySix) == 2))
                {
                    
                    $dt = str_replace('/', '-', $tasks_array[0]);
                    try 
                    {
                        $userDate = DateTime::createFromFormat('d-m-Y', $dt);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userDatef = $userDate->format('Y-m-d');
                    try 
                    {
                        $userTime = new DateTime($tasks_array[1]);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userTimef = $userTime->format('H:i');

                }
                else if ((count($userDateTimeArrayTryFour) > 1) || (count($userDateTimeArrayTryFive) > 1) && (count($userDateTimeArrayTryThree) == 2))
                {
                    
                    $dt = str_replace('/', '-', $tasks_array[1]);
                    try 
                    {
                        $userDate = new DateTime($dt);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userDatef = $userDate->format('Y-m-d');
                    try 
                    {
                        $userTime = new DateTime($tasks_array[0]);
                    } catch (Exception $e)
                    {
                        apologize('Please enter DD/MM/YYYY or DD-MM-YYYY');
                        exit(1);
                    }
                    $userTimef = $userTime->format('H:i');

                }

            }

            
            
            else if (count($tasks_array) > 3)
            {
                apologize ('Please enter the maximum of 3 values');
            
            }
            	 

            if (count($tasks_array) == 1 && ((count($userDateTimeArrayTryOne) > 1) || (count($userDateTimeArrayTryTwo) > 1) || (count($userDateTimeArrayTryThree) > 1)))
            {
                apologize ('Please enter a valid task');
            }
            else if (count($tasks_array) == 2 && ((count($userDateTimeArrayTryOne) > 1) || (count($userDateTimeArrayTryTwo) > 1) || (count($userDateTimeArrayTryThree) > 1)) &&
                ((count($userDateTimeArrayTryFour) > 1) || (count($userDateTimeArrayTryFive) > 1) || (count($userDateTimeArrayTrySix) > 1)))
            {
                apologize ('Please enter a valid task');
            }
            else if (count($tasks_array) == 1)
            {
                $rows = query("SELECT * FROM glogin_users WHERE email ='".$person->email."'");
                $row = $rows[0];
                $etag = mt_rand();
                query("INSERT INTO tasks (rg, date, time, task, type, status, etag, eventId,deleted,SYNC) VALUES (?,?,?,?,?,?,?,?,?,?)", $row["email"], $userDatef, $userTimef, $tasks_array[0], 1,'UNDONE', crypt($etag), crypt($etag),'NO','NO');
                redirect ("./");
            }
            else if (count($tasks_array) == 2)
            {
                $rows = query("SELECT * FROM glogin_users WHERE email ='".$person->email."'");
                $row = $rows[0];
                $etag = mt_rand();
                query("INSERT INTO tasks (rg, date, time, task, type, status, etag, eventId,deleted,SYNC) VALUES (?,?,?,?,?,?,?,?,?,?)", $row["email"], $userDatef, $userTimef, $tasks_array[1], 1,'UNDONE',crypt($etag), crypt($etag),'NO','NO');
                redirect ("./");
            }
            else if (count($tasks_array) == 3 && ((count($userDateTimeArrayTryOne) > 1) || (count($userDateTimeArrayTryTwo) > 1)) && (count($userDateTimeArrayTrySix == 2)))
            {
                $rows = query("SELECT * FROM glogin_users WHERE email ='".$person->email."'");
                $row = $rows[0];
                $etag = mt_rand();
                query("INSERT INTO tasks (rg, date, time, task, type,status, etag, eventId,deleted,SYNC) VALUES (?,?,?,?,?,?,?,?,?,?)", $row["email"], $userDatef, $userTimef, $tasks_array[2], 2,'UNDONE',crypt($etag), crypt($etag),'NO','NO');
                redirect ("./");
            }
            else if (count($tasks_array) == 3 && (count($userDateTimeArrayTryThree) == 2) && ((count($userDateTimeArrayTryFour) > 1) || (count($userDateTimeArrayTryFive > 1))))
            {
                $rows = query("SELECT * FROM glogin_users WHERE email ='".$person->email."'");
                $row = $rows[0];
                $etag = mt_rand();
                query("INSERT INTO tasks (rg, date, time, task, type,status, etag, eventId,deleted,SYNC) VALUES (?,?,?,?,?,?,?,?,?,?)", $row["email"], $userDatef, $userTimef, $tasks_array[2], 2,'UNDONE',crypt($etag), crypt($etag),'NO','NO');
                redirect ("./");
            }
            
            	
            

            	
        }
    }
    

?>
