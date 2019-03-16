<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartnerSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;


class PartnerSchoolController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
    	$user=Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$partner= PartnerSchool::join('college_data',function($join){
    		$join->on('partner_school.college','college_data.college');
    		$join->on('partner_school.dept','college_data.dept');
    		});

        if($user->permission == 2){
            $partner = $partner->where('partner_school.college',$user->college);
        }else if($user->permission == 3){
            $partner = $partner->where('partner_school.college',$user->college)
                ->where('partner_school.dept', $user->dept);
        }
        $partner = $partner->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $partner->appends($request->except('page')); 
    	$data=compact('partner','user');

    	return view ('other/partner_school',$data);
    }

    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'nation'=>'required|max:20',
            'chtName'=>'max:50',
            'engName'=>'max:80',
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
            return redirect('partner_school')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('partner_school')->withErrors($validator)->withInput();
        }

        partnerschool::create($request->all());
        return redirect('partner_school')->with('success','新增成功');
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
    	$user=Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $partner = PartnerSchool::join('college_data',function($join){
                $join->on('partner_school.college','college_data.college');
                $join->on('partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $partner = $partner
                ->where('partner_school.college',$request->college);
        if($request->dept != 0)
            $partner = $partner
                ->where('partner_school.dept',$request->dept);        
        if($request->nation != "")
            $partner = $partner
                ->where('nation',"like","%$request->nation%"); 
        if($request->chtName != "")
            $partner = $partner
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $partner = $partner
                ->where('engName',"like","%$request->engName%");
        if($request->startDate != "")
            $partner = $partner
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $partner = $partner
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $partner = $partner
                ->where('comments','like',"%$request->comments%");
                
        if($user->permission == 2){
            $partner = $partner->where('partner_school.college',$user->college);
        }else if($user->permission == 3){
            $partner = $partner->where('partner_school.college',$user->college)
                ->where('partner_school.dept', $user->dept);
        }

        $partner = $partner->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $partner->appends($request->except('page'));    
        $data = compact('partner','user');
        return view('other/partner_school',$data);
    }

    
    public function edit($id){
        $partner = PartnerSchool::find($id);
        if(Gate::allows('permission',$partner))
            return view('other/partner_school_edit',$partner);
        return redirect('partner_school');
    }

    public function update($id,Request $request){
        $partner = PartnerSchool::find($id);
        if(!Gate::allows('permission',$partner))
            return redirect('partner_school');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'nation'=>'required|max:20',
            'chtName'=>'max:50',
            'engName'=>'max:80',
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
            return redirect("partner_school/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("partner_school/$id")->withErrors($validator)->withInput();
        }

        $partner->update($request->all());
        return redirect('partner_school')->with('success','更新成功');
    }

    public function delete($id){
        $partner = PartnerSchool::find($id);
        if(!Gate::allows('permission',$partner))
            return redirect('partner_school');
        $partner->delete();
        return redirect('partner_school');
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
                    '簽約機構所屬一級單位'=>'required|max:11',
                    '簽約系所'=>'required|max:11',
                    '姊妹校所屬國家'=>'required|max:20',
                    '中文校名'=>'max:50',
                    '英文校名'=>'max:80',
                    '簽約時間'=>'required|date',
                    '到期時間'=>'required|date',
                    '備註'=>'max:500',
                ];
                $message=[
                    'required'=>"必須填寫 :attribute 欄位,第 $errorLine 行",
                    'max'=>':attribute 欄位的輸入長度不能大於:max'.",第 $errorLine 行",
                    'date'=>':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx'.", 第 $errorLine 行"
                ];
                $validator = Validator::make($item,$rules,$message);

                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '簽約機構所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '簽約系所':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '姊妹校所屬國家':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '中文校名':
                            $item['chtName'] = $value;
                            unset($item[$key]);
                            break;
                        case '英文校名':
                            $item['engName'] = $value;
                            unset($item[$key]);
                            break;
                        case '簽約時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '到期時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            $item['comments'] = $value;
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
                    return redirect('partner_school')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            PartnerSchool::insert($newArray);
        });
        return redirect('partner_school');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/partner_school.xlsx',"姊妹校締約情形.xlsx");
    }
    
    private function isAllNull($array){
        foreach($array as $item){
            if($item != null)
                return false;
        }
        return true;
    }
}
