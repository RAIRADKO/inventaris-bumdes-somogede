<?php

namespace Tests\Unit\Models;

use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function it_hides_password_and_remember_token()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
            'role' => 'director',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /** @test */
    public function director_role_check_returns_true_for_director()
    {
        $user = User::create([
            'name' => 'Director User',
            'email' => 'director@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        $this->assertTrue($user->isDirector());
        $this->assertFalse($user->isTreasurer());
        $this->assertFalse($user->isUnitAdmin());
        $this->assertFalse($user->isSupervisor());
        $this->assertFalse($user->isAccountant());
    }

    /** @test */
    public function treasurer_role_check_returns_true_for_treasurer()
    {
        $user = User::create([
            'name' => 'Treasurer User',
            'email' => 'treasurer@example.com',
            'password' => 'password',
            'role' => 'treasurer',
        ]);

        $this->assertTrue($user->isTreasurer());
        $this->assertFalse($user->isDirector());
    }

    /** @test */
    public function director_can_approve_transactions()
    {
        $director = User::create([
            'name' => 'Director',
            'email' => 'director@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        $this->assertTrue($director->canApprove());
    }

    /** @test */
    public function treasurer_can_approve_transactions()
    {
        $treasurer = User::create([
            'name' => 'Treasurer',
            'email' => 'treasurer@example.com',
            'password' => 'password',
            'role' => 'treasurer',
        ]);

        $this->assertTrue($treasurer->canApprove());
    }

    /** @test */
    public function unit_admin_cannot_approve_transactions()
    {
        $unitAdmin = User::create([
            'name' => 'Unit Admin',
            'email' => 'unitadmin@example.com',
            'password' => 'password',
            'role' => 'unit_admin',
        ]);

        $this->assertFalse($unitAdmin->canApprove());
    }

    /** @test */
    public function director_can_manage_users()
    {
        $director = User::create([
            'name' => 'Director',
            'email' => 'director@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        $this->assertTrue($director->canManageUsers());
    }

    /** @test */
    public function non_director_cannot_manage_users()
    {
        $treasurer = User::create([
            'name' => 'Treasurer',
            'email' => 'treasurer@example.com',
            'password' => 'password',
            'role' => 'treasurer',
        ]);

        $this->assertFalse($treasurer->canManageUsers());
    }

    /** @test */
    public function director_and_accountant_can_manage_journals()
    {
        $director = User::create([
            'name' => 'Director',
            'email' => 'director@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@example.com',
            'password' => 'password',
            'role' => 'accountant',
        ]);

        $this->assertTrue($director->canManageJournals());
        $this->assertTrue($accountant->canManageJournals());
    }

    /** @test */
    public function role_label_returns_correct_indonesian_label()
    {
        $director = User::create([
            'name' => 'Director',
            'email' => 'director@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        $this->assertEquals('Direktur', $director->role_label);
    }

    /** @test */
    public function active_scope_filters_active_users()
    {
        User::create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => 'password',
            'role' => 'director',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => 'password',
            'role' => 'director',
            'is_active' => false,
        ]);

        $activeUsers = User::active()->get();

        $this->assertCount(1, $activeUsers);
        $this->assertEquals('Active User', $activeUsers->first()->name);
    }

    /** @test */
    public function user_belongs_to_business_unit()
    {
        $businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Business Unit',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'unit_admin',
            'business_unit_id' => $businessUnit->id,
        ]);

        $this->assertInstanceOf(BusinessUnit::class, $user->businessUnit);
        $this->assertEquals('Test Business Unit', $user->businessUnit->name);
    }
}
