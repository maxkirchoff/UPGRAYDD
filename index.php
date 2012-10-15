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
?>
<!DOCTYPE HTML>
<html>
<head>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $('a.sfx').click( function (event) {
                var filename = $(this).attr('href');
                $.ajax({
                    url: 'main_controller.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'play', file: filename, file_type: 'sfx' },
                    error: function() { }
                });
                event.preventDefault();
            });
            $('a.song').click( function (event) {
                var filename = $(this).attr('href');
                $.ajax({
                    url: 'main_controller.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'queue', file: filename, file_type: 'song' },
                    error: function() { }
                });
                event.preventDefault();
            });
            // Capture the skip requests
            $('a.song-control').click( function (event) {
                var control = $(this).attr('href');
                $.ajax({
                    url: 'main_controller.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'control', value: control }
                });
                event.preventDefault();
            });
            // Capture the skip requests
            $('a.volume').click( function (event) {
                var volume = $(this).attr('href');
                $.ajax({
                    url: 'main_controller.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'volume', value: volume }
                });
                event.preventDefault();
            });
        });
    </script>
</head>
<body>
NAV: <a href="#sfx">SFX</a> | <a href="#songs">SONGS</a> | <a href="#" onClick="window.open('queue.php','mywindow','width=500,height=1000')">SONG QUEUE</a> | <a href="account.php?logout">LOGOUT</a>
<br />
UPLOAD: <a href="#"  onClick="window.open('upload.php?type=sfx','sfx_upload','width=400,height=200')">SFX</a> | <a href="#"  onClick="window.open('upload.php?type=song','song_upload','width=400,height=200')">SONG</a>
<a name="sfx"></a>
<h2>SFX</h2>
<div id="sfx">
    <?php

    // get all the sfxes
    $sfxes = $request->get_sfxes();

    // Loop through to build the list
    foreach ($sfxes as $sfx)
    {
        echo "<a class='button sfx' href=" . urlencode($sfx['file_path']) . ">" . $sfx['name'] . "</a>";
    }

    ?>
</div>
<hr style="clear:both;" />
<a name="songs"></a>
<h2>SONGS</h2>
<div id="songs">
    <a class='button' href="#" onClick="window.open('/queue.php','mywindow','width=500,height=1000')">SONG QUEUE</a>
    <?php

    $controls = $request->get_controls();

    ?>
    <hr style="clear:both;" />
    <h3>Volume Controls</h3>
    <?php
    if (isset($controls['volume']) && is_array($controls['volume']))
    {
        foreach ($controls['volume'] as $vol_option)
        {
            echo "<a class='button volume' href='{$vol_option}'>{$vol_option}</a>";
        }
    }
    ?>
    <hr style="clear:both;" />
    <h3>Song Controls</h3>
    <?php
    if (isset($controls['song']) && is_array($controls['song']))
    {
        foreach ($controls['song'] as $song_control)
        {
            echo "<a class='button song-control' href='{$song_control}'>{$song_control}</a>";
        }
    }

    // get all the songs sorted by artist
    $artists_with_songs = $request->get_artists_with_songs();

    // loop with all the artists
    foreach ($artists_with_songs as $artist_with_songs)
    {
        // echo the artist info
        echo "<hr style='clear:both;margin-top:10px;' /><h3><a name='{$artist_with_songs['name']}'>{$artist_with_songs['name']}</a></h3>";

        // loop through the songs under that artist
        foreach ($artist_with_songs['song'] as $song)
        {
            echo "<a class='button song' href=" . urlencode($song['file_path']) . ">" . $song['name'] . "</a>";
        }
        echo "<br /><br />";
    }
    ?>
</div>
</body></html>
