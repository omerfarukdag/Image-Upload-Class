# Image Upload Class

This class allows you to upload single and multiple images.


## Single Image Upload Usage
```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image">
    <button type="submit">Upload</button>
</form>
```
```php
require_once 'class.upload.php';
$upload = new Upload\Image($_FILES['image']);
    if ($upload->status) {
        echo $upload->uploaded_file;
    } else {
        echo $upload->error;
    }
```

## Multiple Image Upload Usage
```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="images[]" multiple>
    <button type="submit">Upload</button>
</form>
```
```php
require_once 'class.upload.php';
$upload = new Upload\Image($_FILES['images'], true);
    if (isset($upload->uploaded_files) && !empty($upload->uploaded_files)) {
        print_r($upload->uploaded_files);
    }
    if (isset($upload->errors) && !empty($upload->errors)) {
        print_r($upload->errors);
    }
```
