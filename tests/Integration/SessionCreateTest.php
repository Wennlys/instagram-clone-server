<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\Integration\IntegrationTestCase as TestCase;

/**  Route: POST /sessions  */
class SessionCreateTest extends TestCase
{
    /** @test */
    public function returns_expected_array_when_correct_values_are_used()
    {
        $request = $this->createRequest('POST', '/sessions');
        $request->getBody()->write('{"username": "user2", "password": "123456"}');
        $response = $this->app->handle($request);
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $responseBody['statusCode']);
    }
}
