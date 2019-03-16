<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternationalJournalEditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;


class InternationalJournalEditorController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor=InternationalJournalEditor::
            join('college_data',function($join){
            $join->on('international_journal_editor.college','college_data.college');
            $join->on('international_journal_editor.dept','college_data.dept');
        });

        if($user->permission == 2){
            $internationaljeditor = $internationaljeditor->where('international_journal_editor.college',$user->college);
        }else if($user->permission == 3){
            $internationaljeditor = $internationaljeditor->where('international_journal_editor.college',$user->college)
                ->where('international_journal_editor.dept', $user->dept);
        }
        
        $internationaljeditor = $internationaljeditor->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationaljeditor->appends($request->except('page'));    
        $data=compact('internationaljeditor','user');

    	return view ('other/international_journal_editor',$data);
    }
    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'journalName'=>'required|max:200',
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
            return redirect('international_journal_editor')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('international_journal_editor')->withErrors($validator)->withInput();
        }

        InternationalJournalEditor::create($request->all());
        return redirect('international_journal_editor')->with('success','新增成功');
    }

    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();
        
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor = InternationalJournalEditor::join('college_data',function($join){
                $join->on('international_journal_editor.college','college_data.college');
                $join->on('international_journal_editor.dept','college_data.dept');
            });
        if($request->college != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.college',$request->college);
        if($request->dept != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.dept',$request->dept);
        if($request->name != "")
            $internationaljeditor = $internationaljeditor
                ->where('name',"like","%$request->name%"); 
        if($request->journalName != "")
            $internationaljeditor = $internationaljeditor
                ->where('journalName',"like","%$request->journalName%"); 
        if($request->startDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $internationaljeditor = $internationaljeditor
                ->where('comments',"like","%$request->comments%");

        if($user->permission == 2){
            $internationaljeditor = $internationaljeditor->where('international_journal_editor.college',$user->college);
        }else if($user->permission == 3){
            $internationaljeditor = $internationaljeditor->where('international_journal_editor.college',$user->college)
                ->where('international_journal_editor.dept', $user->dept);
        }

        $internationaljeditor = $internationaljeditor->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationaljeditor->appends($request->except('page'));    
        $data = compact('internationaljeditor','user');
        return view('other/international_journal_editor',$data);
    }

     public function edit($id){
        $internationaljeditor = InternationalJournalEditor::find($id);
        if(Gate::allows('permission',$internationaljeditor))
            return view('other/international_journal_editor_edit',$internationaljeditor);
        return redirect('international_journal_editor');
    }

    public function update($id,Request $request){
        $internationaljeditor = InternationalJournalEditor::find($id);
        if(!Gate::allows('permission',$internationaljeditor))
            return redirect('international_journal_editor');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'journalName'=>'required|max:200',
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
            return redirect("international_journal_editor/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("international_journal_editor/$id")->withErrors($validator)->withInput();
        }

        $internationaljeditor->update($request->all());
        return redirect('international_journal_editor')->with('success','更新成功');


    }



    public function delete($id){
        $IJE = InternationalJournalEditor::find($id);
        if(!Gate::allows('permission',$IJE))
            return redirect('international_journal_editor');
        $IJE->delete();
        return redirect('international_journal_editor');
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
                    '期刊編輯者'=>'required|max:20',
                    '期刊名稱'=>'required|max:200',
                    '開始擔任時間'=>'required|date',
                    '結束擔任時間'=>'required|date',
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
                        case '所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '期刊名稱':
                            $item['journalName'] = $value;
                            unset($item[$key]);
                            break;
                        case '期刊編輯者':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始擔任時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束擔任時間':
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
                    return redirect('international_journal_editor')
                        ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            InternationalJournalEditor::insert($newArray);
        });
        return redirect('international_journal_editor');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/international_journal_editor.xlsx',"擔任國際期刊編輯.xlsx");
    }  

    private function isAllNull($array){
        foreach($array as $item){
            if($item != null)
                return false;
        }
        return true;
    }
}
