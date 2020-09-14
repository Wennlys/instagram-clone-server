<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Persistence\User\UserRepositoryImpl;
use DI\Container;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $userArray = [
            'username' => 'bill.gates',
            'email' => 'bill.gates@mail.com',
            'name' => 'Bill Gates',
            'password' => 'Gates123',
        ];
        
        $user = new User($userArray['username'], $userArray['email'], $userArray['name'], $userArray['password']);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->store($user)
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write(json_encode($userArray));
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
