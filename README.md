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
$upload = new ImageUploader($_FILES['image']);
if ($upload->hasUploadedFile()) {
    echo $upload->getUploadedFile();
}
if ($upload->hasErrors()) {
    print_r($upload->getErrors());
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
$upload = new ImageUploader($_FILES['images']);
if ($upload->hasUploadedFiles()) {
    print_r($upload->getUploadedFiles());
}
if ($upload->hasErrors()) {
    print_r($upload->getErrors());
}
```
