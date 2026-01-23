<?php

namespace Tests\Unit\Models;

use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_business_unit()
    {
        $unit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Unit Perdagangan',
            'description' => 'Unit usaha perdagangan',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('business_units', [
            'code' => 'BU001',
            'name' => 'Unit Perdagangan',
        ]);
    }

    /** @test */
    public function active_scope_filters_active_units()
    {
        BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Active Unit',
            'is_active' => true,
        ]);

        BusinessUnit::create([
            'code' => 'BU002',
            'name' => 'Inactive Unit',
            'is_active' => false,
        ]);

        $activeUnits = BusinessUnit::active()->get();

        $this->assertCount(1, $activeUnits);
        $this->assertEquals('Active Unit', $activeUnits->first()->name);
    }

    /** @test */
    public function business_unit_has_many_users()
    {
        $unit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => 'password',
            'role' => 'unit_admin',
            'business_unit_id' => $unit->id,
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => 'password',
            'role' => 'unit_admin',
            'business_unit_id' => $unit->id,
        ]);

        $this->assertCount(2, $unit->users);
    }
}
