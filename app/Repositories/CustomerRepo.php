<?php namespace App\Repositories;

use App\Customer;
use App\Helpers\EloquentHelper;
use DB;

class CustomerRepo
{
	public function getBy($params = array())
	{
		$query = DB::table('customers');

        $query->select(array(
            'customers.*',
        ));

        $EloquentHelper = new EloquentHelper();
        return $EloquentHelper->allInOne($query, $params);
	}
	
	
	public function find($id)
	{
		return Customer::find($id);
	}

	/**
	 * Create a new customer instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return customer
	 */
	public function create(array $data)
	{
		$customer = Customer::create($data);
		return $customer;
	}

	/**
	 * Create a new customer instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return customer
	 */
	public function update(array $data,$id)
	{
		$customer = Customer::find($id)->update($data);
		return $customer;
	}


	public function delete(int $id)
	{
		return Customer::destroy($id);
	}
}