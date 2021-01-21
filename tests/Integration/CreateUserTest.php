<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\DataBaseSetUp;
use Tests\Integration\IntegrationTestCase as TestCase;

/**  Route: POST /users  */
class CreateUserTest extends TestCase
{
    /** @test */
    public function returns_expected_array_when_correct_values_are_used()
    {
        $app = $this->getAppInstance();
        DataBaseSetUp::up();
        $request = $this->createRequest('POST', '/users');
        $requestBody = json_encode(['user' => [
            'username' => 'username1',
            'email' => 'email1@mail.com',
            'password' => '12345678',
            'name' => 'User Name',
        ]]);
        $request->getBody()->write($requestBody);
        $response = $app->handle($request);
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('authToken', $responseBody['data']);
    }
}
