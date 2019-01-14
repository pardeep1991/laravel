<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class BackCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BackCheck:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'background check';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('settings')->insert([
            'percentage'=>'1','is_active'=>'2'

        ]);
    }
}
