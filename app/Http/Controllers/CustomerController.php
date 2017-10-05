<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CustomerRepo;
use Illuminate\Support\Facades\Validator;
use View;
use Auth;
use Response;
use App\Customer;
use DB;

class CustomerController extends Controller
{
    private $view_path;
    protected $CustomerRepo;

    public function __construct(Request $request,CustomerRepo $CustomerRepo)
    {
    	$this->middleware('auth');
        $this->CustomerRepo = $CustomerRepo;
        $this->ctrl_url = 'customer';
        $this->view_path = 'admin.customer';
        $contact_phone_type = config('customer.contact_phone_type');
        $license_type = config('customer.license_type');
        $maximum_licensed_users = config('customer.maximum_licensed_users');
        
        View::share([
            'ctrl_url'=>$this->ctrl_url,
            'view_path'=>$this->view_path,
            'module_name'=> 'Company',
            'title'=>'Company',
            'contact_phone_type' => $contact_phone_type,
            'license_type' => $license_type,
            'maximum_licensed_users' => $maximum_licensed_users,
        ]);
    }

    // Method : index
	// Param : request
    // Output : return index view
    public function index(Request $request)
    {
    	$param['filter'] = $request->input("filter", array());
        $param['sort'] = $request->input("sort", array('created_at'=>'desc'));
        $param['paginate'] = TRUE;
        if($request->input('filter.name.value')){
            $param['filter']['name']['value'] = '%'.$request->input('filter.name.value').'%';
        }

        $items = $this->CustomerRepo->getBy($param);

        //serial number
        $srno = ($request->input('page', 1) - 1) * config("setup.par_page", 10)  + 1;

        $compact = compact('items','srno');

        return view($this->view_path . '.index',$compact)
                ->with('title', 'list');
    }

    public function create()
    {
        $customer_details = Customer::select('company_number')->orderBy('id','DESC')->first();
        return view($this->view_path . '.create')
                ->with('title', 'create')
                ->with('company_number', ($customer_details)?$customer_details->company_number:3000000);
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $data   = array_except($inputs, 'save', 'save_exit');

        $rules = [
            'company_name' => 'required',
            'company_address' => 'required',
            'company_secret_key' => 'required|max:255|unique:customers',
            'company_web_site' => 'url',
            'company_number' => 'required|unique:customers',
            'contact_email_address' => 'string|email|max:255',
            'billing_address_zip_code' => 'min:5|max:5',
            'license_start_date' => 'date',
            'license_end_date' => 'date|after:license_start_date'
        ];
        // Create a new validator instance from our validation rules
        $validator = Validator::make($inputs, $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return redirect($this->ctrl_url.'/create')
                ->withErrors($validator)
                ->withInput();
        }

        if($customer_id = $this->CustomerRepo->create($data)->getKey()){

            DB::table('api_token')->insert([
                'client_secret'=>$data['company_secret_key'],
                'user_id'=>\Auth::user()->id,
                'customer_id'=>$customer_id
            ]);

      		return redirect($this->ctrl_url)
                ->with('success', 'Record added sucessfully');
        }

        return redirect($this->ctrl_url)->with('error', 'Can not be created');
    }

    public function edit($id)
    {
    	$item = $this->CustomerRepo->find($id);
    	
        $compact = compact('item');
    	return view($this->view_path . '.update',$compact)
                ->with('title', 'edit');
    }

    public function update(Request $request,$id)
    {
    	$inputs = $request->except('_token','_method','password_confirmation');
        $data   = array_except($inputs,array('save','save_exit'));

         $rules = [
            'company_name' => 'required',
            'company_address' => 'required',
            'company_web_site' => 'url',
            'contact_email_address' => 'string|email|max:255',
            'billing_address_zip_code' => 'min:5|max:5',
            'license_start_date' => 'date',
            'license_end_date' => 'date|after:license_start_date'
        ];

        $rules['company_secret_key']="required|max:255|unique:customers,company_secret_key,".$id;
        $rules['company_number']="required|unique:customers,company_number,".$id;
        
        // Create a new validator instance from our validation rules
        $validator = Validator::make($inputs, $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return redirect($this->ctrl_url.'/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->CustomerRepo->update($data,$id)){

            DB::table('api_token')->where('customer_id','=',DB::raw('"'.$id.'"'))->update(['client_secret'=>$data['company_secret_key']]);

    		return redirect($this->ctrl_url)
            ->with('success', 'Record updated sucessfully');
        }

        return redirect($this->ctrl_url)->with('error', 'Can not be created');
    }

    public function destroy(Request $request,$id)
    {
        if(!empty($id)) {
            Customer::where("id",$id)->forceDelete();

            //DB::table('api_token')->where('customer_id','=',DB::raw('"'.$id.'"'))->delete();
        }
    	return redirect($this->ctrl_url)->with('success', 'Records is deleted');
    }

    public function createSecretKey()
    {
        echo  md5(microtime().rand());
    }
}
