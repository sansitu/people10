<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EmployeeWebHistoryController;

class EmployeeWebHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'empwebhistory {method} {ip_address} {url}';
    protected $signature = 'empwebhistory {method} {ip_address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $inputData = [];

    private $employeeWebHistory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmployeeWebHistoryController $employeeWebHistory)
    {
        parent::__construct();
        $this->employeeWebHistory = $employeeWebHistory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch($this->argument('method')) {
            case 'SET':
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $this->inputData['url'] = $this->argument('url');                
                $result = $this->employeeWebHistory->createEmployeeWebHistory($this->inputData);
                print_r($result);
                break;
            case 'GET':
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $result = $this->employeeWebHistory->getEmployeeWebHistory($this->inputData['ip_address']);
                print_r($result);
                break;
            case 'UNSET':
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $result = $this->employeeWebHistory->deleteEmployeeWebHistory($this->inputData['ip_address']);
                $this->info($result);
                break;
            default:
                $this->error('Request method not found');
        }
    }
}
