<?php
// CONF
if (! file_exists('conf.php'))
{
    die("You need a conf.php, bro.");
}
require_once('conf.php');

$type = $_SERVER['QUERY_STRING'] == 'song' ? 'song' : 'sfx';

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
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'audio', file: filename, file_type: '<?php echo $type; ?>' },
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
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { action: 'control', file: filename, file_type: '<?php echo $type; ?>' },
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
<?php
if ($type == 'song')
{
    print("<a class='button control' href='skip'>SKIP</a>");
}
?>

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

        switch ($_POST['file_type'])
        {
            case 'sfx':
                $endpoint = '/play';
                break;
            case 'song':
                $endpoint = '/queue';
                break;
        }
    }
    elseif ($_POST['action'] == 'control')
    {
        $payload = json_encode(array(
            "control" => "skip"
        ));

        $endpoint = '/queue';
    }

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
    $process = curl_init($host . "/" . $type);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($process);
    curl_close($process);

    $files = json_decode($return, true);

    foreach ($files as $file)
    {
        echo "<a class='button audio' href=" . urlencode($file['file_path']) . ">" . $file['name'] . "</a>";
    }
}
?>
</body></html>