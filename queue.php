<?php
require_once('request.php');
require_once('account.php');
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
            $('a.song-control').click( function (event) {
                var control = $(this).attr('href');
                $.ajax({
                    url: 'main_controller.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'control', value: control }
                }).done(function() {
                            location.reload(true);
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
                }).done(function() {
                      location.reload(true);
                });
                event.preventDefault();
            });
        });
        // Refresh Queue
        setTimeout("location.reload(true);", 60000);
    </script>
</head>
<body>
<?php

$controls = $request->get_controls();

?>
<h4>Volume Controls | Current Volume: <?php if(isset($controls['volume']['current'])) { ?><span style="text-decoration: blink; color: red;"><?php echo $controls['volume']['current']; ?></span><?php } ?></h4>
<?php
if (isset($controls['volume']['set']) && is_array($controls['volume']['set']))
{
    foreach ($controls['volume']['set'] as $vol_option)
    {
        echo "<a class='button volume' href='{$vol_option}'>{$vol_option}</a>";
    }
}
?>
<h4>Song Controls</h4>
<?php
if (isset($controls['song']) && is_array($controls['song']))
{
    foreach ($controls['song'] as $song_control)
    {
        echo "<a class='button song-control' href='{$song_control}'>{$song_control}</a>";
    }
}

// Grab the queued songs
$queue = $request->get_song_queue();

echo "<h1>Songs in the Play Queue</h1>";
echo "<ol class='song-queue'>";

// loop with all the songs
if (! array_key_exists("__error", $queue))
{
    foreach ($queue as $queue_item)
    {
        $song_name = isset($queue_item['song']['name']) ? $queue_item['song']['name'] : "";
        $artist_name = isset($queue_item['song']['album']['artist']['name']) ? $queue_item['song']['album']['artist']['name'] : "Unknown Artist";
        echo "<li>{$artist_name} - {$song_name}</li>";
    }
}
echo "</ol>";

?>
</body>
</html>