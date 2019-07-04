<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\EmployeeModel;
use App\EmployeeWebHistoryModel;
use Carbon\Carbon;

class CommonAPIController extends Controller
{
    private $employeeModel;

    private $employeeWebHistoryModel;

    public function __construct(EmployeeModel $employeeModel, EmployeeWebHistoryModel $employeeWebHistoryModel) {
        $this->employeeModel = $employeeModel;

        $this->employeeWebHistoryModel = $employeeWebHistoryModel;
    }

    /**
     * This function used to take input and perform the task
     *
     * @param string $inputData
     * @return json
     */
    public function commonAPICall($inputData)
    {
    	$methods = ['SET', 'GET', 'UNSET'];

    	$entities = ['empdata', 'empwebhistory'];

    	$data = explode(' ', $inputData);
    	$data = array_values(array_filter($data)); 

    	if (!in_array(trim($data[0]), $methods) || !in_array(trim($data[1]), $entities)) {
    		print_r("Can not process the request");
    		exit;
    	}

    	switch(trim($data[0])) {
            case 'SET':
            	if (trim($data[1]) == 'empdata') {
            		$this->setEmployee($data);
            	} else {
            		$webHistoryData['ip_address'] = trim($data[2]);
            		$webHistoryData['url'] = trim($data[3]);
                	$this->setEmployeeWebHistory($webHistoryData);
            	}
                break;
            case 'GET':
            	if (trim($data[1]) == 'empdata') {
            		$this->getEmployee(trim($data[2]));
            	} else {
                	$this->getEmployeeWebHistory(trim($data[2]));
            	}
                break;
            case 'UNSET':
                if (trim($data[1]) == 'empdata') {
            		$this->deleteEmployee(trim($data[2]));
            	} else {
                	$this->deleteEmployeeWebHistory(trim($data[2]));
            	}
                break;
            default:
                $this->error('Request method not found');
        }
    }

    /**
     * This function used to create or set employee
     *
     * @param array $data
     * @return json
     */
    private function setEmployee($data)
    {
    	$validator = Validator::make($data, [
						'emp_id' => 'required|unique:employee',
						'emp_name' => 'required',
			        	'ip_address' => 'required|ip|unique:employee'
			    	]);

    	if ($validator->fails()) {
    		$this->sendResponse($validator->messages()->toJson(), 400);
    		exit;
    	}

		$this->sendResponse($this->employeeModel->createEmployee($data), 200);
    }

    /**
     * This function used to create or set employee web history
     *
     * @param array $data
     * @return json
     */
    private function setEmployeeWebHistory($data)
    {
    	$validator = Validator::make($data, [
			'ip_address' => 'required|ip',
			'url' => 'required|url'
    	]);

    	if ($validator->fails()) {
    		$this->sendResponse($validator->messages()->toJson(), 400);
    		exit;
    	}

    	if (!$this->employeeModel->isEmployeeExist($data['ip_address'])) {
    		$this->sendResponse("Sorry! you can add the history for this ip address", 400);
    		exit;
    	}

    	$data['date'] = Carbon::now()->toDateString();
		
		$this->sendResponse($this->employeeWebHistoryModel->createEmployeeWebHistory($data), 200);
    }

    /**
     * This function used to get employee information based on ip address
     *
     * @param string $ipAddress
     * @return json
     */
    private function getEmployee($ipAddress)
    {
        $result = $this->employeeModel->getEmployee($ipAddress);
        
        if ($result) {
        	$this->sendResponse($result, 200);
        } else {
        	$this->sendResponse("Data not found", 400);
        }
    }

    /**
     * This function used to get employee web history information based on ip address
     *
     * @param string $ipAddress
     * @return json
     */
    private function getEmployeeWebHistory($ipAddress)
    {
        $result = $this->employeeWebHistoryModel->getEmployeeWebHistory($ipAddress);
        
        if ($result) {
        	$this->sendResponse($result, 200);
        } else {
        	$this->sendResponse("Data not found", 400);
        }
    }

    /**
     * This function used to delete employee information based on ip address
     *
     * @param string $ipAddress
     * @return json
     */
    private function deleteEmployee($ipAddress)
    {
    	$result = $this->employeeModel->deleteEmployee($ipAddress);
        
        if ($result) {
        	$this->sendResponse("Data has been deleted successfully", 200);
        } else {
        	$this->sendResponse("Data not found", 400);
        }
    }

    /**
     * This function used to delete employee web history information based on ip address
     *
     * @param string $ipAddress
     * @return json
     */
    private function deleteEmployeeWebHistory($ipAddress)
    {
        $result = $this->employeeWebHistoryModel->deleteEmployeeWebHistory($ipAddress);
        
        if ($result) {
        	$this->sendResponse("Data has been deleted successfully", 200);
        } else {
        	$this->sendResponse("Data not found", 400);
        }
    }

    /**
     * This function used to send response
     *
     * @param mixed $data
     * @param int $httpCode
     * @return json
     */
    private function sendResponse($data, $httpCode)
    {
       print_r((new Response($data, $httpCode))->header('Content-Type', 'application/json'));
    }
}
