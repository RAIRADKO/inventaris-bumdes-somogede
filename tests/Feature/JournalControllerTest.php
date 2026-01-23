<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JournalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected User $accountant;
    protected User $unitAdmin;
    protected BusinessUnit $businessUnit;
    protected FiscalPeriod $fiscalPeriod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->fiscalPeriod = FiscalPeriod::create([
            'name' => 'Tahun 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
        ]);

        $this->director = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'is_active' => true,
        ]);

        $this->accountant = User::create([
            'name' => 'Akuntan',
            'email' => 'akuntan@example.com',
            'password' => bcrypt('password'),
            'role' => 'accountant',
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
    public function director_can_access_journals_index()
    {
        // Route is /journal (singular)
        $response = $this->actingAs($this->director)->get('/journal');

        $response->assertStatus(200);
    }

    #[Test]
    public function accountant_can_access_journals_index()
    {
        $response = $this->actingAs($this->accountant)->get('/journal');

        $response->assertStatus(200);
    }

    #[Test]
    public function director_can_create_manual_journal()
    {
        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->post('/journal', [
            'date' => '2026-01-23',
            'description' => 'Test Manual Journal',
            'business_unit_id' => $this->businessUnit->id,
            'entries' => [
                [
                    'account_id' => $cashAccount->id,
                    'description' => 'Debit entry',
                    'debit' => 1000000,
                    'credit' => 0,
                ],
                [
                    'account_id' => $revenueAccount->id,
                    'description' => 'Credit entry',
                    'debit' => 0,
                    'credit' => 1000000,
                ],
            ],
        ]);

        $this->assertDatabaseHas('journals', [
            'description' => 'Test Manual Journal',
            'type' => 'manual',
        ]);
    }

    #[Test]
    public function director_can_view_journal_details()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'status' => 'draft',
            'type' => 'manual',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->get("/journal/{$journal->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function director_can_approve_journal()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
            'created_by' => $this->director->id,
        ]);

        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'debit' => 100000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'debit' => 0,
            'credit' => 100000,
        ]);

        $response = $this->actingAs($this->director)->post("/journal/{$journal->id}/approve");

        $journal->refresh();
        $this->assertEquals('approved', $journal->status);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_journals()
    {
        $response = $this->get('/journal');

        $response->assertRedirect('/login');
    }
}
