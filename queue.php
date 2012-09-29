<?php
require('request.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <style>
        <!--
        a.button {
            background:#000;
            color: #fff;
            padding: 10px;
        }
        -->
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            // Capture the skip requests
            $('a.control').click( function (event) {
                $.ajax({
                    url: '/',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'control'}
                });
                event.preventDefault();
            });
        });
        // Refresh Queue
        setTimeout("location.reload(true);", 60000);
    </script>
</head>
<body>
<a class='button control' href='skip'>SKIP CURRENTLY PLAYING SONG</a>
<?php

// Grab the queued songs
$queued_songs = get_song_queue();

echo "<h1>Songs in the Play Queue</h1><ol class='song-queue'>";

// loop with all the songs
foreach ($queued_songs as $queued_song)
{
    echo "<li>{$queued_song['artist']} - {$queued_song['name']}</li>";
}

echo "</ol>";

?>
</body>
</html>