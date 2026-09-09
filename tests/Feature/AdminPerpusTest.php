<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPerpusTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->siswa = User::factory()->create([
            'role' => 'user',
        ]);
    }

    public function test_admin_dashboard_accessible_only_by_admin(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_can_crud_buku(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.buku.store'), [
            'kode_buku' => 'BK-100',
            'judul' => 'Buku Uji Coba',
            'pengarang' => 'Penulis Uji',
            'penerbit' => 'Penerbit Uji',
            'stok' => 10,
        ]);

        $response->assertRedirect(route('admin.buku.index'));
        $this->assertDatabaseHas('bukus', [
            'kode_buku' => 'BK-100',
            'stok' => 10,
        ]);

        $buku = Buku::where('kode_buku', 'BK-100')->first();

        $response = $this->actingAs($this->admin)->put(route('admin.buku.update', $buku->id), [
            'kode_buku' => 'BK-100',
            'judul' => 'Buku Uji Coba Diperbarui',
            'pengarang' => 'Penulis Uji',
            'penerbit' => 'Penerbit Uji',
            'stok' => 8,
        ]);

        $response->assertRedirect(route('admin.buku.index'));
        $this->assertDatabaseHas('bukus', [
            'judul' => 'Buku Uji Coba Diperbarui',
            'stok' => 8,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.buku.destroy', $buku->id));
        $response->assertRedirect(route('admin.buku.index'));
        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    public function test_admin_can_crud_user_and_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.user.store'), [
            'name' => 'Anggota Baru',
            'email' => 'baru@perpus.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('admin.user.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'baru@perpus.com',
            'role' => 'user',
        ]);

        $newUser = User::where('email', 'baru@perpus.com')->first();

        $response = $this->actingAs($this->admin)->put(route('admin.user.update', $newUser->id), [
            'name' => 'Anggota Baru Edit',
            'email' => 'baru@perpus.com',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('admin.user.index'));
        $this->assertDatabaseHas('users', ['name' => 'Anggota Baru Edit']);

        $response = $this->actingAs($this->admin)->delete(route('admin.user.destroy', $this->admin->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.user.destroy', $newUser->id));
        $response->assertRedirect(route('admin.user.index'));
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_borrowing_reduces_stock_and_returning_restores_stock(): void
    {
        $buku = Buku::create([
            'kode_buku' => 'BK-TEST',
            'judul' => 'Buku Stok Test',
            'pengarang' => 'Tester',
            'penerbit' => 'Tester',
            'stok' => 5,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'user_id' => $this->siswa->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->format('Y-m-d'),
            'tanggal_kembali' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.peminjaman.index'));
        $this->assertDatabaseHas('peminjamans', [
            'user_id' => $this->siswa->id,
            'buku_id' => $buku->id,
            'status' => 'dipinjam',
        ]);

        $buku->refresh();
        $this->assertEquals(4, $buku->stok);

        $peminjaman = Peminjaman::where('user_id', $this->siswa->id)->first();

        $response = $this->actingAs($this->admin)->patch(route('admin.peminjaman.kembali', $peminjaman->id));
        $response->assertRedirect(route('admin.peminjaman.index'));

        $peminjaman->refresh();
        $this->assertEquals('dikembalikan', $peminjaman->status);

        $buku->refresh();
        $this->assertEquals(5, $buku->stok);
    }
}