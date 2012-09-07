<?php
// CONF
if (! file_exists('conf.php'))
{
    die("You need a conf.php, brocoder.");
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
            $('a').click( function (event) {
                var filename = $(this).attr('href');
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { file: filename, file_type: 'sfx' },
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
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $file = $_POST['file'];
    $file_type = 'sfx';

    $payload = json_encode(array(
        "file" => $file,
        "file_type" => $file_type,
    ));

    $process = curl_init($host . "/play");
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
    $process = curl_init($host . "/sfx");
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_USERPWD, "max@shopigniter.com:reedrichards32");
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($process);
    curl_close($process);

    $sfxes = json_decode($return, true);

    foreach ($sfxes as $sfx)
    {
        echo "<a class='button' href=" . $sfx['file_path'] . ">" . $sfx['name'] . "</a>";
    }
}
?>
</body></html>