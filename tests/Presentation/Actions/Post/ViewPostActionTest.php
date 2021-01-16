<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Post;

use App\Domain\Usecases\LoadPostById;
use App\Presentation\Actions\Post\ViewPostAction;
use App\Presentation\Errors\Post\PostNotFoundException;
use App\Presentation\Protocols\HttpRequest;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\LoadPostByIdSpy;
use Tests\Presentation\Actions\ActionTestCase as TestCase;

class ViewPostActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function returns500_when_load_post_by_id_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadPostById = $this->prophesize(LoadPostById::class);
        $id = 1;
        $loadPostById->load($id)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($loadPostById->reveal());
        $request = $this->requestFactory($id);
        $SUT->handle($request);
    }

    /** @test */
    public function returns404_when_load_post_by_id_returns_empty_array(): void
    {
        [
            'SUT' => $SUT,
            'loadPostById' => $loadPostById
        ] = $this->SUTFactory();
        $loadPostById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(404, ['error' => new PostNotFoundException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_http_response_object_when_load_post_by_id_returns_not_empty_array(): void
    {
        ['SUT' => $SUT] = $this->SUTFactory();
        $expectedResponse = $this->responseFactory(200, ['data' => [1]]);
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $this->assertEquals($expectedResponse, $response);
    }

    private function SUTFactory(LoadPostById $loadPostById = null): array
    {
        $loadPostById = $loadPostById ?: new LoadPostByIdSpy();
        $SUT = new ViewPostAction($loadPostById);

        return [
            'SUT' => $SUT,
            'loadPostById' => $loadPostById,
        ];
    }

    private function requestFactory(int $postId = 1): HttpRequest
    {
        $requestBody = ['post_id' => $postId];

        return new HttpRequest($requestBody);
    }
}
