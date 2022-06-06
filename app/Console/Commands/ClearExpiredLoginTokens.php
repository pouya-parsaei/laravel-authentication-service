<?php

namespace App\Console\Commands;

use App\Models\LoginToken;
use Illuminate\Console\Command;

class ClearExpiredLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Expired Login Tokens';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return LoginToken::expired()->delete();
    }
}
