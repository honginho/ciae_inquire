<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CollegeData;
use Gate;


class UserController extends Controller
{
	public function index(Request $request){
		
		$user = $request->user();
		$collegeData = CollegeData::toChtName($user->college,$user->dept);

		$data = compact('collegeData');
		return view('user/user',$data);
	}
    //
    public function update(Request $request){
    	$id = $request->id;
    	$user = User::find($id);

    	
    	$this->validate($request,[
    		'password' => 'confirmed|max:16',
    		'contactPeople' => 'required|max:10',
    		'phone' => 'max:20',
    		'email' => 'email|max:50',
    	]);
    	foreach ($request->request as $key => $value) {
    		# code...
    		if($value != '')
    			$newRequest[$key] = $value;
    	}
    	$request->replace($newRequest);
    	if($request->password){
    		$request->merge(array('password' => bcrypt($request->password)));
    	}
    	
    	$user->update($request->all());
    	return redirect('/user')->with('success','更新成功');
    }
    public function manage(Request $request){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        $user = User::join('college_data',function($join){
                $join->on('user.college','college_data.college');
                $join->on('user.dept','college_data.dept');
            });
        $user = $user->orderBy($sortBy,$orderBy)
                ->paginate(20);
        $data = compact('user');
        return view('user/manage',$data);
    }
    public function manageInsert(Request $request){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'password' => 'required|confirmed|max:16',
            'contactPeople' => 'required|max:10',
            'phone' => 'max:20',
            'email' => 'email|max:50',
        ]);
        $user = User::create($request->all());
        $user->password = bcrypt($request->password);
        $user->permission = $request->permission;
        $user->save();
        return redirect('manage')->with('success','新增成功');
    }
    public function manageSearch(Request $request){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        $user = User::join('college_data',function($join){
                $join->on('user.college','college_data.college');
                $join->on('user.dept','college_data.dept');
            });
        if($request->college != 0)
            $user = $user->where('user.college',$request->college);
        if($request->dept != 0)
            $user = $user->where('user.dept',$request->dept);
        if($request->username != "")
            $user = $user->where('username',"like","%$request->username%");
        if($request->contactPeople != "")
            $user = $user->where('contactPeople',"like","%$request->contactPeople%");
        if($request->phone != "")
            $user = $user->where("phone","like","%$request->phone%");
        if($request->email != "")
            $user = $user->where('email',"like","%$request->email%");
        if($request->permission != "")
            $user = $user->where('permission',$request->permission);
        $user = $user->orderBy($sortBy,$orderBy)
                ->paginate(20);
        $data = compact('user');
        return view('user/manage',$data);
    }
    public function manageUpdate(Request $request,$id){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $this->validate($request,[
            'password' => 'confirmed|max:16',
            'contactPeople' => 'required|max:10',
            'phone' => 'max:20',
            'email' => 'email|max:50',
        ]);
        foreach ($request->request as $key => $value) {
            # code...
            if($value != '')
                $newRequest[$key] = $value;
        }
        $request->replace($newRequest);
        if($request->password){
            $request->merge(array('password' => bcrypt($request->password)));
        }
        $user = User::find($id);
        $user->update($request->all());
        $user->permission = $request->permission;
        $user->save();
        return redirect('manage')->with('success','更新成功');
    }
    public function manageDelete($id){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $user = User::find($id);
        $user->delete();
        return redirect('manage')->with('success','刪除成功');
    }
    public function manageEdit($id){
        if(!Gate::allows('superUser'))
            return redirect('home');
        $user = User::find($id);

        return view('user/manage_edit',$user);
    }
}
