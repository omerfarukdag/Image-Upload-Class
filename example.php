<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Image Upload Class</title>
</head>

<body>
    <?php
    echo '<pre>';
    if (isset($_POST)) {
        require_once 'class.upload.php';
        switch (@$_POST['type']) {
            case 'single':
                $upload = new ImageUploader($_FILES['image']);
                if ($upload->hasUploadedFile()) {
                    echo $upload->getUploadedFile();
                }
                if ($upload->hasErrors()) {
                    print_r($upload->getErrors());
                }
                break;
            case 'multiple':
                $upload = new ImageUploader($_FILES['images']);
                if ($upload->hasUploadedFiles()) {
                    print_r($upload->getUploadedFiles());
                }
                if ($upload->hasErrors()) {
                    print_r($upload->getErrors());
                }
                break;
        }
    }
    echo '</pre>';
    ?>
    <br>
    <form method="POST" enctype="multipart/form-data">
        <label for="file">File:</label>
        <input id="file" type="file" name="image">
        <input type="hidden" name="type" value="single">
        <button type="submit">Upload</button>
    </form>
    <br>
    <form method="POST" enctype="multipart/form-data">
        <label for="files">Files:</label>
        <input id="files" type="file" name="images[]" multiple>
        <input type="hidden" name="type" value="multiple">
        <button type="submit">Upload</button>
    </form>
</body>

</html>