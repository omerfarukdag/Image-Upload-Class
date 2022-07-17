<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Class</title>
</head>
<body>
<?php
if (isset($_POST)) {
    require_once 'class.upload.php';

    switch (@$_POST['type']) {
        case 'single':
            $upload = new ImageUpload($_FILES['file']);
            if ($upload->status) {
                echo $upload->uploaded_file;
            } else {
                echo $upload->error;
            }
            break;
        case 'multiple':
            $upload = new ImageUpload($_FILES['files'], true);
            echo '<pre>';
            print_r($upload->uploaded_files);
            echo '</pre>';
            break;
    }
}
?>
<br>
<form method="POST" enctype="multipart/form-data">
    <label for="file">File:</label>
    <input id="file" type="file" name="file">
    <input type="hidden" name="type" value="single">
    <button type="submit">Upload</button>
</form>
<br>
<form method="POST" enctype="multipart/form-data">
    <label for="files">Files:</label>
    <input id="files" type="file" name="files[]" multiple>
    <input type="hidden" name="type" value="multiple">
    <button type="submit">Upload</button>
</form>
</body>
</html>