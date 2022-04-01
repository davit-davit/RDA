<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class clear_log extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will clear storage/logs/laravel.log file';

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
    public function handle() // ეს ფუნქცია სრულდება მაშინ როდესაც ცმდ-დან ვასრულებთ შესაბამის ბრძანებას
    // php artisan clear_log_file
    {
        exec("echo '' > " . storage_path("logs/laravel.log"));
        $this->info("Log have been cleared successfully!");
    }
}
