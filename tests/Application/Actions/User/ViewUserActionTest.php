<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\ViewUserAction;
use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Application\Actions\Mocks\LoadAccountByUsernameSpy;
use Tests\TestCase;

class ViewUserActionTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?LoadAccountByUsername $loadAccountByUsername = null): array
    {
        $loadAccountByUsername = $loadAccountByUsername ?: new LoadAccountByUsernameSpy();
        $SUT = new ViewUserAction($loadAccountByUsername);
        return [
            "SUT" => $SUT,
            "loadAccountByUsername" => $loadAccountByUsername
        ];
    }

    /** @test */
    public function returns_500_when_LoadAccountByUsername_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountByUsername = $this->prophesize(LoadAccountByUsername::class);
        $username = 'username';
        $loadAccountByUsername->load($username)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($loadAccountByUsername->reveal());
        $SUT->handle($username);
    }

    /** @test */
    public function returns_404_when_LoadAccountByUsername_returns_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountByUsername" => $loadAccountByUsername
        ] = $this->SUTFactory();
        $loadAccountByUsername->result = [];
        $response = $SUT->handle("username");
        $expectedResponse = new HttpResponse(404, ["error" => new UserNotFoundException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_LoadAccountByUsername_returns_not_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountByUsername" => $loadAccountByUsername
        ] = $this->SUTFactory();
        $expectedResponse = new HttpResponse(200, ["data" => [1]]);
        $response = $SUT->handle("username");
        $this->assertEquals($expectedResponse, $response);
    }
}
