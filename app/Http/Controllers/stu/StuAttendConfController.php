<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuAttendConf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class StuAttendConfController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
    	$user = Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$conf = StuAttendConf::join('college_data',function($join){
    		$join->on('stu_attend_conf.college','college_data.college');
    		$join->on('stu_attend_conf.dept','college_data.dept');
    		});

        if($user->permission == 2){
            $conf = $conf->where('stu_attend_conf.college',$user->college);
        }else if($user->permission == 3){
            $conf = $conf->where('stu_attend_conf.college',$user->college)
                ->where('stu_attend_conf.dept', $user->dept);
        }

        $conf = $conf->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $conf->appends($request->except('page'));  
    	$data=compact('conf','user');
    	return view ('stu/stu_attend_conf',$data);
    	}

    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'confName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('stu_attend_conf')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('stu_attend_conf')->withErrors($validator)->withInput();
        }

        StuAttendConf::create($request->all());
        return redirect('stu_attend_conf')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $conf = StuAttendConf::join('college_data',function($join){
                $join->on('stu_attend_conf.college','college_data.college');
                $join->on('stu_attend_conf.dept','college_data.dept');
            });
        if($request->college != 0)
            $conf = $conf
                ->where('stu_attend_conf.college',$request->college);
        if($request->dept != 0)
            $conf = $conf
                ->where('stu_attend_conf.dept',$request->dept);
        if($request->name != "")
            $conf = $conf
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $conf = $conf
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $conf = $conf
                ->where('nation',"like","%$request->nation%");
        if($request->confName != "")
            $conf = $conf
                ->where('confName',"like","%$request->confName%"); 
        if($request->startDate != "")
            $conf = $conf
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $conf = $conf
                ->where('endDate','<=',"$request->endDate"); 
        if($request->comments != "")
            $conf = $conf
                ->where('comments',"like","%$request->comments%");
        
        if($user->permission == 2){
            $conf = $conf->where('stu_attend_conf.college',$user->college);
        }else if($user->permission == 3){
            $conf = $conf->where('stu_attend_conf.college',$user->college)
                ->where('stu_attend_conf.dept', $user->dept);
        }

        $conf = $conf->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $conf->appends($request->except('page'));    
        $data = compact('conf','user');
        return view('stu/stu_attend_conf',$data);
    }	
     public function delete($id){
        $conf = StuAttendConf::find($id);
        if(!Gate::allows('permission',$conf))
            return redirect('stu_attend_conf');
        $conf->delete();
        return redirect('stu_attend_conf');
    }


    public function edit($id){
        $conf = StuAttendConf::find($id);

        if(Gate::allows('permission',$conf))
            return view('stu/stu_attend_conf_edit',$conf);
        return redirect('stu_attend_conf');
    }

public function update($id,Request $request){
        $conf = StuAttendConf::find($id);
        if(!Gate::allows('permission',$conf))
            return redirect('stu_attend_conf');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'confName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect("stu_attend_conf/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("stu_attend_conf/$id")->withErrors($validator)->withInput();
        }
        
        $conf->update($request->all());
        return redirect('stu_attend_conf')->with('success','更新成功');
    }

    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {

                if($this->isAllNull($item))
                    continue;
                    
                $errorLine = $arrayKey + 2;
                $rules=[
                    '所屬一級單位'=>'required|max:11',
                    '所屬系所部門'=>'required|max:11',
                    '姓名'=>'required|max:20',
                    '身分學士碩士或博士'=>'required|max:11',
                    '前往國家'=>'required|max:20',
                    '會議名稱'=>'required|max:200',
                    '開始時間'=>'required|date',
                    '結束時間'=>'required|date',
                    '備註'=>'max:500',
                ];

                $message=[
                    'required'=>'必須填寫:attribute欄位',
                    'max'=>':attribute欄位的輸入長度不能大於:max',
                    'date'=>':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx'.", 第 $errorLine 行"
                ];
                $validator = Validator::make($item,$rules,$message);

                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '身分學士碩士或博士':
                            switch($value){
                                case "學士":
                                    $value = 3;
                                    break;
                                case "碩士":
                                    $value = 2;
                                    break;
                                case "博士":
                                    $value = 1;
                                    break;
                                default:
                                    $validator->errors()->add('身分',"身分內容填寫錯誤,第 $errorLine 行");
                                    break;
                            }
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '前往國家':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '會議名稱':
                            $item['confName'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            unset($item[$key]);
                            break;
                    }
                }

                if($item['startDate'] > $item['endDate']){
                    $validator->errors()->add('date','開始時間必須在結束時間前'.",第 $errorLine 行");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤'.",第 $errorLine 行");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門'.",第 $errorLine 行");
                }
                if(count($validator->errors())>0){
                    return redirect('stu_attend_conf')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            StuAttendConf::insert($newArray);
        });
        return redirect('stu_attend_conf');
    }
    
    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/stu_attend_conf.xlsx',"本校學生赴國外出席國際會議.xlsx");
    }

    private function isAllNull($array){
        foreach($array as $item){
            if($item != null)
                return false;
        }
        return true;
    }
}
