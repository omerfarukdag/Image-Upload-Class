# Image Upload Class

This class allows you to upload single and multiple images.


## Single Image Upload Usage
```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>
```
```php
require_once 'class.upload.php';
$upload = new ImageUpload($_FILES['file']);
    if ($upload->status) {
        echo $upload->uploaded_file;
    } else {
        echo $upload->error;
    }
```

## Multiple Image Upload Usage
```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <button type="submit">Upload</button>
</form>
```
```php
require_once 'class.upload.php';
$upload = new ImageUpload($_FILES['files'], true);
    if (count($upload->uploaded_files) > 0) {
        print_r($upload->uploaded_files);
    }
    if (count($upload->errors) > 0) {
        print_r($upload->errors);
    }
```
