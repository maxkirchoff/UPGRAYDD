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
    <h3>Volume Controls | Current Volume: <?php if(isset($controls['volume']['current'])) { ?><span style="text-decoration: blink; color: red;"><?php echo $controls['volume']['current']; ?></span><?php } ?></h3>
    <blink></blink>
    <?php
    if (isset($controls['volume']['set']) && is_array($controls['volume']['set']))
    {
        foreach ($controls['volume']['set'] as $vol_option)
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
    $artists = $request->get_artists_with_everything();

    // loop with all the artists
    foreach ($artists as $artist)
    {
        // echo the artist info
        echo "<hr style='clear:both;margin-top:40px; height:27px; border: 0px; background-image: url(img/starz.gif);' />";
        echo "<h2><a name='{$artist['name']}'>{$artist['name']}</a></h2><div class='artist'>";

        if (isset($artist['album']))
        {
            // loop through the songs under that artist
            foreach ($artist['album'] as $album)
            {
                echo "<hr style='clear:both;margin-top:10px;' />";
                echo "<div class='album' style='background: lightgray; margin-bottom: 10px;'>";


                /**
                 * This is bogging down load right now
                if (isset($album['album_cover']['file_path']))
                {
                    echo "<img src='http://10.44.111.111/uploads/{$album['album_cover']['file_path']}' align='left' />";
                }
                 */

                echo "<h4>{$album['name']}</h4>";

                foreach ($album['song'] as $song)
                {
                    echo "<a class='button song' href=" . urlencode($song['file_path']) . ">" . $song['name'] . "</a>";
                }

                echo "</div>";
            }
        }

        echo "</div>";
    }
    ?>
</div>
</body></html>