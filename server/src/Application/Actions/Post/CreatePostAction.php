<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Post\Post;
use Psr\Http\Message\ResponseInterface as Response;

class CreatePostAction extends PostAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        [
            'image' => $image,
            'description' => $description,
            'userId' => $userId
        ] = $this->request->getUploadedFiles();


        $user = $this->userRepository->findUserOfId($userId);

        $imageUrl = $this->moveUploadedFile(getcwd() . '/public/tmp/', $image);

        $post = new Post($imageUrl, $description, $userId);
        $this->postRepository->store($post);

        $this->logger->info("New post was created.");
        return $this->respondWithData(true);
    }

    private function moveUploadedFile($directory, $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
