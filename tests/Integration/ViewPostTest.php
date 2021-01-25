<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\Integration\IntegrationTestCase as TestCase;

/**  Route: GET /posts/{post_id}  */
class ViewPostTest extends TestCase
{
    /** @test */
    public function returns_expected_array_when_correct_values_are_used()
    {
        $request = $this->createRequest('GET', '/posts/1');
        $response = $this->app->handle($request);
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $responseBody['statusCode']);
    }
}
