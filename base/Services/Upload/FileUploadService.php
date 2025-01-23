<?php
namespace Base\Services\Upload;

use Base\Interfaces\FileUploadServiceInterface;
use Base\Interfaces\StorageManagerInterface;

class FileUploadService implements FileUploadServiceInterface
{
    protected StorageManagerInterface $storage;

    public function __construct(StorageManagerInterface $storage)
    {
        $this->storage = $storage;
    }

    public function upload(
        array $file,
        string $path,
        ?string $filename = null
    ): string {
        if (!$this->isValidFile($file)) {
            throw new \Exception("Invalid file upload.");
        }

        $filename = $filename ?? $this->generateFilename($file["name"]);
        $targetPath = rtrim($path, "/") . "/" . $filename;

        if (
            !$this->storage->set(
                $targetPath,
                file_get_contents($file["tmp_name"])
            )
        ) {
            throw new \Exception("Failed to upload file.");
        }

        return $targetPath;
    }

    public function validate(array $file, array $rules): bool
    {
        if (!isset($file["tmp_name"]) || !is_uploaded_file($file["tmp_name"])) {
            return false;
        }

        // Validate file size
        if (isset($rules["size"]) && $file["size"] > $rules["size"]) {
            return false;
        }

        // Validate file extension
        if (isset($rules["extensions"])) {
            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $rules["extensions"])) {
                return false;
            }
        }

        return true;
    }

    public function delete(string $path): bool
    {
        return $this->storage->delete($path);
    }

    private function isValidFile(array $file): bool
    {
        return isset($file["tmp_name"], $file["name"], $file["size"]) &&
            is_uploaded_file($file["tmp_name"]);
    }

    private function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid("", true) . "." . $extension;
    }
}
