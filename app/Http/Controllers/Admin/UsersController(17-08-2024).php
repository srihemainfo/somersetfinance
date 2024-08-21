<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\TeachingStaff;
use App\Models\TeachingType;
use App\Models\User;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = DB::table('users')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('teaching_types', 'teaching_types.id', '=', 'roles.type_id')
                ->select('users.id', 'users.name', 'users.email', 'roles.title', 'teaching_types.name as teach_type')
                ->WhereNull('users.deleted_at')
                ->get();

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_show';
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';
                $crudRoutePart = 'users';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
          
            $table->editColumn('roles', function ($row) {
                return $row->title ? sprintf('<span class="roleLabel">%s</span>', $row->title) : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'roles']);
            return $table->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');
        $working_as = Role::pluck('title', 'id');
        $TeachingType = TeachingType::pluck('name', 'id');


        return view('admin.users.create', compact('roles',  'working_as', 'TeachingType'));
    }

    
    public function store(Request $request)
    {

            $count = User::where('email',$request->email)->count();

            if($count > 0){
                return response()->json(['status' => false, 'data' => 'Mail Already registered']);

            }

            $user = new User();
            $user->name = $request->fname;
            $user->password = $request->password;
            $user->email = $request->email;
            
            if($request->role == '2'){
                 $user->phone = $request->phone;
                 $user->company_address_1 = $request->company_address_1;
                  $user->company_address_2= $request->company_address_2;
                $user->company_phone= $request->company_phone;
                    
                 if ($request->hasFile('file_path')) {
                    $image = $request->file('file_path');
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->move(public_path('partner_images'), $imageName);
                     $user->file_path= $imageName ;
                } 
                
                
            }
            
            
            $user->save();
            $user->roles()->sync([$request->role]);

            if ($user->id != '') {
                return response()->json(['status' => true, 'data' => 'User created successfully']);
            } else {
                return response()->json(['status' => false, 'data' => 'User creation failed']);
            }

    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('title', 'id');
        $user->load('roles');
        $role_type = TeachingType::pluck('name', 'id');
        // dd( $role_type);
        return view('admin.users.edit', compact('role_type', 'user', 'roles'));
    }

     public function update(UpdateUserRequest $request, User $user )
    {
        

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6|max:8',
            'role' => 'required|integer',
            'phone' => 'nullable|required_if:role,2|string|max:15',
            'company_address_1' => 'nullable|required_if:role,2|string|max:255',
            'company_address_2' => 'nullable|string|max:255',
            'company_phone' => 'nullable|required_if:role,2|string|max:15',
            'file_path' => 'nullable|file|mimes:png,jpg,jpeg|max:2048'
        ]);
    
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->email = $request->email;
    
        if ($request->role == '2') {
            $user->phone = $request->phone;
            $user->company_address_1 = $request->company_address_1;
            $user->company_address_2 = $request->company_address_2;
            $user->company_phone = $request->company_phone;
    
            if ($request->hasFile('file_path')) {
                $oldFilePath = $user->file_path;
                $image = $request->file('file_path');
                
                if($oldFilePath != null){
                $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/partner_images');
                $newFilePath = $imageName;
                $image->move($destinationPath, $imageName);
                $user->file_path = $imageName;
                    
                }else{
                    
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->move(public_path('partner_images'), $imageName);
                    $user->file_path= $imageName;
                    
                }
    
            }
        } else {
            // If the role is not 2, ensure these fields are null
            $user->phone = null;
            $user->company_address_1 = null;
            $user->company_address_2 = null;
            $user->company_phone = null;
            $user->file_path = null;
        }
    
        $user->save();
        $user->roles()->sync([$request->role]);

        if ($user->exists) {
            return response()->json(['status' => true, 'data' => 'User updated successfully']);
        } else {
            return response()->json(['status' => false, 'data' => 'User update failed']);
        }

    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'userUserAlerts');
        
        
    
        return view('admin.users.staff', compact('user'));
        // return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $user->delete();
        
        if ($user) {
        
            if($user->applications()->exists()){
                
                   return back()->with('message_error', 'This Partner are used in case list');
            }
                
            $user->delete();
            
             return back()->with('message', 'Partner Successfully Deleted');
           
        } else {
             return back()->with('message_error' , ' Partner not found');

        }
        
       
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
     
            $ids = request('ids');
            $users = User::whereIn('id', $ids)->get();
            $status = false;
        
            foreach ($users as $user) {
                if (!$user->applications()->exists()) {
                    $user->delete();
                    $status = true;
                }
            }
        
            if ($status) {
                return response()->json(['status' => 'success', 'data' => 'Partner(s) Successfully Deleted']);
            } else {
                return response()->json(['status' => 'error', 'data' => 'This Partner\'s are used in case list']);
            }

    }

    public function block(Request $request)
    {

        $role_type = TeachingType::pluck('name', 'id');

        return view('admin.users.block', compact('role_type'));
    }
    public function fetchRoles(Request $request)
    {
        $roles = Role::where(['type_id' => $request->role_type])->select('id', 'title')->get();
        return response()->json(['roles' => $roles]);
    }
    public function fetchUsers(Request $request)
    {
        $users = [];
        $user = DB::table('role_user')->where(['role_id' => $request->role_user])->select('user_id')->get();
        // $userId =$user->toArray();
        foreach ($user as $u) {

            $getUser = User::where('id', $u->user_id)->select('name', 'id')->get();

            foreach ($getUser as $us) {
                array_push($users, ['id' => $us->id, 'name' => $us->name]);
            }
        }

        return response()->json([$users]);
    }
    public function unblock(Request $request)
    {
        $type_id = auth()->user()->roles[0]->type_id;
        $users = User::pluck('name', 'id');
        $got_users = User::get();
        $roles = Role::pluck('title', 'id');
        $got_roles = [];
        foreach ($roles as $role_id => $role) {
            // dd($users);
            $got_roles[$role_id] = [];
            $get_user = DB::table('role_user')->where(['role_id' => $role_id])->get();
            foreach ($get_user as $user) {
                foreach ($got_users as $data) {
                    // dd($data);
                    if ($user->user_id == $data->id) {
                        if ($role_id == 5) {
                            array_push($got_roles[$role_id], [$data->id => $data->name . ' ( ' . $data->roll_no . ' ) ']);
                        } elseif ($type_id == 1 || $type_id == 3) {
                            array_push($got_roles[$role_id], [$data->id => $data->name . ' ( ' . $data->employID . ' ) ']);
                        } else {
                            array_push($got_roles[$role_id], [$data->id => $data->name]);
                        }
                    }
                }
            }

        }

        return view('admin.users.unblock', compact('users', 'roles', 'got_roles'));
    }

    public function block_user(Request $request)
    {
        // dd($request);
        if ($request->user != '') {
            $user = User::where(['id' => $request->user])->update([
                'access' => 1,
                'block_reason' => $request->block_reason,
            ]);
        }
        return redirect()->route('admin.users.index')->with('error', 'User Blocked Successfully...');
    }

    public function unblock_user(Request $request)
    {

        if ($request->user != '') {
            $user = User::where(['id' => $request->user])->update([
                'access' => 0,
            ]);
        }
        return redirect()->route('admin.users.index')->with('message', 'User Unblocked Successfully...');
    }

    public function block_list()
    {
        $user = User::where(['access' => 1])->get();

        return view('admin.users.blockList', compact('user'));
    }
    public function fetch_role(Request $request)
    {

        $role = Role::where('type_id', $request->type)->pluck('title', 'id');
        return response()->json($role);
    }
    // public function profileUpdate(Request $request){
        
    //     $id = Auth::user()->id;
        
    //     $user = User::find($id);
        
    //     $request->validate([
    //         'profile_name' => 'required|string',
    //         'profile_phone' => 'required|string',
    //         'profile_address1' => 'required|string',
    //         'profile_file_path' => 'required|file|mimes:png,jpg,jpeg|max:2048'
    //     ]);
        
    //      if ($request->hasFile('file_path')) {
    //             $oldFilePath = $user->file_path;
    //             $image = $request->file('file_path');
               
                
    //             if($oldFilePath != null){
                    
    //             $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
    //             $destinationPath = public_path('/partner_images');
    //             $newFilePath = $imageName;
    //             $image->move($destinationPath, $imageName);
    //             $user->file_path = $imageName;
                
               
                    
    //             }else{
                    
    //                 $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
    //                 $imagePath = $image->move(public_path('partner_images'), $imageName);
    //                 $user->file_path= $imageName;
                    
    //             }
                
                
    //         }
            
    //         $user->save();
    //         if ($user->exists) {
    //             return response()->json(['status' => true, 'data' =>$user->file_path ]);
    //         } else {
    //             return response()->json(['status' => false, 'data' => 'User update failed']);
    //         }
        
        
    // }
    
    public function profileCurrentData(){
        
        $id = Auth::user()->id ??'';
        
        if(!$id){
            return response()->json(['status' => false, 'data' => 'User Not found']);
        }
        
        $user = User::where('id',$id)->select('name','phone','company_address_1','company_address_2','company_phone')->first();
        
        if(!$user){
            return response()->json(['status' => false, 'data' => 'User Not found']);
        }
        
         return response()->json(['status' => true, 'data' => $user ]);
        
    }
    
    public function profileUpdate(Request $request) {
            $id = Auth::user()->id;
            $user = User::find($id);
        
            $request->validate([
                'profile_name' => 'required|string',
                'profile_phone' => 'required|string',
                'profile_company_address_1' => 'required|string',
                'profile_file_path' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
                'password' => 'nullable|string|min:6|confirmed',
                'password_confirmation' => 'nullable|string|min:6'
            ]);
        
            // Handle file upload
            if ($request->hasFile('profile_file_path')) {
                $oldFilePath = $user->file_path;
                $image = $request->file('profile_file_path');
                
                if ($oldFilePath != null) {
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/partner_images');
                    $image->move($destinationPath, $imageName);
                    $user->file_path = $imageName;
                } else {
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('partner_images'), $imageName);
                    $user->file_path = $imageName;
                }
            }
        
            // Update user profile details
            $user->name = $request->input('profile_name');
            $user->phone = $request->input('profile_phone');
            $user->company_address_1 = $request->input('profile_company_address_1');
            $user->company_address_2 = $request->input('profile_company_address_2');
             $user->company_phone = $request->input('profile_company_phone');
        
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
        
            $user->save();
            $updatedUser = User::where('id',$id)->select('name','phone','company_address_1','company_address_2','company_address_1','company_phone','file_path')->first();
            
            if ($user->exists) {
                return response()->json(['status' => true, 'data' => $updatedUser ]);
            } else {
                return response()->json(['status' => false, 'data' => 'User update failed']);
            }
        }


    
    
}
