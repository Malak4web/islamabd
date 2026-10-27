<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_has_correct_fillable_fields()
    {
        $admin = new Admin();
        $this->assertEquals(['name', 'email', 'role', 'password'], $admin->getFillable());
    }

    public function test_admin_password_is_hidden()
    {
        $admin = new Admin();
        $this->assertContains('password', $admin->getHidden());
        $this->assertContains('remember_token', $admin->getHidden());
    }

    public function test_admin_factory_creates_valid_instance()
    {
        $admin = Admin::factory()->create();
        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertDatabaseHas('admins', [
            'email' => $admin->email,
        ]);
        $this->assertTrue($admin->isAdmin());
    }

    public function test_admin_roles_helper_methods()
    {
        $admin = new Admin(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isDataEntry());

        $dataEntry = new Admin(['role' => 'data_entry']);
        $this->assertTrue($dataEntry->isDataEntry());
        $this->assertFalse($dataEntry->isAdmin());
    }
}
