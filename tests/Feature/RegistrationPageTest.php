<?php

namespace Tests\Feature;

use App\Mail\NewUserMailForAdmins;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_allows_unauthenticated_users_to_register_with_pending_status()
    {
        Mail::fake();

        $response = $this->post('/api/auth/register', [
            'name' => 'Calvin',
            'email' => 'calvin@canas.com',
            'dealer' => [
                'id' => 'Johnson City',
                'name' => 'Johnson City',
            ],
        ]);

        $response->dump();

        Mail::assertQueued(NewUserMailForAdmins::class);

        $this->assertDatabaseHas('users', [
            'email' => 'calvin@canas.com',
        ]);
    }
}
