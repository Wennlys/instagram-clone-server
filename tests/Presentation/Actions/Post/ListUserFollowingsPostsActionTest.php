<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use Prophecy\PhpUnit\ProphecyTrait;
use App\Presentation\Protocols\HttpRequest;
use App\Data\Protocols\Token\GetTokenPayload;
use App\Presentation\Actions\Post\ListUserFollowingsPostsAction;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\GetTokenPayloadSpy;
use Tests\Presentation\Actions\ActionTestCase as TestCase;

class ListUserFollowingsPostsActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function returns500_when_get_token_payload_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $getTokenPayloadProphecy = $this->prophesize(GetTokenPayload::class);
        $token = 'authorization.token';
        $getTokenPayloadProphecy->get($token)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($getTokenPayloadProphecy->reveal());
        $request = $this->requestFactory($token);
        $SUT->handle($request);
    }

    private function SUTFactory(GetTokenPayload $getTokenPayload = null): array
    {
        $getTokenPayload = $getTokenPayload ?: new GetTokenPayloadSpy();
        $SUT = new ListUserFollowingsPostsAction($getTokenPayload);

        return [
            'SUT' => $SUT,
            'getTokenPayload' => $getTokenPayload,
        ];
    }

    private function requestFactory(string $authToken = 'authorization.token'): HttpRequest
    {
        $requestBody = ['authToken' => $authToken];

        return new HttpRequest($requestBody);
    }
}
