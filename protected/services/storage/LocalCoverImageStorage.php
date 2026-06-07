<?php

declare(strict_types=1);

class LocalCoverImageStorage implements CoverImageStorageInterface
{
    public function __construct(private string $baseDirectory) {}

    public function store(CUploadedFile $file): string
    {
        if (!is_dir($this->baseDirectory)) {
            if (!mkdir($this->baseDirectory, 0755, true) && !is_dir($this->baseDirectory)) {
                throw new RuntimeException('Unable to create upload directory.');
            }
        }

        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', pathinfo($file->name, PATHINFO_FILENAME));
        $fileName = sprintf('%s-%s.%s', $safeBaseName ?: 'cover', bin2hex(random_bytes(16)), $file->extensionName);
        $absolutePath = rtrim($this->baseDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
        if (!$file->saveAs($absolutePath)) {
            throw new RuntimeException('Unable to save uploaded cover image.');
        }

        return 'uploads/' . $fileName;
    }

    public function delete(string $path): void
    {
        $fileName = basename($path);
        $absolutePath = rtrim($this->baseDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }
}
