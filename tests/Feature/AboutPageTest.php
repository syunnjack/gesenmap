<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_is_reachable(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('このサイトについて');
    }

    public function test_about_link_is_present_in_footer(): void
    {
        $response = $this->get('/');

        $response->assertSee(route('about'), false);
    }
}
