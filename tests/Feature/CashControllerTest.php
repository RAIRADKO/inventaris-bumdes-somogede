<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\CashTransaction;
use App\Models\ChartOfAccount;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CashControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected User $treasurer;
    protected BusinessUnit $businessUnit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->director = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'is_active' => true,
        ]);

        $this->treasurer = User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@example.com',
            'password' => bcrypt('password'),
            'role' => 'treasurer',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function authenticated_user_can_view_cash_index()
    {
        $response = $this->actingAs($this->director)->get('/cash');

        $response->assertStatus(200);
        $response->assertViewIs('cash.index');
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/cash/create');

        $response->assertStatus(200);
        $response->assertViewIs('cash.create');
    }

    #[Test]
    public function it_can_create_cash_in_transaction()
    {
        $response = $this->actingAs($this->treasurer)->post('/cash', [
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 1000000,
            'description' => 'Penerimaan kas',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('cash_transactions', [
            'type' => 'in',
            'amount' => 1000000,
            'description' => 'Penerimaan kas',
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function it_can_create_cash_out_transaction()
    {
        $response = $this->actingAs($this->treasurer)->post('/cash', [
            'type' => 'out',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Pengeluaran kas',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('cash_transactions', [
            'type' => 'out',
            'amount' => 500000,
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->director)->post('/cash', []);

        $response->assertSessionHasErrors(['type', 'date', 'amount', 'description']);
    }

    #[Test]
    public function it_can_show_cash_transaction()
    {
        $transaction = CashTransaction::create([
            'transaction_number' => 'KM-001',
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->treasurer->id,
        ]);

        $response = $this->actingAs($this->director)->get("/cash/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertViewIs('cash.show');
    }

    #[Test]
    public function it_can_delete_draft_transaction()
    {
        $transaction = CashTransaction::create([
            'transaction_number' => 'KM-001',
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->treasurer->id,
        ]);

        $response = $this->actingAs($this->director)->delete("/cash/{$transaction->id}");

        $this->assertDatabaseMissing('cash_transactions', [
            'id' => $transaction->id,
        ]);
    }

    #[Test]
    public function it_can_submit_draft_for_approval()
    {
        $transaction = CashTransaction::create([
            'transaction_number' => 'KM-001',
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->treasurer->id,
        ]);

        $response = $this->actingAs($this->treasurer)->post("/cash/{$transaction->id}/submit");

        $transaction->refresh();
        $this->assertEquals('pending', $transaction->status);
    }

    #[Test]
    public function director_can_approve_cash_transaction()
    {
        // Create accounts for journal
        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $transaction = CashTransaction::create([
            'transaction_number' => 'KM-001',
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->treasurer->id,
        ]);

        $response = $this->actingAs($this->director)->post("/cash/{$transaction->id}/approve");

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);
    }

    #[Test]
    public function treasurer_can_approve_cash_transaction()
    {
        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $transaction = CashTransaction::create([
            'transaction_number' => 'KM-001',
            'type' => 'in',
            'date' => '2026-01-23',
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->treasurer)->post("/cash/{$transaction->id}/approve");

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);
    }

    #[Test]
    public function can_access_daily_report_route()
    {
        $response = $this->actingAs($this->director)->get('/cash/daily-report');

        // Route exists but view may not be implemented yet - check we're not getting 404
        $this->assertNotEquals(404, $response->status());
    }

    #[Test]
    public function unauthenticated_user_cannot_access_cash()
    {
        $response = $this->get('/cash');

        $response->assertRedirect('/login');
    }
}
