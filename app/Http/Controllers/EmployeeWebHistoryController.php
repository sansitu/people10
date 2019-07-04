<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EmployeeWebHistoryModel;
use App\EmployeeModel;
use Carbon\Carbon;

class EmployeeWebHistoryController extends Controller
{
    private $employeeWebHistoryModel;

    private $employeeModel;
	
	public function __construct(EmployeeWebHistoryModel $employeeWebHistoryModel, EmployeeModel $employeeModel) {
        $this->employeeWebHistoryModel = $employeeWebHistoryModel;

        $this->employeeModel = $employeeModel;
    }
	
    public function createEmployeeWebHistory($data)
	{
		$validator = Validator::make($data, [
			'ip_address' => 'required|ip',
			'url' => 'required|url'
    	]);

    	if ($validator->fails()) {
    		return $validator->messages()->toJson();
    	}

    	if (!$this->employeeModel->isEmployeeExist($data['ip_address'])) {
    		return "Sorry! you can add the history for this ip address";
    	}

    	$data['date'] = Carbon::now()->toDateString();
		
		return $this->employeeWebHistoryModel->createEmployeeWebHistory($data);
	}

	/**
     * Used to get all the team info
     *
     * @return array
     */
    public function getEmployeeWebHistory($ipAddress)
    {
        return $this->employeeWebHistoryModel->getEmployeeWebHistory($ipAddress);
    }

    /**
     * Used to get all the team info
     *
     * @return array
     */
    public function deleteEmployeeWebHistory($ipAddress)
    {
        return $this->employeeWebHistoryModel->deleteEmployeeWebHistory($ipAddress);
    }
}
