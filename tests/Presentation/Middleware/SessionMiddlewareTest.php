<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware;

use App\Data\Protocols\Token\GetTokenPayload;
use App\Presentation\Errors\Http\HttpInternalServerErrorException;
use App\Presentation\Middleware\SessionMiddleware;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Presentation\Actions\ActionTestCase as TestCase;
use Tests\Presentation\Actions\Mocks\GetTokenPayloadSpy;

final class SessionMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?GetTokenPayload $getTokenPayload = null): array
    {
        $getTokenPayload = $getTokenPayload ?: new GetTokenPayloadSpy();
        $SUT = new SessionMiddleware($getTokenPayload);

        return [
            'SUT' => $SUT,
            'getTokenPayload' => $getTokenPayload,
        ];
    }

    private function requestFactory($authToken = 'token.token'): Request
    {
        $body = ['authToken' => $authToken];

        return new Request($body);
    }

    /** @test */
    public function returns_500_when_get_token_paylod_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $getTokenPayloadProphecy = $this->prophesize(GetTokenPayload::class);
        $token = 'token.token';
        $getTokenPayloadProphecy->get($token)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($getTokenPayloadProphecy->reveal());
        $request = $this->requestFactory($token);
        $SUT->process($request);
    }

    /** @test */
    public function returns_401_when_get_token_payload_returns_not_valid_expiration_timed_token(): void
    {
        [
            'SUT' => $SUT,
            'getTokenPayload' => $getTokenPayload,
        ] = $this->SUTFactory();
        $getTokenPayload->result = ['exp' => time() - 1000];
        $request = $this->requestFactory();
        $response = $SUT->process($request);
        $expectedResponse = new HttpResponse(401, []);
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());
    }
}
