<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test root route renders public landing page.
     */
    public function test_root_returns_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('REDMAGIC');
    }
}
