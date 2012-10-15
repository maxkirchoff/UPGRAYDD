<?php
require_once('request.php');
require_once('account.php');

// Check for POST
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // Check that we have an action in our post vars
    if (isset($_POST['action']))
    {
        switch ($_POST['action'])
        {
            case "play":
                // play action gets played
                $request->play(urldecode($_POST['file']), $_POST['file_type']);
                break;
            case "queue":
                // queue action gets queued
                $request->queue(urldecode($_POST['file']), $_POST['file_type']);
                break;
            case "control":
                // control action only has skip right now, so skip
                $request->song_control(urldecode($_POST['value']));
                break;
            case "volume":
                // request that we change the volume
                $request->set_volume(urldecode($_POST['value']));
                break;
        }
    }

    die();
}