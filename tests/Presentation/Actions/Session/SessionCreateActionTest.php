<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Session;

use App\Domain\Usecases\Authentication;
use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Actions\Session\SessionCreateAction;
use App\Presentation\Errors\Http\HttpInternalServerErrorException;
use App\Presentation\Errors\Http\HttpUnauthorizedException;
use App\Presentation\Protocols\HttpRequest;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Presentation\Actions\ActionTestCase as TestCase;
use Tests\Presentation\Actions\Mocks\AuthenticationSpy;
use Tests\Presentation\Actions\Mocks\LoadAccountByUsernameSpy;

class SessionCreateActionTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?Authentication $authentication = null, ?LoadAccountByUsername $loadAccountByUsername = null): array
    {
        $authentication = $authentication ?: new AuthenticationSpy();
        $loadAccountByUsername = $loadAccountByUsername ?: new LoadAccountByUsernameSpy();
        $SUT = new SessionCreateAction($authentication, $loadAccountByUsername);

        return [
            'SUT' => $SUT,
            'authentication' => $authentication,
            'loadAccountByUsername' => $loadAccountByUsername,
        ];
    }

    private function requestFactory(string $username = null, string $password = null)
    {
        $username = $username ?: $this->faker->userName;
        $password = $password ?: $this->faker->password(8);

        return new HttpRequest(['username' => $username, 'password' => $password]);
    }

    /** @test */
    public function returns_500_when_authentication_throws(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $authenticationProphecy = $this->prophesize(Authentication::class);
        $username = $this->faker->userName;
        $password = $this->faker->password(8);
        $authenticationProphecy->authenticate($username, $password)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($authenticationProphecy->reveal());
        $request = $this->requestFactory($username, $password);
        $SUT->handle($request);
    }

    /** @test */
    public function returns_401_when_authentication_fails(): void
    {
        [
            'SUT' => $SUT,
            'authentication' => $authentication
        ] = $this->SUTFactory();
        $authentication->result = null;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(401, ['error' => new HttpUnauthorizedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_500_when_find_user_of_username_throws(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountByUsernameProphecy = $this->prophesize(LoadAccountByUsername::class);
        $username = $this->faker->userName;
        $loadAccountByUsernameProphecy->load($username)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory(null, $loadAccountByUsernameProphecy->reveal());
        $request = $this->requestFactory($username);
        $SUT->handle($request);
    }

    /** @test */
    public function returns_expected_http_response_when_authenticates(): void
    {
        [
            'SUT' => $SUT,
            'authentication' => $authentication,
            'loadAccountByUsername' => $loadAccountByUsername,
        ] = $this->SUTFactory();
        $loadAccountByUsername->result = [$this->faker->rgbColorAsArray];
        $authentication->result = $this->faker->linuxPlatformToken;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(200, ['data' => ['user' => $loadAccountByUsername->result], 'authenticationToken' => $authentication->result]);
        $this->assertEquals($expectedResponse, $response);
    }
}
