<?php

namespace App\Domain\Models;

class Image
{
    private string $directoryName;
    private string $imageStream;

    public function __construct(string $directoryName, string $imageStream)
    {
        isset($directoryName) && $this->setDirectoryName($directoryName);
        isset($imageStream) && $this->setImageStream($imageStream);
    }

    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    public function setDirectoryName(string $directoryName): void
    {
        $this->directoryName = $directoryName;
    }

    public function getImageStream(): string
    {
        return $this->imageStream;
    }

    public function setImageStream(string $imageStream): void
    {
        $this->imageStream = $imageStream;
    }
}
