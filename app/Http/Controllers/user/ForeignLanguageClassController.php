<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignLanguageClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;

class ForeignLanguageClassController extends Controller
{
    //
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignLanguageClass = ForeignLanguageClass::join('college_data',function($join){
    		$join->on('foreign_language_class.college','college_data.college');
    		$join->on('foreign_language_class.dept','college_data.dept');
    	})->orderBy($sortBy,$orderBy)
            ->paginate(20);
        
        $foreignLanguageClass->appends($request->except('page'));    
    	$user = Auth::user();
    	$data = compact('foreignLanguageClass','user');


    	return view('user/foreign_language_class',$data);
    }

    public function insert(Request $request){
        
        $this->validate($request,[
        
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'year'=>'required|max:5',
            'semester'=>'required|max:1',
            'chtName'=>'required|max:50',
            'engName'=>'required|max:50',
            'teacher'=>'required|max:20',
            'language'=>'required|max:20',
            'totalCount'=>'required|max:11',
            'nationalCount'=>'required|max:11',

            ]);

        ForeignLanguageClass::create($request->all());

        return redirect('foreign_language_class')->with('success','新增成功');
    }

    public function edit($id){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if(Gate::allows('permission',$foreignLanguageClass)){
            return view('user/foreign_language_class_edit',$foreignLanguageClass);
        }
        return redirect('foreign_language_class');
    }

    public function update($id , Request $request){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if(!Gate::allows('permission',$foreignLanguageClass))
            return redirect('foreign_language_class');
        $this->validate($request,[
            'year' => 'required',
            'chtName' => 'required|max:50',
            'engName' => 'required|max:200',
            'teacher' => 'required|max:20',
            'language' => 'required|max:20',
            'totalCount' => 'required',
            'nationalCount' => 'required',
            ]);
        $foreignLanguageClass->update($request->all());
        return redirect('foreign_language_class');
    }
    public function delete($id){
        $foreignLanguageClass = ForeignLanguageClass::find($id);
        if(!Gate::allows('permission',$foreignLanguageClass))
            return redirect('foreign_language_class');
        $foreignLanguageClass->delete();
        return redirect('foreign_language_class');
    }

    public function search(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $foreignLanguageClass = ForeignLanguageClass::join('college_data',function($join){
    		$join->on('foreign_language_class.college','college_data.college');
    		$join->on('foreign_language_class.dept','college_data.dept');
    	});
        if($request->college != 0)
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.college',$request->college);
        if($request->dept != 0)
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.dept',$request->dept);
        if($request->year != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.year',$request->year);
        if($request->semester != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.semester',$request->semester);
        if($request->chtName != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('chtName',"like","%$request->chtName%");
        if($request->engName != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('engName',"like","%$request->engName%");
        if($request->teacher != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('teacher',"like","%$request->teacher%");
        if($request->totalCount !="")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.totalCount',$request->totalCount);
        if($request->nationalCount !="")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.nationalCount',$request->nationalCount);        


        $foreignLanguageClass = $foreignLanguageClass->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignLanguageClass->appends($request->except('page'));  
        $user = Auth::user();
        $data = compact('foreignLanguageClass','user');
        return view('user/foreign_language_class',$data);
    }

    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '單位名稱':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '學年':
                            $item['year'] = $value;
                            unset($item[$key]);
                            break;
                        case '學期':
                            $item['semester'] = $value;
                            unset($item[$key]);
                            break;
                        case '課程中文名稱':
                            $item['chtName'] = $value;
                            unset($item[$key]);
                            break;
                        case '課程英文名稱':
                            $item['engName'] = $value;
                            unset($item[$key]);
                            break;
                        case '授課教師':
                            $item['teacher'] = $value;
                            unset($item[$key]);
                            break;
                        case '授課語言':
                            $item['language'] = $value;
                            unset($item[$key]);
                            break;
                        case '總人數':
                            $item['totalCount'] = $value;
                            unset($item[$key]);
                            break;
                        case '外籍生人數':
                            $item['nationalCount'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            break;
                    }

                } 
                    
                $validator = Validator::make($item,[
                    'year' => 'required',
                    'chtName' => 'required|max:50',
                    'engName' => 'required|max:200',
                    'teacher' => 'required|max:20',
                    'language' => 'required|max:20',
                    'totalCount' => 'required',
                    'nationalCount' => 'required',
                ]);
                if($validator->fails()){
                    return redirect('foreign_language_class')
                        ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ForeignLanguageClass::insert($newArray);
        });
        return redirect('foreign_language_class');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/user/foreign_language_class.xlsx',"全外語授課之課程.xlsx");
    }

}
