<?php

namespace Tests\Feature;

use App\Models\RepositorySetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryUploadToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_upload_shows_closed_page_when_session_is_closed()
    {
        $response = $this->get(route('public.upload.create', ['mahasiswa', 'skripsi']));

        $response->assertOk();
        $response->assertViewIs('repository.closed');
    }

    public function test_public_actor_pages_are_available_to_guests()
    {
        $this->get(route('public.mahasiswa.home'))
            ->assertOk()
            ->assertViewIs('pages.actor-home');

        $this->get(route('public.dosen.home'))
            ->assertOk()
            ->assertViewIs('pages.actor-home');
    }

    public function test_public_upload_form_opens_after_admin_opens_session()
    {
        RepositorySetting::create([
            'key' => 'upload_skripsi',
            'value' => 'open',
        ]);

        $response = $this->get(route('public.upload.create', ['mahasiswa', 'skripsi']));

        $response->assertOk();
        $response->assertViewIs('repository.form');
        $response->assertSee('Upload Dokumen');
    }

    public function test_contact_route_exists_for_cached_or_legacy_views()
    {
        $this->get(route('contact'))->assertRedirect(route('home'));
    }
}
