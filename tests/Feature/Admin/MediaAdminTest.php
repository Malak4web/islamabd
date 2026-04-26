<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\MediaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        Storage::fake('public');
    }

    public function test_can_list_media()
    {
        MediaFile::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/media');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_upload_media()
    {
        $file1 = UploadedFile::fake()->create('photo1.jpg', 100);
        $file2 = UploadedFile::fake()->create('photo2.png', 100);

        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/media', [
                'files' => [$file1, $file2]
            ]);

        $response->assertStatus(201);
        $this->assertCount(2, MediaFile::all());
        
        $path1 = MediaFile::first()->path;
        Storage::disk('public')->assertExists($path1);
    }

    public function test_can_delete_media()
    {
        $file = UploadedFile::fake()->create('test.jpg', 100);
        $path = $file->store('media', 'public');
        $media = MediaFile::create([
            'filename' => 'test.jpg',
            'path' => $path
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->deleteJson("/api/admin/media/{$media->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('media_files', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
