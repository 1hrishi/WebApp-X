<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
// use Tests\TestCase;

class UserLoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function the_login_route_exists()
    {
        $this->get('/login')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_login_with_email_and_password()
    {
        $user = factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);
        Event::fake();

        $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        // Event::assertDispatched(UserLoggedIn::class);
        $this->assertAuthenticatedAs($user);
    }


    /** @test */
    public function inactive_users_cant_login()
    {
        factory(User::class)->states('inactive')->create([
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        // $response->assertSessionHas('flash_danger');
        $this->assertFalse($this->isAuthenticated());
    }
    
    /** @test */
    // public function unconfirmed_user_cant_login()
    // {
    //     factory(User::class)->states('unconfirmed')->create([
    //         'email' => 'john@example.com',
    //         'password' => 'secret',
    //     ]);

    //     $response = $this->post('/login', [
    //         'email' => 'john@example.com',
    //         'password' => 'secret',
    //     ]);

    //     $response->assertSessionHas('flash_danger');
    //     $this->assertFalse($this->isAuthenticated());
    // }
    
    /** @test */
    public function email_is_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '12345',
        ]);

        $response->assertSessionHasErrors('email');
    }


}


