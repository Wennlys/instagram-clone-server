<?php
declare(strict_types=1);

namespace App\Domain\Post;

use JsonSerializable;

class Post implements JsonSerializable
{
    private string $imageUrl;
    private ?string $description;
    private int $userId;

    public function __construct(?string $imageUrl = null, ?string $description = null, ?int $userId = null)
    {
        isset($imageUrl) ? $this->setImageUrl($imageUrl) : $this->imageUrl = $imageUrl;
        isset($description) ? $this->setDescription($description) : $this->description = $description;
        isset($userId) ? $this->setUserId($userId) : $this->userId = $userId;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter([
            'imageUrl' => $this->imageUrl,
            'description' => $this->description,
            'userId' => $this->userId,
        ]);
    }
}
