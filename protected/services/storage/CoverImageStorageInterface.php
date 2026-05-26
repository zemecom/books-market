<?php

declare(strict_types=1);

interface CoverImageStorageInterface
{
    public function store(CUploadedFile $file): string;
}
