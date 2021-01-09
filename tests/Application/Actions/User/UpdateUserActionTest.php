<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\UpdateUserAction;
use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Domain\Usecases\UpdateAccountInformations;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
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
            "loadAccountById" => $loadAccountById,
            "updateAccountInformations" => $updateAccountInformations
        ];
    }

    private function requestFactory(?User $user = null, int $userId = 1): HttpRequest
    {
        $user = $user ?: new User();
        $requestBody = ["user" => $user, "userId" => $userId];
        return new HttpRequest($requestBody);
    }

    private function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }

    /** @test */
    public function returns_500_when_LoadAccountById_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $userId = 1;
        $loadAccountById->load($userId)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($loadAccountById->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_LoadAccountById_returns_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountById" => $loadAccountById
        ] = $this->SUTFactory();
        $loadAccountById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ["error" => new UserNotFoundException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_500_when_UpdateAccountInformations_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $updateAccountInformationsProphesize = $this->prophesize(UpdateAccountInformations::class);
        $updateAccountInformationsProphesize->update(new User(), 1)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory(null, $updateAccountInformationsProphesize->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_UpdateAccountInformations_returns_false(): void
    {
        [
            "SUT" => $SUT,
            "updateAccountInformations" => $updateAccountInformations
        ] = $this->SUTFactory();
        $updateAccountInformations->result = false;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(400, ["error" => new UserCouldNotBeUpdatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_UpdateAccountInformations_returns_true(): void
    {
        ["SUT" => $SUT] = $this->SUTFactory();
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(200, ["data" => true]);
        $this->assertEquals($expectedResponse, $response);
    }
}
