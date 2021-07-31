<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class refreshLpse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshLpse:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command akan merefresh data lpse';

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
     * @return int
     */
    public function handle()
    {
        $start = microtime(true);

        $controller = app()->make('App\Http\Controllers\Data_Controller');
        app()->call([$controller, 'Main']);
        
        $time_elapsed_secs = (microtime(true) - $start)/60;

        echo "Data telah direfresh, memakan waktu : $time_elapsed_secs menit";
    }
}
