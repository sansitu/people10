<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeWebHistoryModel extends Model
{
    protected $fillable = ['ip_address', 'url', 'date'];
	
	protected $table = 'employee_web_history';

	/**
     * Used to create new employee
     *
     * @param array $data
     * @return string
     */
	public function createEmployeeWebHistory($data)
    {
		$webHistoryId = DB::table('employee_web_history')->insertGetId($data);

        return TRUE;
	}
	
	/**
     * Used to get employee information based on ip address
     *
     * @param string $ipAddress
     * @return mixed
     */
    public function getEmployeeWebHistory($ipAddress)
    {
        $data = DB::table('employee_web_history')
                     ->select(DB::raw('ip_address, GROUP_CONCAT(url) as urls'))
                     ->where('ip_address', $ipAddress)
                     ->groupBy('ip_address')
                     ->get()->toArray();

        if (count($data) > 0) {
            return $data;
        } else {
            return FALSE;
        }
    }

	/**
     * Used to find the employee web history and then delete it using ip address
     *
     * @param string $ipAddress
     * @return string
     */
    public function deleteEmployeeWebHistory($ipAddress)
    {
        if (DB::table('employee_web_history')->where('ip_address', $ipAddress)->delete()) {
        	return TRUE;
        } else {
        	return FALSE;
        }
    }
}
