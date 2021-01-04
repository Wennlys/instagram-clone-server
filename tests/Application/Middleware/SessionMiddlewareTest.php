<?php
declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Middleware\SessionMiddleware;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Server\RequestHandlerInterface;
use ReallySimpleJWT\Exception\ValidateException;
use ReallySimpleJWT\Token;
use Slim\Psr7\Response;
use Tests\TestCase;

class SessionMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    public function testMiddleware() {
        $middleware = new SessionMiddleware();
        $token = Token::create(1, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('PUT', '/users', ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler
            ->handle($request)
            ->willReturn(new Response());
        $response = $middleware->process($request, $handler->reveal());

        $this->assertNotNull($response);
    }

    public function testInvalidToken() {
        $middleware = new SessionMiddleware();
        $token = 'dfasdfasdfasd.asdfasdf.asdfasdfasdf';
        $request = $this->createRequest('PUT', '/users', ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler
            ->handle($request)
            ->willReturn(new Response());

        $this->expectException(ValidateException::class);
        $middleware->process($request, $handler->reveal());
    }

    public function testOutdatedToken() {
        $middleware = new SessionMiddleware();
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MDM2Njc2NzUsImlzcyI6Imluc3RhZ3JhbS5jbG9uZSIsImlhdCI6MTYwMzY2NzY3NH0.KVZ1Fw80AMh58JyxwJCQcwU3TfBSPBLJZaGdEQzzrhI';
        $request = $this->createRequest('PUT', '/users', ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler
            ->handle($request)
            ->willReturn(new Response());

        $response = $middleware->process($request, $handler->reveal());
        $this->assertSame('Invalid token.', (string) $response->getBody());
    }
}
