<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\UpdateUserAction;
use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Domain\Usecases\UpdateAccountInformations;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Application\Actions\Mocks\LoadAccountByIdSpy;
use Tests\Application\Actions\Mocks\UpdateAccountInformationsSpy;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?LoadAccountById $loadAccountById = null, ?UpdateAccountInformations $updateAccountInformations = null): array
    {
        $updateAccountInformations = $updateAccountInformations ?: new UpdateAccountInformationsSpy();
        $loadAccountById = $loadAccountById ?: new LoadAccountByIdSpy();
        $SUT = new UpdateUserAction($loadAccountById, $updateAccountInformations);
        return [
            "SUT" => $SUT,
            "loadAccountById" => $loadAccountById
        ];
    }

    /** @test */
    public function returns_500_when_LoadAccountById_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $userId = 1;
        $loadAccountById->load($userId)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($loadAccountById->reveal());
        $SUT->handle(new User(), $userId);
    }
}
