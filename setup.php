<?php

// Includes

require_once 'includes/google-api-php-client/apiClient.php';
require_once 'includes/google-api-php-client/contrib/apiOauth2Service.php';
require_once 'includes/google-api-php-client/contrib/apiCalendarService.php';
require_once 'includes/google-api-php-client/contrib/apiTasksService.php';
require_once 'includes/idiorm.php';
require_once 'includes/relativeTime.php';

// Session. Pass your own name if you wish.

//session_name('tzine_demo');
//session_start();

// Database configuration with the IDIORM library

$host = 'localhost';
$user = 'root';
$pass = '23581321';
$database = 'calen-do';

ORM::configure("mysql:host=$host;dbname=$database");
ORM::configure('username', $user);
ORM::configure('password', $pass);

// Changing the connection to unicode
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Google API. Obtain these settings from https://code.google.com/apis/console/

$redirect_url = 'http://localhost/~drausiofonsecatronolone/login.php'; // The url of your web site
$client_id = '513397191832-7d5lcbclsk5pnru6ank20kd6ah9hf147.apps.googleusercontent.com';
$client_secret = 'UAUWCQHhBCU6xeGoNPhiuf4C';
$api_key = 'AIzaSyD9NaWkaoFE2GSpbHkaTnsHg5V6hZ0bmOk';



    ?>


