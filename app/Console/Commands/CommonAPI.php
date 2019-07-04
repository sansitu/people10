<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CommonAPIController;

class CommonAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'common:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute API command';

    private $commonAPIController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CommonAPIController $commonAPIController)
    {
        parent::__construct();
        $this->commonAPIController = $commonAPIController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->ask('Enter the API command');

        $this->commonAPIController->commonAPICall($data);
    }
}
