<?php

namespace Upload;
class Image
{
    private ?string $file_name = null;
    private ?string $file_tmp_name = null;
    private ?int $file_size = null;
    private ?string $file_type = null;
    private ?string $file_ext = null;

    private const UPLOAD_DIR = 'uploads/';
    public ?string $uploaded_file = null;
    public array $uploaded_files = array();

    private const ALLOWED_FILE_TYPES = array('image/jpeg', 'image/png', 'image/gif');
    private const ALLOWED_FILE_EXTENSIONS = array('jpg', 'jpeg', 'png', 'gif');
    private const MAX_FILE_SIZE = 1048576 * 2;

    public bool $status = false;
    public ?string $error = null;
    public array $errors = array();

    public function __construct(array $file, bool $is_multiple = false)
    {
        if (!empty($file) && in_array($is_multiple, array(true, false))) {
            if ($is_multiple === true) {
                $this->multiple($file);
            } else {
                $this->file_name = $file['name'];
                $this->file_tmp_name = $file['tmp_name'];
                $this->file_size = $file['size'];
                $this->file_type = $file['type'];
                $this->file_ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
                $this->single();
            }
        }
    }

    private function single(): void
    {
        if (file_exists($this->file_tmp_name)) {
            if (in_array($this->file_type, self::ALLOWED_FILE_TYPES)) {
                if (in_array($this->file_ext, self::ALLOWED_FILE_EXTENSIONS)) {
                    if ($this->file_size < self::MAX_FILE_SIZE) {
                        if (!file_exists(self::UPLOAD_DIR)) {
                            mkdir(self::UPLOAD_DIR);
                        }
                        $this->file_name = time() . '-' . uniqid(sha1(md5(rand() . time())), true) . '.' . $this->file_ext;
                        $this->uploaded_file = self::UPLOAD_DIR . $this->file_name;
                        if (move_uploaded_file($this->file_tmp_name, $this->uploaded_file)) {
                            $this->status = true;
                        } else {
                            $this->error = 'An error occurred while uploading the file.';
                        }
                    } else {
                        $this->error = 'This file is bigger than ' . floor(self::MAX_FILE_SIZE / 1048576) . 'MB (' . floor($this->file_size / 1048576) . 'MB)';
                    }
                } else {
                    $this->error = 'This file extension is not allowed. (' . $this->file_ext . ')';
                }
            } else {
                $this->error = 'This file type is not allowed. (' . $this->file_type . ')';
            }
        } else {
            $this->error = 'There is no file to upload.';
        }
    }

    private function multiple(array $form_files): void
    {
        $files = array();
        foreach ($form_files as $key => $values) {
            foreach ($values as $index => $data) {
                $files[$index][$key] = $data;
            }
        }
        foreach ($files as $file) {
            self::__construct($file);
            if (!is_null($this->uploaded_file) && file_exists($this->uploaded_file)) {
                $this->uploaded_files[] = $this->uploaded_file;
            } else {
                $this->errors[] = $this->error;
            }
            $this->clean();
        }
    }

    private function clean(): void
    {
        $this->file_name = null;
        $this->file_tmp_name = null;
        $this->file_size = null;
        $this->file_type = null;
        $this->file_ext = null;
        $this->uploaded_file = null;
        $this->error = null;
        $this->status = false;
    }

    public function __destruct()
    {
        $this->clean();
        $this->uploaded_files = array();
        $this->errors = array();
    }
}
