<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CustomerRepo;
use Illuminate\Support\Facades\Validator;
use View;
use Auth;
use Response;
use App\Customer;
use App\Mail\CompanyToUser;
use Mail;
use App\User;
use DB;
use Datatables;
use Html;
use Form;
use App\Helpers\EloquentHelper;

class CustomerController extends Controller
{
    private $view_path;
    protected $CustomerRepo;
    protected $contact_phone_type;
    protected $license_type;
    protected $maximum_licensed_users;

    public function __construct(Request $request,CustomerRepo $CustomerRepo)
    {
    	$this->middleware('auth');
        $this->CustomerRepo = $CustomerRepo;
        $this->ctrl_url = 'customer';
        $this->view_path = 'admin.customer';
        $this->contact_phone_type = config('customer.contact_phone_type');
        $this->license_type = config('customer.license_type');
        $this->maximum_licensed_users = config('customer.maximum_licensed_users');
        
        View::share([
            'ctrl_url'=>$this->ctrl_url,
            'view_path'=>$this->view_path,
            'module_name'=> 'Company',
            'title'=>'Company',
            'contact_phone_type' => $this->contact_phone_type,
            'license_type' => $this->license_type,
            'maximum_licensed_users' => $this->maximum_licensed_users,
        ]);
    }

    // Method : index
	// Param : request
    // Output : return index view
    public function index(Request $request)
    {
        /*$param['filter'] = $request->input("filter", array());
        $param['sort'] = $request->input("sort", array('created_at'=>'desc'));
        $param['paginate'] = TRUE;
        if($request->input('filter.name.value')){
            $param['filter']['name']['value'] = '%'.$request->input('filter.name.value').'%';
        }

        $items = $this->CustomerRepo->getBy($param);

        //serial number
        $srno = ($request->input('page', 1) - 1) * config("setup.par_page", 10)  + 1;

        $compact = compact('items','srno');*/

        return view($this->view_path . '.index'/*,$compact*/)
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
            'contact_email_address' => 'string|email|max:255|unique:users,email',
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
            //'' => 'string|email|max:255',
            'billing_address_zip_code' => 'min:5|max:5',
            'license_start_date' => 'date',
            'license_end_date' => 'date|after:license_start_date'
        ];

        $rules['company_secret_key']="required|max:255|unique:customers,company_secret_key,".$id;
        $rules['contact_email_address']="string|email|max:255|unique:users,email,".$id;
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

    public function convertToUser($id='')
    {
        if (!empty($id)) {
            $user_exists=User::where('is_customer','=',DB::raw('"'.$id.'"'))->first();
            if ($user_exists) {
                $user = $user_exists;
                $user->email_token = str_random(10);

                User::where('is_customer','=',DB::raw('"'.$id.'"'))->update(['email_token'=>$user->email_token]);
            }else{
                $param['filter']['id']['value'] = $id;
                $param['filter']['id']['oprator'] = '=';
                $param['single'] = true;
                $company = $this->CustomerRepo->getBy($param);
                
                $user =  User::create([
                    'name' => $company->contact_first_name.' '.$company->contact_last_name,
                    'first_name' => $company->contact_first_name,
                    'last_name' => $company->contact_last_name,
                    'email' => $company->contact_email_address,
                    'is_customer' => $id,
                    'is_profile_updated' => '1',
                    'verified' => '1',
                    'platform' => config('app.name'),
                    'username' => $company->contact_email_address,
                    'password' => '',
                    'email_token' => str_random(10),
                ]);
                $user->attachRole('3');
            }

            if ($user) {
                
                $email = new CompanyToUser($user);
                Mail::to($user->email)->send($email);
                
                Customer::where('id','=',\DB::raw('"'.$id.'"'))->update(['is_converted_user'=>'1']);

                return back()->with('success','Company converted to user successfully, please ask company contact person for check mailbox');
            }

            return back()->with('error','Error : Company not convert to user');
        }
    }
    public function getDatas()
    {
        $customer = $this->CustomerRepo->getBy([]);
        
        return Datatables::of($customer)
            ->editColumn('contact_phone_type', function ($customer) {
                return $this->contact_phone_type[$customer->contact_phone_type]??'';
            })
            ->editColumn('maximum_licensed_users', function ($customer) {
                return $this->maximum_licensed_users[$customer->maximum_licensed_users]??'';
            })
            ->addColumn('company_url', function ($customer) {
                //return '<a target="_blank" href=https://taoe.ddslis.com/2VaYeJ1U?CompanyNumber='.base64_encode($customer->company_number).' >https://taoe.ddslis.com/2VaYeJ1U?CompanyNumber='.base64_encode($customer->company_number).'</a> <hr/> <a target="_blank" href=https://taoe.ddslis.com/2VaYeJ1U/register?CompanyNumber='.base64_encode($customer->company_number).' >https://taoe.ddslis.com/2VaYeJ1U/register?CompanyNumber='.base64_encode($customer->company_number).'</a>';
                return '<a target="_blank" href=https://taoe.ddslis.com/2VaYeJ1U?CompanyNumber='.encrypt($customer->company_number).' >https://taoe.ddslis.com/2VaYeJ1U?CompanyNumber='.encrypt($customer->company_number).'</a> <hr/> <a target="_blank" href=https://taoe.ddslis.com/2VaYeJ1U/register?CompanyNumber='.encrypt($customer->company_number).' >https://taoe.ddslis.com/2VaYeJ1U/register?CompanyNumber='.encrypt($customer->company_number).'</a>';
            })
            ->editColumn('license_type', function ($customer) {
                return $this->license_type[$customer->license_type]??'';
            })
            ->editColumn('license_valid', function ($customer) {
                return $customer->license_valid?'Valid':'Invalid';
            })
            ->addColumn('action', function ($customer) {
                
                $form =  Html::decode(Form::open(["url" => url("customer/$customer->id"),"method"=>"delete"]));
                
                if (empty($customer->is_converted_user))
                    $convert = '<a href=customer/'.$customer->id.'/convert-to-user class="btn btn-small btn-warning"><span class="glyphicon glyphicon-user"></span></a>';
                else
                    $convert = '<a href="#" class="btn btn-small btn-success"><span class="glyphicon glyphicon-user"></span></a>';
                
                return $form.'<a href=customer/'.$customer->id.'/edit class="btn btn-small btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                            '.$convert.'
                        </form>';
            })
            ->rawColumns(['company_url','action'])
            ->make(true);
    }
}
