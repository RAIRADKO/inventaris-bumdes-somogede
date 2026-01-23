<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected User $unitAdmin;
    protected BusinessUnit $businessUnit;
    protected TransactionCategory $expenseCategory;
    protected ChartOfAccount $expenseAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->expenseAccount = ChartOfAccount::create([
            'code' => '5110',
            'name' => 'Beban Operasional',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->expenseCategory = TransactionCategory::create([
            'name' => 'Beban ATK',
            'type' => 'expense',
            'account_id' => $this->expenseAccount->id,
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
    public function authenticated_user_can_view_expense_index()
    {
        $response = $this->actingAs($this->director)->get('/expense');

        $response->assertStatus(200);
        $response->assertViewIs('expense.index');
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/expense/create');

        $response->assertStatus(200);
        $response->assertViewIs('expense.create');
    }

    #[Test]
    public function it_can_create_expense_transaction()
    {
        $response = $this->actingAs($this->director)->post('/expense', [
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'business_unit_id' => $this->businessUnit->id,
            'amount' => 500000,
            'description' => 'Pembelian ATK',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('expense_transactions', [
            'amount' => 500000,
            'description' => 'Pembelian ATK',
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->director)->post('/expense', []);

        $response->assertSessionHasErrors(['date', 'category_id', 'amount', 'description']);
    }

    #[Test]
    public function it_can_show_expense_transaction()
    {
        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'business_unit_id' => $this->businessUnit->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->get("/expense/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertViewIs('expense.show');
    }

    #[Test]
    public function it_can_update_draft_transaction()
    {
        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'amount' => 500000,
            'description' => 'Original',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->put("/expense/{$transaction->id}", [
            'date' => '2026-01-24',
            'category_id' => $this->expenseCategory->id,
            'amount' => 750000,
            'description' => 'Updated',
        ]);

        $this->assertDatabaseHas('expense_transactions', [
            'id' => $transaction->id,
            'amount' => 750000,
            'description' => 'Updated',
        ]);
    }

    #[Test]
    public function it_can_delete_draft_transaction()
    {
        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->delete("/expense/{$transaction->id}");

        $this->assertDatabaseMissing('expense_transactions', [
            'id' => $transaction->id,
        ]);
    }

    #[Test]
    public function it_can_submit_draft_for_approval()
    {
        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'draft',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->unitAdmin)->post("/expense/{$transaction->id}/submit");

        $transaction->refresh();
        $this->assertEquals('pending', $transaction->status);
    }

    #[Test]
    public function director_can_approve_pending_transaction()
    {
        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->director)->post("/expense/{$transaction->id}/approve");

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);
        $this->assertEquals($this->director->id, $transaction->approved_by);
    }

    #[Test]
    public function director_can_reject_pending_transaction()
    {
        $transaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'category_id' => $this->expenseCategory->id,
            'amount' => 500000,
            'description' => 'Test',
            'status' => 'pending',
            'created_by' => $this->unitAdmin->id,
        ]);

        $response = $this->actingAs($this->director)->post("/expense/{$transaction->id}/reject");

        $transaction->refresh();
        $this->assertEquals('rejected', $transaction->status);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_expense()
    {
        $response = $this->get('/expense');

        $response->assertRedirect('/login');
    }
}
