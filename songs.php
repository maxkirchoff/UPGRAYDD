<?php
// CONF
if (! file_exists('conf.php'))
{
    die("You need a conf.php, bro.");
}
require_once('conf.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <style>
        <!--
        a.button {
            background:#000;
            color: #fff;
            float: left;
            padding: 10px;
            margin: 10px;
        }

        -->
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $('a.audio').click( function (event) {
                var filename = $(this).attr('href');
                $.ajax({
                    url: 'songs.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'audio', file: filename, file_type: 'song' },
                    error: function() { },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Basic ' + window.btoa('<?php echo $username; ?>:<?php echo $password; ?>'));
                    }
                });
                event.preventDefault();
            });
            $('a.control').click( function (event) {
                var filename = $(this).attr('href');
                $.ajax({
                    url: 'songs.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'control'},
                    error: function() { },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Basic ' + window.btoa('<?php echo $username; ?>:<?php echo $password; ?>'));
                    }
                });
                event.preventDefault();
            });


        });


    </script>
</head>
<body>
<a class='button control' href='skip'>SKIP</a>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($_POST['action'] == 'audio')
    {
        $file = urldecode($_POST['file']);
        $file_type = $_POST['file_type'];

        $payload = json_encode(array(
            "file" => $file,
            "file_type" => $file_type,
        ));
    }
    elseif ($_POST['action'] == 'control')
    {
        $payload = json_encode(array(
            "control" => "skip"
        ));
    }

    $endpoint = '/queue';

    $process = curl_init($host . $endpoint);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($process);
    curl_close($process);

}
else
{
    $process = curl_init($host . "/artist?_relations=song");
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($process);
    curl_close($process);

    $artists_with_songs = json_decode($return, true);

    // loop with all the songs
    foreach ($artists_with_songs as $artist_with_songs)
    {
        echo "<hr style='clear:both;margin-top:10px;' /><h1><a name='{$artist_with_songs['name']}'>{$artist_with_songs['name']}</a></h1>";
        foreach ($artist_with_songs['song'] as $song)
        {
            echo "<a class='button audio' href=" . urlencode($song['file_path']) . ">" . $song['name'] . "</a>";
        }
        echo "<br /><br />";
    }
}
?>
</body></html>