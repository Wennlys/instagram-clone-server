<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Domain\Usecases\UpdateAccountInformations;
use App\Presentation\Actions\User\UpdateUserAction;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpRequest;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\LoadAccountByIdSpy;
use Tests\Presentation\Actions\Mocks\UpdateAccountInformationsSpy;
use Tests\Presentation\Actions\ActionTestCase as TestCase;

final class UpdateUserActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function returns500_when_load_account_by_id_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $userId = 1;
        $loadAccountById->load($userId)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($loadAccountById->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_http_response_object_when_load_account_by_id_returns_empty_array(): void
    {
        [
            'SUT' => $SUT,
            'loadAccountById' => $loadAccountById
        ] = $this->SUTFactory();
        $loadAccountById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ['error' => new UserNotFoundException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns500_when_update_account_informations_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $updateAccountInformationsProphesize = $this->prophesize(UpdateAccountInformations::class);
        $updateAccountInformationsProphesize->update(new User(), 1)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory(null, $updateAccountInformationsProphesize->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_http_response_object_when_update_account_informations_returns_false(): void
    {
        [
            'SUT' => $SUT,
            'updateAccountInformations' => $updateAccountInformations
        ] = $this->SUTFactory();
        $updateAccountInformations->result = false;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(400, ['error' => new UserCouldNotBeUpdatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_http_response_object_when_update_account_informations_returns_true(): void
    {
        ['SUT' => $SUT] = $this->SUTFactory();
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(200, ['data' => true]);
        $this->assertEquals($expectedResponse, $response);
    }

    private function SUTFactory(?LoadAccountById $loadAccountById = null, ?UpdateAccountInformations $updateAccountInformations = null): array
    {
        $updateAccountInformations = $updateAccountInformations ?: new UpdateAccountInformationsSpy();
        $loadAccountById = $loadAccountById ?: new LoadAccountByIdSpy();
        $SUT = new UpdateUserAction($loadAccountById, $updateAccountInformations);

        return [
            'SUT' => $SUT,
            'loadAccountById' => $loadAccountById,
            'updateAccountInformations' => $updateAccountInformations,
        ];
    }

    private function requestFactory(?User $user = null, int $userId = 1): HttpRequest
    {
        $user = $user ?: new User();
        $requestBody = ['user' => $user, 'userId' => $userId];

        return new HttpRequest($requestBody);
    }
}
