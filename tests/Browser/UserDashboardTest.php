<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserDashboardTest extends DuskTestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cant_access_the_dashboard()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
