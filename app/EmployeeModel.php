<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeModel extends Model
{
    protected $fillable = ['emp_id', 'emp_name', 'ip_address', 'is_deleted'];
	
	protected $table = 'employee';

	const EMPLOYEE_FETCHABLE_COLUMNS = ['emp_id', 'emp_name', 'ip_address'];
	
	/**
     * Used to create new employee
     *
     * @param array $data
     * @return string
     */
	public function createEmployee($data)
    {
		$employeeId = DB::table('employee')->insertGetId($data);

        return TRUE;
	}
	
	/**
     * Used to get employee information based on ip address
     *
     * @param string $ipAddress
     * @return mixed
     */
    public function getEmployee($ipAddress)
    {
        $data = DB::table('employee')->where(['ip_address' => $ipAddress, 'is_deleted' => 0])->get(self::EMPLOYEE_FETCHABLE_COLUMNS)->toArray();

        if (count($data) > 0) {
            return $data;
        } else {
            return FALSE;
        }
    }

	/**
     * Used to find the employee and then update the delete it using ip address
     *
     * @param string $ipAddress
     * @return string
     */
    public function deleteEmployee($ipAddress)
    {
        if (DB::table('employee')->where('ip_address', '=', $ipAddress)->update(['is_deleted' => 1])) {
        	return TRUE;
        } else {
        	return FALSE;
        }
    }

    /**
     * Used to find the ip address exist or not
     *
     * @param string $ipAddress
     * @return boolean
     */
    public function isEmployeeExist($ipAddress)
    {
        $output = DB::table('employee')->where('ip_address', '=', $ipAddress)->get()->toArray();
       
        if (count($output) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
