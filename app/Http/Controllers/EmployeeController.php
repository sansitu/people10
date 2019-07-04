<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EmployeeModel;

class EmployeeController extends Controller
{
	private $employee;
	
	public function __construct(EmployeeModel $employee) {
        $this->employee = $employee;
    }
	
    public function createEmployee($data)
	{
		$validator = Validator::make($data, [
			'emp_id' => 'required|unique:employee',
			'emp_name' => 'required',
        	'ip_address' => 'required|ip|unique:employee'
    	]);

    	if ($validator->fails()) {
    		return $validator->messages()->toJson();
    	}
		
		return $this->employee->createEmployee($data);
	}

	/**
     * Used to get all the team info
     *
     * @return array
     */
    public function getEmployee($ipAddress)
    {
        return $this->employee->getEmployee($ipAddress);
    }

    /**
     * Used to get all the team info
     *
     * @return array
     */
    public function deleteEmployee($ipAddress)
    {
        return $this->employee->deleteEmployee($ipAddress);
    }
}
