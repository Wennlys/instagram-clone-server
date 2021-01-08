<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\ViewUserAction;
use App\Domain\Usecases\LoadAccountByUsername;
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
            "SUT" => $SUT
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
}
