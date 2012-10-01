<?php
require('request.php');
$request = new Request_Thingy();

if (! empty($_COOKIE['username']) || ! empty($_COOKIE['password']))
{
    header('Location: /');
}

if (isset($_GET['username']) && isset($_GET['password']))
{
    try
    {

        $cred_array = array(
            "username"  =>  $_GET['username'],
            "password"  =>  $_GET['password']
        );

        $request->set_credentials($cred_array);

        $request->check_credentials();

        $username = $_GET['username'];
        $password = $_GET['password'];

        setcookie("username", $username);
        setcookie("password", $password);

        header('Location: /');
    }
    catch (Exception $e)
    {
        $invalid_login = true;
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <style>
        <!--
        #login {
            background :red;
            padding: 20px;
        }
        -->
        -->
    </style>
</head>
<body>
<div id="login">
    <h2>You must log in.</h2>
    <form action="/login.php">
        <?php
            if (isset($invalid_login))
            {
                echo "Invalid Login provided. Try again.<br /><br />";
            }
        ?>
        Username: <input type="text" name="username"></input><br /><br />
        Password: <input type="password" name="password"></input><br /><br />
        <input type="submit" value="Login" />
    </form>
</div>
</body>
</html>