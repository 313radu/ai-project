<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_privacy_and_cookie_pages_are_available(): void
    {
        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('Privacy policy')
            ->assertSee('data-cookie-consent', false);

        $this->get(route('cookies'))
            ->assertOk()
            ->assertSee('Cookie policy')
            ->assertSee('Manage cookies');
    }

    public function test_public_pages_include_the_cookie_consent_manager(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-cookie-consent', false)
            ->assertSee('Only necessary');
    }
}
