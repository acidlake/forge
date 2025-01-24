<?php
namespace Base\Interfaces;

interface FileUploadServiceInterface
{
    /**
     * Handle a file upload.
     *
     * @param array $file The file array from the request.
     * @param string $path The target path for storage.
     * @param string|null $filename Optional custom filename.
     * @return string The full path to the stored file.
     */
    public function upload(
        array $file,
        string $path,
        ?string $filename = null
    ): string;

    /**
     * Validate a file before uploading.
     *
     * @param array $file The file array from the request.
     * @param array $rules Validation rules (e.g., size, extensions).
     * @return bool Whether the file passes validation.
     */
    public function validate(array $file, array $rules): bool;

    /**
     * Delete a file.
     *
     * @param string $path The path to the file to delete.
     * @return bool Whether the file was successfully deleted.
     */
    public function delete(string $path): bool;
}
