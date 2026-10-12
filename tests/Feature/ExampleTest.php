<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test root route redirects to login or dashboard.
     */
    public function test_root_redirects_properly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
