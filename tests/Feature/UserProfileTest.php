<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Su Su',
            'email' => 'susu@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('Su Su');
        $response->assertSee('susu@example.com');
        $response->assertSee('Profile Settings');
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $photo = UploadedFile::fake()->create('my_avatar.png', 100, 'image/png');

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $photo,
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
        $this->assertStringContainsString('storage/avatars/', $user->avatar_url);
    }

    public function test_user_can_remove_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar' => 'avatars/sample.jpg',
        ]);
        Storage::disk('public')->put('avatars/sample.jpg', 'fake-image-bytes');

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'remove_avatar' => 1,
        ]);

        $response->assertRedirect('/profile');
        $user->refresh();
        $this->assertNull($user->avatar);
        $this->assertNull($user->avatar_url);
        Storage::disk('public')->assertMissing('avatars/sample.jpg');
    }

    public function test_avatar_must_be_valid_image(): void
    {
        $user = User::factory()->create();
        $fakeDoc = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $fakeDoc,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_initials_accessor(): void
    {
        $userA = new User(['name' => 'Ma Shwe Chaw']);
        $this->assertSame('MS', $userA->initials);

        $userB = new User(['name' => 'Aidan']);
        $this->assertSame('A', $userB->initials);
    }
}

