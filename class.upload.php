<?php
class ImageUpload
{
    private $file_name = null;
    private $file_tmp_name = null;
    private $file_size = null;
    private $file_type = null;
    private $file_ext = null;

    private $upload_dir = 'uploads/';
    public $uploaded_file = null;
    public $uploaded_files = array();

    private $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    private $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    private $max_size = 1048576 * 2;

    public $status = false;
    public $error = null;
    private $show_multiple_upload_errors = true;

    public function __construct($file, $multiple = false)
    {
        if (isset($file)) {
            if ($multiple) {
                $this->multiple($file);
            } else {
                $this->file_name = $file['name'];
                $this->file_tmp_name = $file['tmp_name'];
                $this->file_size = $file['size'];
                $this->file_type = $file['type'];
                $this->file_ext = explode('.', $this->file_name);
                $this->file_ext = strtolower(end($this->file_ext));
                $this->single();
            }
        }
    }

    private function single()
    {
        if (in_array($this->file_type, $this->allowed_types)) {
            if (in_array($this->file_ext, $this->allowed_extensions)) {
                if ($this->file_size < $this->max_size) {
                    if (!file_exists($this->upload_dir)) {
                        mkdir($this->upload_dir);
                    }
                    $this->file_name = time() . '-' . rand(1000000, 9999999) . '.' . $this->file_ext;
                    $this->uploaded_file = $this->upload_dir . $this->file_name;
                    if (file_exists($this->uploaded_file)) {
                        $this->file_name = time() . '-' . rand(1000000, 9999999) . '.' . $this->file_ext;
                        $this->uploaded_file = $this->upload_dir . $this->file_name;
                    }
                    if (move_uploaded_file($this->file_tmp_name, $this->uploaded_file)) {
                        $this->status = true;
                    } else {
                        $this->error = 'An error occurred while uploading the file.';
                    }
                } else {
                    $this->error = 'This file is bigger than ' . floor($this->max_size / 1048576) . 'MB (' . floor($this->file_size / 1048576) . 'MB)';
                }
            } else {
                $this->error = 'This file extension is not allowed. (' . $this->file_ext . ')';
            }
        } else {
            $this->error = 'This file type is not allowed. (' . $this->file_type . ')';
        }
    }


    private function multiple($images)
    {
        $files = array();
        foreach ($images as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $files))
                    $files[$i] = array();
                $files[$i][$k] = $v;
            }
        }
        foreach ($files as $file) {
            $this->__construct($file);
            if (!is_null($this->uploaded_file)) {
                $this->uploaded_files[] = $this->uploaded_file;
            } else {
                if ($this->show_multiple_upload_errors) {
                    $this->uploaded_files[] = $this->error;
                }
            }
            $this->clean();
        }
    }

    private function clean()
    {
        $this->file_name = null;
        $this->file_tmp_name = null;
        $this->file_size = null;
        $this->file_type = null;
        $this->file_ext = null;
        $this->uploaded_file = null;
        $this->status = false;
        $this->error = null;
    }

    public function __destruct()
    {
        $this->clean();
        $this->uploaded_files = array();
    }
}
