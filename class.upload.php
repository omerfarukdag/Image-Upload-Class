<?php declare(strict_types=1);
/**
 * Image Upload Class
 * @author github/omerfarukdag
 */
class ImageUploader
{
    private ?string $file_name = null;
    private ?string $file_dir = null;
    private ?string $file_tmp_name = null;
    private ?int $file_size = null;
    private ?string $file_type = null;
    private ?string $file_ext = null;
    private ?string $upload_dir = null;
    private int $count = 0;
    private ?string $new_file_name = null;

    private ?string $uploaded_file = null;
    private array $uploaded_files = array();
    private array $errors = array();

    private const UPLOAD_DIR = 'uploads/';
    private const ALLOWED_FILE_TYPES = array('image/jpeg', 'image/png', 'image/gif');
    private const ALLOWED_FILE_EXTENSIONS = array('jpg', 'jpeg', 'png', 'gif');
    private const MAX_FILE_SIZE = 1048576 * 2;

    public function __construct(array $file)
    {
        if (!is_array($file['name'])) {
            $this->file_name = $file['name'];
            $this->file_dir = pathinfo($file['full_path'], PATHINFO_DIRNAME);
            $this->file_tmp_name = $file['tmp_name'];
            $this->file_size = $file['size'];
            $this->file_type = $file['type'];
            $this->file_ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
            $this->upload_dir = self::UPLOAD_DIR . (($this->file_dir === '.') ? '' : $this->file_dir . '/');
            $this->upload();
        } else {
            $this->count = count($file['name']);
            for ($i = 0; $i < $this->count; $i++) {
                $this->file_name = $file['name'][$i];
                $this->file_dir = pathinfo($file['full_path'][$i], PATHINFO_DIRNAME);
                $this->file_tmp_name = $file['tmp_name'][$i];
                $this->file_size = $file['size'][$i];
                $this->file_type = $file['type'][$i];
                $this->file_ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
                $this->upload_dir = self::UPLOAD_DIR . (($this->file_dir === '.') ? '' : $this->file_dir . '/');
                $this->upload();
            }
        }

    }

    private function upload(): void
    {
        if (file_exists($this->file_tmp_name)) {
            if (in_array($this->file_type, self::ALLOWED_FILE_TYPES)) {
                if (in_array($this->file_ext, self::ALLOWED_FILE_EXTENSIONS)) {
                    if ($this->file_size <= self::MAX_FILE_SIZE) {
                        if (!file_exists($this->upload_dir)) {
                            mkdir($this->upload_dir, recursive: true);
                        }
                        $this->uploaded_file = $this->upload_dir . $this->createFileName();
                        if (move_uploaded_file($this->file_tmp_name, $this->uploaded_file)) {
                            if ($this->count > 0) {
                                $this->uploaded_files[] = $this->uploaded_file;
                                $this->uploaded_file = null;
                            }
                        } else {
                            $this->errors[] = 'This file could not be uploaded.';
                        }
                    } else {
                        $this->errors[] = 'This file is bigger than ' . $this->convertToMB(self::MAX_FILE_SIZE) . 'MB (' . $this->convertToMB($this->file_size) . 'MB)';
                    }
                } else {
                    $this->errors[] = 'This file extension is not allowed. (' . $this->file_ext . ')';
                }
            } else {
                $this->errors[] = 'This file type is not allowed. (' . $this->file_type . ')';
            }
        } else {
            $this->errors[] = 'This file does not exist.';
        }
    }

    private function createFileName(): string
    {
        $this->new_file_name = time() . '-' . uniqid(sha1(md5(rand() . time()))) . '.' . $this->file_ext;
        if (file_exists($this->upload_dir . $this->new_file_name)) {
            $this->createFileName();
        }
        return $this->new_file_name;
    }

    private function convertToMB(int $bytes): float
    {
        return round($bytes / 1048576, 2);
    }

    public function hasUploaded(): bool
    {
        return !is_null($this->uploaded_file) || !empty($this->uploaded_files);
    }

    public function getUploaded(): string|array|null
    {
        return $this->uploaded_file ?? $this->uploaded_files;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function __destruct()
    {
        unset($this->file_name,
            $this->file_dir,
            $this->file_tmp_name,
            $this->file_size,
            $this->file_type,
            $this->file_ext,
            $this->upload_dir,
            $this->count,
            $this->new_file_name,
            $this->uploaded_file,
            $this->uploaded_files,
            $this->errors);
    }
}