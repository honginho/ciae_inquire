<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignStu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ForeignStuController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
    	$user=Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignStu = ForeignStu::join('college_data',function($join){
    		$join->on('foreign_stu.college','college_data.college');
    		$join->on('foreign_stu.dept','college_data.dept');
    		});

        if($user->permission == 2){
            $foreignStu = $foreignStu->where('foreign_stu.college',$user->college);
        }else if($user->permission == 3){
            $foreignStu = $foreignStu->where('foreign_stu.college',$user->college)
                ->where('foreign_stu.dept', $user->dept);
        }
        $foreignStu = $foreignStu->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignStu->appends($request->except('page'));  
    	$data = compact('foreignStu','user');
    	return view ('stu/foreign_stu',$data);

    }
 
    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'chtName'=>'required|max:50',
            'engName'=>'required|max:50',
            'stuID'=>'required|max:15',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:50',
            'engNation'=>'required|max:50',
            'startDate'=>'required',
            'endDate'=>'required',
            'status'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('foreign_stu')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('foreign_stu')->withErrors($validator)->withInput();
        }

        ForeignStu::create($request->all());
        return redirect('foreign_stu')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
    	$user=Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignStu = ForeignStu::join('college_data',function($join){
                $join->on('foreign_stu.college','college_data.college');
                $join->on('foreign_stu.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.college',$request->college);
        if($request->dept != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.dept',$request->dept);
        if($request->stuID != "")
            $foreignStu = $foreignStu
                ->where('stuID',"like","%$request->stuID%");
        if($request->chtName != "")
            $foreignStu = $foreignStu
                ->where('chtName',"like","%$request->chtName%");
        if($request->engName != "")
            $foreignStu = $foreignStu
                ->where('engName',"like","%$request->engName%");
        if($request->stuLevel != "")
            $foreignStu = $foreignStu
                ->where('stuLevel', $request->stuLevel);
        if($request->nation != "")
            $foreignStu = $foreignStu
                ->where('nation',"like","%$request->nation%");
        if($request->engNation != "")
            $foreignStu = $foreignStu
                ->where('engNation',"like","%$request->engNation%");
        if($request->startDate != "")
            $foreignStu = $foreignStu
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $foreignStu = $foreignStu
                ->where('endDate','<=',"$request->endDate");
        if($request->status != "")
            $foreignStu = $foreignStu
                ->where('status',"$request->status");
        if($request->comments != "")
            $foreignStu = $foreignStu
                ->where('comments',"like","%$request->comments%");
        
        if($user->permission == 2){
            $foreignStu = $foreignStu->where('foreign_stu.college',$user->college);
        }else if($user->permission == 3){
            $foreignStu = $foreignStu->where('foreign_stu.college',$user->college)
                ->where('foreign_stu.dept', $user->dept);
        }

        $foreignStu = $foreignStu->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignStu->appends($request->except('page'));
        $data = compact('foreignStu','user');
        return view('stu/foreign_stu',$data);
    }

    public function edit($id){
        $foreignStu = ForeignStu::find($id);
        if(Gate::allows('permission',$foreignStu))
            return view('stu/foreign_stu_edit',$foreignStu);
        return redirect('foreign_stu');
    }

    public function update($id,Request $request){
        $foreignStu = ForeignStu::find($id);
        if(!Gate::allows('permission',$foreignStu))
            return redirect('foreign_stu');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'chtName'=>'required|max:50',
            'engName'=>'required|max:50',
            'stuID'=>'required|max:15',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:50',
            'engNation'=>'required|max:50',
            'startDate'=>'required',
            'endDate'=>'required',
            'status'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect("foreign_stu/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("foreign_stu/$id")->withErrors($validator)->withInput();
        }

        $foreignStu->update($request->all());
        return redirect('foreign_stu')->with('success','更新成功');
    }

        public function delete($id){
        $foreignStu = ForeignStu::find($id);
        if(!Gate::allows('permission',$foreignStu))
            return redirect('foreign_stu');
        $foreignStu->delete();
        return redirect('foreign_stu');
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
                    '中文姓名'=>'required|max:50',
                    '英文姓名'=>'required|max:50',
                    '學號'=>'required|max:15',
                    '身分學士碩士或博士'=>'required|max:11',
                    '國籍中文'=>'required|max:50',
                    '國籍英文'=>'required|max:50',
                    '開始時間'=>'required|date',
                    '結束時間'=>'required|date',
                    '學籍狀態在學中休學中已畢業'=>'required',
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
                        case '學號':
                            $item['stuID'] = $value;
                            unset($item[$key]);
                            break;
                        case '中文姓名':
                            $item['chtName'] = $value;
                            unset($item[$key]);
                            break;
                        case '英文姓名':
                            $item['engName'] = $value;
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
                                    $validator->errors()->add('stuLevel',"身分內容填寫錯誤,第 $errorLine 行");
                                    break;
                            }
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '國籍中文':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '國籍英文':
                            $item['engNation'] = $value;
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
                        case '學籍狀態在學中休學中已畢業':
                            switch($value){
                                case "在學中":
                                    $value = 1;
                                    break;
                                case "休學中":
                                    $value = 2;
                                    break;
                                case "已畢業":
                                    $value = 3;
                                    break;
                                default:
                                    $validator->errors()->add('status',"學籍狀態內容填寫錯誤,第 $errorLine 行");
                                    break;
                            }
                            $item['status'] = $value;
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
                    return redirect('foreign_stu')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ForeignStu::insert($newArray);
        });
        return redirect('foreign_stu');
    }
    
    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/foreign_stu.xlsx',"修讀正式學位之外國學生.xlsx");
    }

    private function isAllNull($array){
        foreach($array as $item){
            if($item != null)
                return false;
        }
        return true;
    } 			
}
