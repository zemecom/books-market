<?php

declare(strict_types=1);

class LocalCoverImageStorage implements CoverImageStorageInterface
{
    public function __construct(private string $baseDirectory) {}

    public function store(CUploadedFile $file): string
    {
        if (!is_dir($this->baseDirectory)) {
            mkdir($this->baseDirectory, 0777, true);
        }

        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', pathinfo($file->name, PATHINFO_FILENAME));
        $fileName = sprintf('%s-%s.%s', $safeBaseName ?: 'cover', uniqid(), $file->extensionName);
        $absolutePath = rtrim($this->baseDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
        $file->saveAs($absolutePath);

        return 'uploads/' . $fileName;
    }
}
