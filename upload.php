<?php
if (isset($_GET['type']))
{
    $type = $_GET['type'];
}
else
{
    $type = 'sfx';
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <link href="css/fileuploader.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>Upload <?php echo strtoupper($type); ?></h2>
<div id="file-uploader">
    <noscript>
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>
</div>
<div class="qq-upload-file-drop-area">Drop files here too</div>
<script src="js/fileuploader.min.js" type="text/javascript"></script>
<script>
    function createUploader(){
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader'),
            action: 'handle_upload.php?type=<?php echo $type; ?>',
            debug: false,
            extraDropzones: [qq.getByClass(document, 'qq-upload-file-drop-area')[0]]
        });
    }

    // in your app create uploader as soon as the DOM is ready
    // don't wait for the window to load
    window.onload = createUploader;
</script>
</body></html>