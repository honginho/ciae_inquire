<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendInternationalOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class AttendInternationalOrganizationController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $user = Auth::user();
        
        $attendiorganization=AttendInternationalOrganization::
            join('college_data',function($join){
            $join->on('attend_international_organization.college','college_data.college');
            $join->on('attend_international_organization.dept','college_data.dept');
        });
        
        if($user->permission == 2){
            $attendiorganization = $attendiorganization->where('attend_international_organization.college',$user->college);
        }else if($user->permission == 3){
            $attendiorganization = $attendiorganization->where('attend_international_organization.college',$user->college)
                ->where('attend_international_organization.dept', $user->dept);
        }
        
        $attendiorganization = $attendiorganization->orderBy($sortBy,$orderBy)
            ->paginate(20);


        $attendiorganization->appends($request->except('page'));    
        $data=compact('attendiorganization','user');
    	return view ('other/attend_international_organization',$data);
    }
    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'organization'=>'required|max:200',
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
            return redirect('attend_international_organization')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('attend_international_organization')->withErrors($validator)->withInput();
        }

        AttendInternationalOrganization::create($request->all());
        return redirect('attend_international_organization')->with('success','新增成功');
    }

    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $attendiorganization = AttendInternationalOrganization::join('college_data',function($join){
                $join->on('attend_international_organization.college','college_data.college');
                $join->on('attend_international_organization.dept','college_data.dept');
            });
       
        if($request->college != 0)
            $attendiorganization = $attendiorganization
                ->where('attend_international_organization.college',$request->college);
        if($request->dept != 0)
            $attendiorganization = $attendiorganization
                ->where('attend_international_organization.dept',$request->dept);
        if($request->name != "")
            $attendiorganization = $attendiorganization
                ->where('name',"like","%$request->name%"); 
        if($request->organization != "")
            $attendiorganization = $attendiorganization
                ->where('organization',"like","%$request->organization%"); 
        if($request->startDate != "")
            $attendiorganization = $attendiorganization
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $attendiorganization = $attendiorganization
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $attendiorganization = $attendiorganization
                ->where('comments',"like","%$request->comments%");

        if($user->permission == 2){
            $attendiorganization = $attendiorganization->where('attend_international_organization.college',$user->college);
        }else if($user->permission == 3){
            $attendiorganization = $attendiorganization->where('attend_international_organization.college',$user->college)
                ->where('attend_international_organization.dept', $user->dept);
        }

        $attendiorganization = $attendiorganization->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $attendiorganization->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('attendiorganization','user');
        return view('other/attend_international_organization',$data);
    }

    public function edit($id){
        $attendiorganization = AttendInternationalOrganization::find($id);
        if(Gate::allows('permission',$attendiorganization))
            return view('other/attend_international_organization_edit',$attendiorganization);
        return redirect('attend_international_organization');
    }

    public function update($id,Request $request){
        $attendiorganization = AttendInternationalOrganization::find($id);
        if(!Gate::allows('permission',$attendiorganization))
            return redirect('attend_international_organization');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'organization'=>'required|max:200',
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
            return redirect("attend_international_organization/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("attend_international_organization/$id")->withErrors($validator)->withInput();
        }

        $attendiorganization->update($request->all());
        return redirect('attend_international_organization')->with('success','更新成功');
    }


    public function delete($id){
        $AIO = AttendInternationalOrganization::find($id);
        if(!Gate::allows('permission',$AIO))
            return redirect('attend_international_organization');
        $AIO->delete();
        return redirect('attend_international_organization');
    }


    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {

                if($this->isAllNull($item))
                    continue;

                $errorLine = $arrayKey + 2;
                $rules = [
                    '所屬一級單位'=>'required|max:11',
                    '所屬系所部門'=>'required|max:11',
                    '參加人'=>'required|max:20',
                    '組織名稱'=>'required|max:200',
                    '開始時間'=>'required|date',
                    '結束時間'=>'required|date',
                    '備註'=>'max:500',
                ];
                $message=[
                    'required'=>"必須填寫 :attribute 欄位,第 $errorLine 行",
                    'max'=>':attribute 欄位的輸入長度不能大於:max'.",第 $errorLine 行",
                    'date'=>':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx'.", 第 $errorLine 行",
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
                        case '參加人':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '組織名稱':
                            $item['organization'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
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
                    $validator->errors()->add('date','開始擔任時間必須在結束擔任時間前'.",第 $errorLine 行");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤'.",第 $errorLine 行");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門'.",第 $errorLine 行");
                }
                if(count($validator->errors())>0){
                    return redirect('attend_international_organization')
                        ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            AttendInternationalOrganization::insert($newArray);
        });
        return redirect('attend_international_organization');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/attend_international_organization.xlsx',"參與國際組織.xlsx");
    }

    private function isAllNull($array){
        foreach($array as $item){
            if($item != null)
                return false;
        }
        return true;
    }
}
