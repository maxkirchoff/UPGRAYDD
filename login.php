<?php
require('request.php');
$request = new Request_Thingy();

if (! empty($_COOKIE['username']) && ! empty($_COOKIE['password']))
{
    header('Location: index.php');
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

        header('Location: index.php');
    }
    catch (Exception $e)
    {
        $invalid_login = true;
    }
}
elseif (isset($_GET['email']))
{
    $email_sent = $request->create_account($_GET['email']);
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
    <form action="login.php">
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
<div id="signup">
    <h2>Sign up for an account</h2>
    <form action="login.php">
        <?php
        if (isset($email_sent))
        {
            if ($email_sent)
            {
                echo "You will recieve your password via email.<br /><br />";
            }
            else
            {
                echo "Your account could not be created.<br /><br />";
            }
        }
        ?>
        Email: <input type="text" name="email"></input><br /><br />
        *must be a shopigniter.com email address.
        <input type="submit" value="Create Account" />
    </form>
</div>
</body>
</html>