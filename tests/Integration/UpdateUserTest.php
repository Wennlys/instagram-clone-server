<?php

declare(strict_types=1);

namespace Tests\Integration;

use ReallySimpleJWT\Token;
use Tests\Integration\IntegrationTestCase as TestCase;

/**  Route: PUT /users/{user_id}  */
class UpdateUserTest extends TestCase
{
    /** @test */
    public function returns_expected_array_when_correct_values_are_used(): void
    {
        $userId = 1;
        $token = Token::create($userId, $_ENV['SECRET'], time() + 1000, $_ENV['ISSUER']);
        $request = $this->createRequest('PUT', "/users/{$userId}", ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $requestBody = json_encode(['user' => [
            'username' => 'username1',
            'email' => 'email1@mail.com',
            'password' => '12345678',
            'name' => 'User Name',
        ]]);
        $request->getBody()->write($requestBody);
        $response = $this->app->handle($request);
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertTrue($responseBody['data']);
    }

    /** @test */
    public function fails_when_invalid_token_is_sent(): void
    {
        $request = $this->createRequest('PUT', '/users/1', ['Content-Type' => 'application/json', 'Authorization' => 'Bearer token']);
        $requestBody = json_encode(['user' => [
            'username' => 'username1',
            'email' => 'email1@mail.com',
            'password' => '12345678',
            'name' => 'User Name',
        ]]);
        $request->getBody()->write($requestBody);
        $response = $this->app->handle($request);
        $this->assertJsonStringEqualsJsonString('{"statusCode":401,"error":"Unauthorized."}', (string) $response->getBody());
    }
}
