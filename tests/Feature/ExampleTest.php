<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_api_returns_json_welcome_message()
    {
        $this->get("/api")->assertJson([
            "message" => "Welcome to the API"
        ]);
    }
}
