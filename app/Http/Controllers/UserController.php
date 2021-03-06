<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepo;
use App\Repositories\SettingRepo;
use App\Repositories\RoleRepo;
use Illuminate\Support\Facades\Validator;
use View;
use Auth;
use Html;
use Form;
use Response;
use App\User;
use Datatables;
use Mail;
use Entrust;
use App\Mail\resetPassword;

class UserController extends Controller
{
    private $view_path;
    protected $UserRepo;
    protected $RoleRepo;
    protected $SettingRepo;
    protected $roles;

    public function __construct(Request $request,UserRepo $UserRepo,RoleRepo $RoleRepo,SettingRepo $SettingRepo)
    {
    	$this->middleware('auth');
        $this->UserRepo = $UserRepo;
        $this->RoleRepo = $RoleRepo;
        $this->SettingRepo = $SettingRepo;
        $roles = $this->RoleRepo->getBy();

        foreach($roles as $role ) {
            $this->roles[$role->id] = $role->display_name;
        }

        $this->view_path = 'users.user';
        View::share('module_name', 'Users');
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

        $items = $this->UserRepo->getBy($param);

        //serial number
        $srno = ($request->input('page', 1) - 1) * config("setup.par_page", 10)  + 1;

        $roles = $this->RoleRepo->getBy();
        foreach($roles as $role ) {
            $roles[$role->id] = $role->display_name;
        }

        $compact = compact('items','srno','roles');

        return view($this->view_path . '.index',$compact)
                ->with('title', 'list');
    }

    public function create()
    {
        $roles = $this->RoleRepo->lists('name','id');
        $compact = compact('roles');
        return view($this->view_path . '.create',$compact)
                ->with('title', 'create');
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $data   = array_except($inputs, 'save', 'save_exit','password_confirmation');

        $rules = [
            'first_name' => 'required|alpha_space|string|max:255',
            'last_name' => 'required|alpha_space|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|string|max:255|unique:users',
            'role_id' => 'required'
        ]; 
        // Create a new validator instance from our validation rules
        $validator = Validator::make($inputs, $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return redirect('user/create')
                ->withErrors($validator)
                ->withInput();
        }

        if($user = $this->UserRepo->create($data)){
      		return redirect('user')
                ->with('success', 'Record added sucessfully');
        }

        return redirect('user/')->with('error', 'Can not be created');
    }

    public function edit($id)
    {
    	$item = $this->UserRepo->find($id);
    	$selected_roles = $this->UserRepo->currentUserRole($id);
        $item = $item->toArray();
        $item['role_id'] = $selected_roles;
        $roles = $this->RoleRepo->lists('name','id');

        //unset($item['password']);

		$compact = compact('item','roles');
    	return view($this->view_path . '.update',$compact)
                ->with('title', 'edit');
    }

    public function update(Request $request,$id)
    {
    	$inputs = $request->except('_token','_method','password_confirmation');
        $data   = array_except($inputs,array('save','save_exit'));

         $rules = [
            'first_name' => 'required|string|alpha_space|max:255',
            'last_name' => 'required|string|alpha_space|max:255',
            //'password' => 'required|string|min:6|confirmed',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'role_id' => 'required'
        ];
        if(!$request->input('password')){
        	unset($rules['password']);
        }
        //$rules['email']="required|email|max:255|unique:users,email,".$id;
        $rules['email']="required|email|max:255";
        $rules['homeemail']="email|max:255";
        $rules['alternateemail']="email|max:255";
        $rules['workemail']="email|max:255";

        // Create a new validator instance from our validation rules
        $validator = Validator::make($inputs, $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return redirect('user/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->UserRepo->update($data,$id)){
    		return redirect('user')
            ->with('success', 'Record updated sucessfully');
        }

        return redirect('user/')->with('error', 'Can not be created');
    }

    public function profile()
    {
        $item = $this->UserRepo->find(Auth::user()->id);
        $interest = $this->SettingRepo->lists('interest');
        $skill = $this->SettingRepo->lists('skill');
        $state = $this->SettingRepo->lists('state');
        
        View::share('title','Profile');
        
        $compact = compact('item','interest','skill','state');
        return view($this->view_path . '.profile',$compact)
                ->with('title', 'profile');        
    }

    public function postProfile(Request $request)
    {
        $inputs = $request->except('_token','_method');
       
        $data   = array_except($inputs,array('save','save_exit','password_confirmation'));
        $id = Auth::user()->id;

         $rules = [
            'first_name' => 'required|string|alpha_space|max:255',
            'last_name' => 'required|string|alpha_space|max:255',
            'password' => 'sometimes|required|string|min:6|confirmed',
            /*'mobile_contact_num' => 'required_without_all:work_contact_num,home_contact_num',
            'work_contact_num' => 'required_without_all:mobile_contact_num,home_contact_num',*/
            'home_contact_num' => 'required',
            'dob' => 'required|date|date_format:Y-m-d',
            'email' => "required|email|max:255|unique:users,email,".$id,
            //'username' => "required|max:255|unique:users,username,".$id,
            'address' => "max:255",
            //'zipcode' => "max:5|min:5"
        ];
        if(!$request->input('password')){
            unset($rules['password']);
        }
        
        // Create a new validator instance from our validation rules
        $validator = Validator::make($inputs, $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return redirect('user/profile')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->UserRepo->updateProfile($data,$id)){
            return redirect('user/profile')
            ->with('success', 'Profile updated sucessfully');
        }

        return redirect('user/profile')->with('error', 'Can not be updated');
    }

    public function destroy(Request $request,$id)
    {
        if(!empty($id)) {
            User::where("id",$id)->forceDelete();
        }
    	return redirect('user')->with('success', 'Records is deleted');
    }
    
    /**
     * this function use for reset user password 
     */
    public function resetPassword(Request $request)
    {   
        if($request->ajax()) {
            $inputs = $request->all();    
            $rules = ['password'=> 'required'];
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return Response::json(["msg"=>"Validation error occur."],403);
            }
            $userData = User::findOrFail($inputs['user_id']);
            $userData->password = bcrypt($inputs['password']);
            $userData->save();
            $mail_msg = "Your password is changed by admin, new password is : ".$inputs['password'];
            $email = new resetPassword(['user' => $userData, 'newPass' => $inputs['password']]);
            Mail::to($userData->email)->send($email);
            
            return Response::json(["msg"=>"Password has been changed and mail sent to user.",200]);
        }
    }

    public function getDatas()
    {
        $param = [];
        if (Entrust::hasRole('company')) {
            $param['filter']['users.customer_id']['value'] = Auth::user()->is_customer;
            $param['filter']['users.customer_id']['oprator'] = '=';
        }
        
        $user = $this->UserRepo->getBy($param);
        
        return Datatables::of($user)
            //->editColumn('role_id',"{{isset($this->roles[$user->role_id])?$this->roles[$user->role_id]:''}}")
            ->addColumn('action', function ($user) {
                $form =  Html::decode(Form::open(["url" => url("user/$user->id"),"method"=>"delete"]));
                return $form.'<a href=user/'.$user->id.'/edit class="btn btn-small btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a data-userId="'.$user->id.'" href="javascript:void(0)" class="btn btn-small btn-primary reset-pass-modal"><span class="glyphicon glyphicon-lock"></span></a>
                            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                        </form>';
            })
            ->make(true);
    }
}
