<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function generateRandomDetails()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create([
            'status' => 'ACTIVE',
        ]);

        $this->actingAs($user);

        return [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
            'phone_number' => $faker->phoneNumber,
            'job_title' => $faker->jobTitle,
        ];
    }

    /**
     * @group userProfileTest
     * @test
     */
    public function it_allows_the_user_to_view_their_own_profile()
    {
        $data = $this->generateRandomDetails();

        $response = $this->get('api/my-profile');

        $shouldBeTheResponse = User::find(auth()->user()->id)->toArray();

        $response->assertExactJson($shouldBeTheResponse);
    }

    /**
     * @group userProfileTest
     * @test
     */
    public function it_allows_the_user_to_update_their_profile_without_new_password()
    {
        $data = $this->generateRandomDetails();

        $response = $this->patch('api/my-profile/'.auth()->user()->id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'job_title' => $data['job_title'],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @group userProfileTest
     * @test
     */
    public function it_allows_the_user_to_update_their_profile_with_new_password()
    {
        $data = $this->generateRandomDetails();

        $response = $this->patch('api/my-profile/'.auth()->user()->id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => 'password',
            'job_title' => $data['job_title'],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @group userProfileTest
     * @test
     */
    public function it_allows_the_user_to_upload_their_own_picture()
    {
        Storage::fake('s3');

        $data = $this->generateRandomDetails();

        $response = $this->patch('api/my-profile/'.auth()->user()->id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => 'password',
            'job_title' => $data['job_title'],
            'profile_picture' => UploadedFile::fake()->image('sample_profile_picture69.jpg'),
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $data);

        $oldPicture = User::find(auth()->user()->id)->profile_picture;

        Storage::disk('s3')->assertExists('users/'.auth()->user()->id.'/'.$oldPicture);

        $response = $this->patch('api/my-profile/'.auth()->user()->id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => 'password',
            'profile_picture' => UploadedFile::fake()->image('sample_profile_picture69.jpg'),
            'job_title' => $data['job_title'],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $data);

        $newPicture = User::find(auth()->user()->id)->profile_picture;

        Storage::disk('s3')->assertExists('users/'.auth()->user()->id.'/'.$newPicture);
        Storage::disk('s3')->assertMissing('users/'.auth()->user()->id.'/'.$oldPicture);
    }
}
