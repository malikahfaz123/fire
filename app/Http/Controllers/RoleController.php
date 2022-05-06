<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::select(DB::raw('COUNT(id) as count'))->first();
        return view('role.index')->with('title','Roles & Permissions')->with('roles',$roles);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = Helper::filter('roles',$request->all(),null,['guard_name','created_at','updated_at']);
        if($query){
            $roles = $query->orderBy('created_at','DESC')->paginate($per_page)->appends(request()->query());
        }else{
            $roles = Role::orderBy('created_at','DESC')->paginate($per_page)->appends(request()->query());
        }
        return view('role.paginate')->with('roles',$roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = DB::table('permissions')->where('name','LIKE','%s.create')->get();
        return view('role.create')->with('title','Add Role')->with('permissions',$permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'     =>  'required|min:4|alpha|unique:roles',
        ];

        $this->validate($request,$rules);
        $input = $request->except('_token');

        $role = new Role();
        $role->name = strtolower($input['name']);
        $role->guard_name = 'web';
        if(!$role->save())
            return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again.']);

        unset($input['name']);
        foreach ($input as $module=>$array){
            foreach ($array as $operation=>$bool){
                $role->givePermissionTo("{$module}.{$operation}");
            }
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findById($id);
        if(isset($role->id) && $role->id){
            $permissions = DB::table('permissions')->where('name','LIKE','%s.create')->get();
            return view('role.show')->with('title','View Role')->with('permissions',$permissions)->with('role',$role);
        }
        return view('404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($id == config('constant.system_role_id'))
            return view('404');

        $role = Role::findById($id);
        if(isset($role->id) && $role->id){
            $permissions = DB::table('permissions')->where('name','LIKE','%s.create')->get();
            return view('role.edit')->with('title','Edit Role')->with('permissions',$permissions)->with('role',$role);
        }
        return view('404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        if(isset($role->id) && $role->id){

            if($id == config('constant.system_role_id'))
                return response()->json(['status'=>false,'msg'=>'Cannot edit system role.']);

            $rules = [
                'name'     =>  'required|min:4|alpha|unique:roles,name,'.$id,
            ];

            $this->validate($request,$rules);
            $input = $request->except('_token','_method');
            $role->name = strtolower($input['name']);
            if(!$role->save())
                return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again.']);

            $permissionNames = $role->getPermissionNames();
            foreach ($permissionNames as $permissionName){
                $role->revokePermissionTo($permissionName);
            }

            unset($input['name']);
            foreach ($input as $module=>$array){
                foreach ($array as $operation=>$bool){
                    $role->givePermissionTo("{$module}.{$operation}");
                }
            }

            return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
        }
        return response()->json(['status'=>false,'msg'=>'Invalid Request !']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if(isset($role->id) && $role->id){
            if($id == config('constant.system_role_id'))
                return response()->json(['status'=>false,'msg'=>'Cannot delete system role.']);

            try{
                $response = Role::where('id',$id)->delete();
            }catch (\Exception $error){
                return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
            }

            if($response){
                $permissions = DB::table('permissions')->get();
                foreach($permissions as $key=>$permission){
                    $role->revokePermissionTo($permission->name);
                }
                return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
            }
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);

        }else{
            return view('404');
        }
    }

    public function get_unique_role($role,$except_id = null){
        $unique = false;
        $count = 0;
        while ($unique === false){
            if($count){
                $query = Role::select('id')->where('name',$role.'-'.$count);
            }else{
                $query = Role::select('id')->where('name',$role);
            }
            if($except_id){
                $db_role = $query->where('id','!=',$except_id)->limit(1)->first();
            }else{
                $db_role = $query->limit(1)->first();
            }

            if($db_role && isset($db_role->id)){
                $count++;
            }else{
                $unique = true;
                $role = $count ? $role.'-'.$count : $role;
            }
        }
        return $role;
    }

    public function clone(Request $request){

        $rules = [
            'confirm'     =>  'required|numeric',
        ];

        $this->validate($request,$rules);

        $role = Role::findById($request->confirm);
        if(isset($role->id) && $role->id){

            $clone = new Role();
            $clone->name = $this->get_unique_role($role->name);
            $clone->guard_name = 'web';
            if(!$clone->save()){
                return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again.']);
            }
            $permissionNames = $role->getPermissionNames();
            foreach ($permissionNames as $permissionName){
                $clone->givePermissionTo($permissionName);
            }
            return response()->json(['status'=>true,'msg'=>'Cloned Successfully !']);
        }
        return response()->json(['status'=>false,'msg'=>'Invalid request.']);
    }


}
