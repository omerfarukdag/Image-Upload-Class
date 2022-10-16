<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Image Upload Class</title>
</head>

<body>
    <?php
    if (isset($_POST)) {
        require_once 'class.upload.php';
        switch (@$_POST['type']) {
            case 'single':
                $upload = new Upload\Image($_FILES['image']);
                if ($upload->status) {
                    echo $upload->uploaded_file;
                } else {
                    echo $upload->error;
                }
                break;
            case 'multiple':
                $upload = new Upload\Image($_FILES['images'], true);
                if (isset($upload->uploaded_files) && !empty($upload->uploaded_files)) {
                    print_r($upload->uploaded_files);
                }
                if (isset($upload->errors) && !empty($upload->errors)) {
                    print_r($upload->errors);
                }
                break;
        }
    }
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