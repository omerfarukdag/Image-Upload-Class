<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Image Upload Class</title>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<pre>';
        require_once 'class.upload.php';
        $upload = match ($_POST['type']) {
            'single' => new ImageUploader($_FILES['image']),
            'multiple', 'webkitdirectory' => new ImageUploader($_FILES['images']),
        };
        if ($upload->hasUploaded()) {
            print_r($upload->getUploaded());
        }
        if ($upload->hasErrors()) {
            print_r($upload->getErrors());
        }
        echo '</pre>';
    }
    ?>
    <br>
    <form method="POST" enctype="multipart/form-data">
        <label for="file">File: (Single)</label>
        <input id="file" type="file" name="image">
        <input type="hidden" name="type" value="single">
        <button type="submit">Upload</button>
    </form>
    <br>
    <form method="POST" enctype="multipart/form-data">
        <label for="files">Files: (Multiple)</label>
        <input id="files" type="file" name="images[]" multiple>
        <input type="hidden" name="type" value="multiple">
        <button type="submit">Upload</button>
    </form>
    <br>
    <form method="POST" enctype="multipart/form-data">
        <label for="files">Files: (Directory)</label>
        <input id="files" type="file" name="images[]" multiple webkitdirectory>
        <input type="hidden" name="type" value="webkitdirectory">
        <button type="submit">Upload</button>
    </form>
</body>

</html>