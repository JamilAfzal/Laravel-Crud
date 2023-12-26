<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{
   
    public function createuser(Request $req){

        //validation 
        
        $validate = Validator::make($req->all(),[
            'name'=> 'required|string',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:8'
        ]);
        if ($validate->fails()){
            $result = array('status'=>'false', 'message' =>'Validation Went Wrong', 'error_message'=>$validate->errors() );
            return response()->json($result,400); 
            
        }


        //creating users
        $users = User::create([
            'name'=>$req->name,
            'email'=>$req->email,
            
            'password'=> bcrypt($req->password)
        ]);
        if($users->id){
            $result = array('status'=>'true','message'=>'User Has Been Created', 'data'=> $users);
            $responsecode=200;
        }
        else{
            $result = array('status'=>'false', 'message' =>'Something Went Wrong', 'data'=>$users);
            $responsecode=400;

        }
        return response()->json($result,$responsecode); 
    }
    public function showusers(){
        try{
        $users =User::all();
        $result = array('status'=>'true','message'=>'Showing All The Users', 'User Count'=> count($users), "User data"=> $users);
        $responsecode = 200;
        return response()->json($result, $responsecode);
        } catch(Exception $e){
            $result = array('status'=>'false','message'=>'Api has been failed due to some error', 'Error'=>$e->getMessage());
        }
    }
    public function userdetail($id){
        
        $user = User::find($id);
        if(!$user){
            return array(['status'=>'false','message'=>'There Is No User']);
        }
        $result = array('status'=>'true','message'=>'User Found', 'User-Details'=>$user);
        return response()->json($result);
    }
    public function updateuser(Request $req, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['status'=>'weird','message'=>'There is no User on your mentioned Id']);
        }
        $validate = Validator::make($req->all(),[
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            
        
        ]);
        if($validate->fails()){
            $result = array(['status'=>'false', 'message' =>'Validation Went Wrong', 'error_message'=>$validate->errors()]);
            return response()->json($result);
        }
        //upddate user
        $user->name = $req->name;
        $user->email = $req->email;
        $user->save();
        $result = array('status'=>'true', 'message' =>'User has been Updated', 'data'=>$user);
        return response()->json($result);
        

    }
    public function deleteuser(Request $req,$id){
        $users= User::find($id);
        if(!$users){
            return response()->json(['status'=>"False","Message"=>"There is no User on the mentioned ID"]);
        }
        $users->delete();
        $result = array('status'=>'true','message'=>"User has been deleted successfully");
        return response()->json($result);
    }
    public function createuserwithphone(Request $req)
{
    $validate = Validator::make($req->all(), [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users', 
        'phone' => 'required|min:10|numeric',
        'password' => 'required|min:10'
    ]);

    if ($validate->fails()) {
        $result = array(
            'message' => 'Validation has been failed due to some errors',
            'status' => 'Validation Error',
            'errors' => $validate->errors()
        );
        return response()->json($result, 400); // Return a 400 Bad Request status
    }

    $user = User::create([
        'name' => $req->name,
        'phone' => $req->phone,
        'email' => $req->email,
        'password' => bcrypt($req->password)
    ]);

    if ($user) {
        $result = array(
            'message' => 'User has been created',
            'status' => 'Success',
            'data' => $user
        );
        return response()->json($result, 201); // Return a 201 Created status
    } else {
        $result = array(
            'message' => 'User cannot be created due to some reasons',
            'status' => 'Error'
        );
        return response()->json($result, 500); // Return a 500 Internal Server Error status
    }
}
public function postusers(Request $request){
    $validator = Validator::make($request->all(),[
        'name' => 'required|string|min:7|unique:users,name',
        'email'=>'required|email|unique:users,email',
        'password'=> 'required|min:8'
    ]);
    if($validator->fails()){
        $result = array('status'=>'failed','message'=>'Validation Has Been Failed', 'Error'=>$validator->errors());
        return response()->json($result);
    };
    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>bcrypt($request->password)
    ]);
    if($user->id){
        $result = array('status'=>"Working","message"=>"User has been Created",'data'=>$user);
        
    }else($result = array('status'=>"not working","message"=>"Something went wrong",'data'=>$user));
    return response()->json($result);
}
public function allusers(){
    try{
        $users= User::all();
        $result = array('Status'=>'Correct','Message'=>'Showing all the users','User Count'=>count($users),'data'=>$users);
        return response()->json($result);
        
    }catch(Exception $e){
        $result= array('Status'=>'False','Message'=>'Api has been failed','Error'=>$e->getMessage());
    }
}
public function userrdetail($id){
    $user = User::find($id);
    if(!$user){
        return array(['Message'=>'There Is No User Available at your mentioned id']);
    }
    $result=array('Message'=>'User has been found', 'data' =>$user);
    $statuscode=400;
    return response()->json($result,$statuscode);
}
public function saveusers(Request $req,$id){
    $user=User::find($id);
    if(!$user){
        return array('Message'=>"There is no such User available ");
    }
    $validate = Validator::make($req->all(),[
        'name' => 'required|string|min:7|unique:users,name',
        'email'=>'required|email|unique:users,email',
        
    ]);
    if($validate->fails()){
        return response()->json(['Message'=>'Validation has been failed',"Error"=>$validate->errors()]);
    }
    $user->name = $req->name;
    $user->email = $req->email;
    $user->save();
    return response()->json(['Status'=>'Successful',"Message"=>"User has been updated","data"=>$user]);


}
public function postinguserdetails(Request $req){
    $validate = Validator::make($req->all(),[
     'name'=>'required|string|min:4',
     'email'=>'required|email|unique:users,email',
     'password'=>'required|min:8'

    ]);
    if($validate->fails()){
        $result=array('Status'=>'Validation Error',"Message"=>"There is some Validation Error In your system",'Error'=>$validate->errors());
        return response()->json($result);
    }
    $user = User::create([
        'name'=>$req->name,
        'email'=>$req->email,
        'password'=>bcrypt( $req->password)
    ]);
    if ($user->exists()) {
        $result = array('Status' => 'True', 'Message' => 'User Has Been Created', "Data" => $user);
    } else {
        $result = array('Status' => 'Failed due to some database error', 'Message' => 'User has not been created');
    }
    
    return response()->json($result);
}
public function showtheusers($id){
    $user= User::find($id);
    if(!$user){
        return response()->json(['Status'=>'No User','Message'=>'There is no User']);
    }
    return response()->json(['Status'=>'Success','Message'=>'User Found','Data'=>$user]);
}
public function userdeet(){
   try{ $user = User::all();
    return response()->json(['Status'=>'Sucess', "Message"=>"Showing All Users",'User Count'=>count($user),'Data'=>$user]);

}
catch(Exception $e){
    return response()->json(['Status'=>'Sucess', "Message"=>"Showing All Users",'Error'=>$e->getMessage()]);
}
}
public function updatingthedeets(Request $req , $id){
    $user = User::find($id);
    if(!$user){
        return response()->json(['status'=>'weird','message'=>'There is no User on your mentioned Id']);}
        $validator = Validator::make($req->all(),[
            'name'=>"required|string|min:8",
            'email'=>"required|email|unique:users"
        ]);
        if($validator->fails()){
            return response()->json(['status'=>'validation error','message'=>'There is validation error on your mentioned Id',"Error"=> $validator->errors()]); 
        }
        $user->name=$req->name;
        $user->email=$req->email;
        $user->save();
        return response()->json(['status'=>'Success','message'=>'User Has Been Updated',"Data"=>$user]);

}
}
