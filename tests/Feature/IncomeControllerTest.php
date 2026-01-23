<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\IncomeTransaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IncomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected User $unitAdmin;
    protected BusinessUnit $businessUnit;
    protected TransactionCategory $incomeCategory;
    protected ChartOfAccount $revenueAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->incomeCategory = TransactionCategory::create([
            'name' => 'Pendapatan Penjualan',
            'type' => 'income',
            'account_id' => $this->revenueAccount->id,
            'is_active' => true,
        ]);

        $this->director = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'is_active' => true,
        ]);

        $this->unitAdmin = User::create([
            'name' => 'Unit Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'unit_admin',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function authenticated_user_can_view_income_index()
    {
        $response = $this->actingAs($this->director)->get('/income');

        $response->assertStatus(200);
        $response->assertViewIs('income.index');
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/income/create');

        $response->assertStatus(200);
        $response->assertViewIs('income.create');
    }

    #[Test]
    public function it_can_create_income_transaction()
    {
        $response = $this->actingAs($this->director)->post('/income', [
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'business_unit_id' => $this->businessUnit->id,
            'amount' => 1000000,
            'description' => 'Penjualan produk',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('income_transactions', [
            'amount' => 1000000,
            'description' => 'Penjualan produk',
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->director)->post('/income', []);

        $response->assertSessionHasErrors(['date', 'category_id', 'amount', 'description']);
    }

    #[Test]
    public function it_can_show_income_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'business_unit_id' => $this->businessUnit->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->get("/income/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertViewIs('income.show');
    }

    #[Test]
    public function it_can_update_draft_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'business_unit_id' => $this->businessUnit->id,
            'amount' => 500000,
            'description' => 'Original',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->put("/income/{$transaction->id}", [
            'date' => '2026-01-24',
            'category_id' => $this->incomeCategory->id,
            'amount' => 750000,
            'description' => 'Updated',
        ]);

        $this->assertDatabaseHas('income_transactions', [
            'id' => $transaction->id,
            'amount' => 750000,
            'description' => 'Updated',
        ]);
    }

    #[Test]
    public function it_cannot_update_non_draft_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->put("/income/{$transaction->id}", [
            'date' => '2026-01-24',
            'category_id' => $this->incomeCategory->id,
            'amount' => 750000,
            'description' => 'Updated',
        ]);

        // Should not be updated
        $this->assertDatabaseHas('income_transactions', [
            'id' => $transaction->id,
            'amount' => 500000,
        ]);
    }

    #[Test]
    public function it_can_delete_draft_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->delete("/income/{$transaction->id}");

        $this->assertDatabaseMissing('income_transactions', [
            'id' => $transaction->id,
        ]);
    }

    #[Test]
    public function it_can_submit_draft_for_approval()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->unitAdmin)->post("/income/{$transaction->id}/submit");

        $transaction->refresh();
        $this->assertEquals('pending', $transaction->status);
    }

    #[Test]
    public function director_can_approve_pending_transaction()
    {
        // Create cash account for journal
        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->director)->post("/income/{$transaction->id}/approve");

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);
        $this->assertEquals($this->director->id, $transaction->approved_by);
    }

    #[Test]
    public function unit_admin_cannot_approve_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->unitAdmin)->post("/income/{$transaction->id}/approve");

        $transaction->refresh();
        $this->assertEquals('pending', $transaction->status); // Should remain pending
    }

    #[Test]
    public function director_can_reject_pending_transaction()
    {
        $transaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'category_id' => $this->incomeCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->director)->post("/income/{$transaction->id}/reject");

        $transaction->refresh();
        $this->assertEquals('rejected', $transaction->status);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_income()
    {
        $response = $this->get('/income');

        $response->assertRedirect('/login');
    }
}
