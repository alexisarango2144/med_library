<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:mark-overdue-loans')]
#[Description('Command description')]
class MarkOverdueLoans extends Command
{
    protected $signature = 'loans:mark-overdue';
    protected $description = 'Marca como vencidos (overdue) los préstamos activos con más de 15 días.';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = Loan::where('status', 'active')
            ->where(function ($query){
                $query->whereDate('estimated_return_date', '<', now()->toDateString())
                    ->orWhere('borrowed_at', '<=', now()->subDays(15)->toDateString());
            })
            ->update(['status' => 'overdue']);

        $this->info("Préstamos marcados como vencidos: {$updated}");

        return self::SUCCESS;
    }
}
