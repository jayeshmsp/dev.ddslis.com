<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use App\Apilog;
use App\Helpers\EloquentHelper;

class ApiLogController extends Controller
{

    protected $Apilog;
    public function __construct(Apilog $Apilog)
    {
        $this->Apilog = $Apilog;
        \View::share('module_name', 'Api Log');
    }

    public function index(Request $request)
    {
        $EloquentHelper = new EloquentHelper();
        
        $query = $this->Apilog->orderBy('id','DESC');
        $params['paginate'] = TRUE;
        
        $items = $EloquentHelper->allInOne($query, $params);

        $srno = ($request->input('page', 1) - 1) * config("setup.par_page", 10)  + 1;
        return view('admin.api_logs.index')
                ->with('srno',$srno)
                ->with('items',$items);
    }

    public function view(Request $request)
    {
        $EloquentHelper = new EloquentHelper();
        
        $query = $this->Apilog->orderBy('id','DESC');
        
        $items = $EloquentHelper->allInOne($query, []);

        return view('admin.api_logs.view')
                ->with('items',$items);   
    }
}