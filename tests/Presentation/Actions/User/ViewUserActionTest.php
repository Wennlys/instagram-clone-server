<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Actions\User\ViewUserAction;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\LoadAccountByUsernameSpy;
use Tests\TestCase;

final class ViewUserActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function returns500WhenLoadAccountByUsernameThrowsException(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountByUsername = $this->prophesize(LoadAccountByUsername::class);
        $username = 'username';
        $loadAccountByUsername->load($username)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($loadAccountByUsername->reveal());
        $request = $this->requestFactory($username);
        $SUT->handle($request);
    }

    /** @test */
    public function returns404WhenLoadAccountByUsernameReturnsEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'loadAccountByUsername' => $loadAccountByUsername
        ] = $this->SUTFactory();
        $loadAccountByUsername->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = new HttpResponse(404, ['error' => new UserNotFoundException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returnsMatchingHttpResponseObjectWhenLoadAccountByUsernameReturnsNotEmptyArray(): void
    {
        ['SUT' => $SUT] = $this->SUTFactory();
        $expectedResponse = new HttpResponse(200, ['data' => [1]]);
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $this->assertEquals($expectedResponse, $response);
    }

    private function SUTFactory(?LoadAccountByUsername $loadAccountByUsername = null): array
    {
        $loadAccountByUsername = $loadAccountByUsername ?: new LoadAccountByUsernameSpy();
        $SUT = new ViewUserAction($loadAccountByUsername);

        return [
            'SUT' => $SUT,
            'loadAccountByUsername' => $loadAccountByUsername,
        ];
    }

    private function requestFactory($username = 'username'): HttpRequest
    {
        $requestBody = ['username' => $username];

        return new HttpRequest($requestBody);
    }

    private function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }
}
