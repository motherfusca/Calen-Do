<?php
    
    /**
     * config.php
     * Configures pages.
     */
    
    //display errors, warnings, and notices
    //ini_set("display_errors", true);
    //error_reporting(E_ALL);
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // requirements
    require("constants.php");
    require("functions.php");
    require './setup.php';
    
    // enable sessions
    session_start();
    
    if (!preg_match("{(?:login|logout|register|about)\.php$}", $_SERVER["PHP_SELF"]))
    {
        if (empty($_SESSION['access_token']) && empty($_SESSION['user_id']))
        {
            redirect("login.php");
        }
    }
    
 ?>
