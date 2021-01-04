<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Infrastructure\Db\SQL\UserRepository;
use App\Domain\Models\User;
use DI\Container;
use Prophecy\PhpUnit\ProphecyTrait;
use ReallySimpleJWT\Token;
use Tests\TestCase;

class ListUsersActionTest extends TestCase
{
    use ProphecyTrait;

    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User('bill.gates', 'bill.gates@mail.com', 'Bill Gates', 'Gates123');

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findAll()
            ->willReturn([$user])
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $token = Token::create(1, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('GET', '/users', ['HTTP_ACCEPT' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
