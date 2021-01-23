<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\Integration\IntegrationTestCase as TestCase;

/**  Route: POST /users  */
class CreateUserTest extends TestCase
{
    private function userProvider(string $index = 'random'): array
    {
        return ['random' => ['user' => [
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
            'name' => $this->faker->name,
        ]]][$index];
    }

    /** @test */
    public function returns_expected_array_when_correct_values_are_used()
    {
        $request = $this->createRequest('POST', '/users');
        $requestBody = json_encode($this->userProvider());
        $request->getBody()->write($requestBody);
        $response = $this->app->handle($request);
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('authToken', $responseBody['data']);
    }
}
