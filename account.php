<?php
require_once('request.php');

if ((! isset($_COOKIE['username'])) || (! isset($_COOKIE['password'])) || $_SERVER['QUERY_STRING'] == "logout")
{
    setcookie("username");
    setcookie("password");

    header('Location: login.php');
}

if (empty($_COOKIE['username']) && (empty($_COOKIE['password'])))
{
    header('Location: login.php');
}

$request = new Request_Thingy();

$cred_array = array(
    "username"  =>  $_COOKIE['username'],
    "password"  =>  $_COOKIE['password']
);

$request->set_credentials($cred_array);