<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EmployeeController;

class Employee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'empdata {method} {emp_id} {emp_name} {ip_address}';
    protected $signature = 'empdata {method} {ip_address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform CRUD operation on employees';

    private $inputData = [];

    private $employee;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmployeeController $employee)
    {
        parent::__construct();
        $this->employee = $employee;
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
                $this->inputData['emp_id'] = $this->argument('emp_id');
                $this->inputData['emp_name'] = $this->argument('emp_name');
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $result = $this->employee->createEmployee($this->inputData);
                print_r($result);
                break;
            case 'GET':
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $result = $this->employee->getEmployee($this->inputData['ip_address']);
                print_r($result);
                break;
            case 'UNSET':
                $this->inputData['ip_address'] = $this->argument('ip_address');
                $result = $this->employee->deleteEmployee($this->inputData['ip_address']);
                $this->info($result);
                break;
            default:
                $this->error('Request method not found');
        }
    }
}
