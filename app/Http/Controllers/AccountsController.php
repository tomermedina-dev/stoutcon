<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UserDataValidation;
use App\Http\Requests\UserUpdateDataValidation;
use App\Http\Requests\UpdateUsersRequest;
use DataTables;
use App\User;
use DB;
use Auth;
use Validator;
use Hash;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin')) {
            return abort(401);
        }

 
        $data = DB::table('users')->select(DB::raw('*, FORMAT(salary_amount,2) as salary_amount'))->latest()->get();


        if($request->ajax()){

            return DataTables::of($data)
                 ->addColumn('status', function($data) {

                      switch ($data->status) {
                            case 0:
                                return 'Resigned';
                            break;
                            case 1:
                                return 'Active';
                            break;
                            case 2:
                                return 'End of Contract';
                            break;
                            case 3:
                                return 'Terminated';
                            break;

                          default:
                              # code...
                              break;
                      }
                       
                    })
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-info btn-xs view" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/show').'" ><i class="fas fa-eye"></i> View </a>';
                      $button .= '<a class="btn btn-warning btn-xs edit" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/edit').'" ><i class="fas fa-pencil-alt"></i> Edit </a>';
                      $button .= '<a class="btn btn-danger btn-xs delete" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/delete').'" ><i class="fas fa-trash"></i> Delete </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserDataValidation $request)
    {
        if (!Gate::allows('admin')) {
            return abort(401);
        }


       $user = User::create($request->toArray());

        $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $user->name.' new case succesfully added!',
            'data' => $user,
        ];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $data = User::find($id);
        $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $data->name.' succesfully pulled data!',
            'data' => $data,
        ];
        return response($response, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        if (!Gate::allows('admin')) {
            return abort(401);
        }


        $data = User::find($id);
        $response = [
            'success' => true,
            'intended' => url('admin/accounts/'.$data->id),
            'page' => '_self',
            'message' => $data->name.' succesfully pulled data!',
            'data' => $data,
        ];
        return response($response, 200);
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
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $user = User::findOrFail($id);
        $user->update($request->all());
        $response = [
            'success' => true,
            'intended' =>  false,
            'page' => '_self',
            'message' => $user->name.' succesfully updated!',
            'data' => $user,
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $msg = $user->name." has been successfully deleted!";
        $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $msg,
            'data' => $user
        ];
        return response($response, 200);
    }


    public function settings(){

        $user = User::findOrFail(Auth::id());
        return view('profile.index', compact('user'));

    }

    public function change_password(){

        $user = User::findOrFail(Auth::id());
        return view('change_password.index', compact('user'));
    }


 public function profile_update(UpdateUsersRequest $request, $id)
    {
       
        if(!$request->role_id){
            $request['role_id']=2;
        }
        $user = User::findOrFail($id);
        $user->update($request->all());
        $msg = $request->name." your profile has been updated!";
    

         $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $msg,
            'data' => $user,
    
        ];
        return response($response, 200);

    }


   public function password_update(Request $request, $id)
    {
       
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|same:new_password|different:password',
        ]);

         if ($validator->fails()) {

             $msg = "Invalid data please check before you submit!";
            $response = [
            
                'message' => $msg,
                'errors' => $validator->errors(),
            ];
            return response($response, 422);

        }


        if (Hash::check($request->password, $user->password)) { 
            $user->fill([
            'password' => Hash::make($request->new_password)
        ])->save();


            $msg = $request->name." your password has been updated!";
            

              $response = [
                'success' => true,
                'intended' => false,
                'page' => '_self',
                'message' => $msg,
                'data' => $user,
               
            ];
            return response($response, 200);

        } else {
            

            $msg = "The old password you have have entred is incorrect!";
            $response = [
            
                'message' => $msg,
                'errors' => ['password' => ['The old password you have have entred is incorrect!']],
            ];
            return response($response, 422);

        }

    

    }



}
