<?php

session_start();
include_once("conn.php");
//include_once("class.php");
include_once("user.php");

//$_BASE_URL = 'http://localhost/edeep_services/';

if (!defined('USER_STATUS'))
    define('USER_STATUS', 0);

?>