<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfSpeechLecture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ProfSpeechLectureController extends Controller
{
    public function index(Request $request) {
    	$sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pspeechlecture = ProfSpeechLecture::join('college_data', function ($join) {
            $join->on('prof_speech_lecture.college', 'college_data.college');
            $join->on('prof_speech_lecture.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $Pspeechlecture = $Pspeechlecture->where('prof_speech_lecture.college', $user->college);
        }
        else if ($user->permission == 3) {
            $Pspeechlecture = $Pspeechlecture->where('prof_speech_lecture.college', $user->college)->where('prof_speech_lecture.dept', $user->dept);
        }

        $Pspeechlecture = $Pspeechlecture->orderBy($sortBy, $orderBy)->paginate(20);
        $Pspeechlecture->appends($request->except('page'));
    	$data = compact('Pspeechlecture', 'user');
    	return view ('prof/prof_speech_lecture', $data);
    }

    public function insert(Request $request) {

        $rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'lecture' => 'required|max:200',
            'isForeign' => 'required|max:11',
            'place' => 'required|max:50',
            'startDate' => 'required',
            'endDate' => 'required',
            'comments' => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max' => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect('prof_speech_lecture')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('prof_speech_lecture')->withErrors($validator)->withInput();
        }

        ProfSpeechLecture::create($request->all());
        return redirect('prof_speech_lecture')->with('success', '新增成功');
    }

    public function search (Request $request) {
    	$sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $Pspeechlecture = ProfSpeechLecture::join('college_data', function ($join) {
                $join->on('prof_speech_lecture.college', 'college_data.college');
                $join->on('prof_speech_lecture.dept', 'college_data.dept');
        });

        if ($request->college != 0)
            $Pspeechlecture = $Pspeechlecture
                ->where('prof_speech_lecture.college', $request->college);
        if ($request->dept != 0)
            $Pspeechlecture = $Pspeechlecture
                ->where('prof_speech_lecture.dept', $request->dept);
        if ($request->name != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('name', "like", "%$request->name%");
        if ($request->profLevel != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('profLevel', $request->profLevel);
        if ($request->lecture != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('lecture', "like", "%$request->lecture%");
        if ($request->isForeign != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('isForeign', $request->isForeign);
        if ($request->place != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('place', "like", "%$request->place%");
        if ($request->startDate != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('startDate', '>=', "$request->startDate");
        if ($request->endDate != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('endDate', '<=', "$request->endDate");
        if ($request->comments != "")
            $Pspeechlecture = $Pspeechlecture
                ->where('comments', "like", "%$request->comments%");

        if ($user->permission == 2) {
            $Pspeechlecture = $Pspeechlecture->where('prof_speech_lecture.college', $user->college);
        }
        else if ($user->permission == 3) {
            $Pspeechlecture = $Pspeechlecture->where('prof_speech_lecture.college', $user->college)->where('prof_speech_lecture.dept', $user->dept);
        }

        $Pspeechlecture = $Pspeechlecture->orderBy($sortBy, $orderBy)->paginate(20);
        $Pspeechlecture->appends($request->except('page'));
        $data = compact('Pspeechlecture', 'user');
        return view('prof/prof_speech_lecture', $data);
    }

    public function delete($id) {
        $Pspeechlecture = ProfSpeechLecture::find($id);
        if(!Gate::allows('permission', $Pspeechlecture))
            return redirect('prof_speech_lecture');
        $Pspeechlecture->delete();
        return redirect('prof_speech_lecture');
    }

    public function edit($id) {
        $Pspeechlecture = ProfSpeechLecture::find($id);
        if(Gate::allows('permission', $Pspeechlecture))
            return view('prof/prof_speech_lecture_edit', $Pspeechlecture);
        return redirect('prof_speech_lecture');
    }
    public function update($id, Request $request) {
        $Pspeechlecture = ProfSpeechLecture::find($id);
        if(!Gate::allows('permission', $Pspeechlecture))
            return redirect('prof_speech_lecture');
        $rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'lecture' => 'required|max:200',
            'isForeign' => 'required|max:11',
            'place' => 'required|max:50',
            'startDate' => 'required',
            'endDate' => 'required',
            'comments' => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max' => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("prof_speech_lecture/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("prof_speech_lecture/$id")->withErrors($validator)->withInput();
        }

        $Pspeechlecture->update($request->all());
        return redirect('prof_speech_lecture')->with('success', '更新成功');
    }


    public function upload(Request $request) {
        Excel::load($request->file('file'), function ($reader) {
            $array = $reader->toArray();
            $newArray = [];

            foreach ($array as $arrayKey => $item) {
                if($this->isAllNull($item))
                    continue;

                $errorLine = $arrayKey + 2;
                $rules = [
                    '所屬一級單位' => 'required|max:11',
                    '所屬系所部門' => 'required|max:11',
                    '姓名' => 'required|max:20',
                    '身分教授副教授助理教授或博士後研究員' => 'required|max:11',
                    '演講研習活動或講學' => 'required|max:200',
                    '境內外' => 'required|max:11',
                    '地點' => 'required|max:50',
                    '開始時間' => 'required|date',
                    '結束時間' => 'required|date',
                    '備註' => 'max:500',
                ];
                $message = [
                    'required'=>"必須填寫 :attribute 欄位, 第 $errorLine 行",
                    'max'=>':attribute 欄位的輸入長度不能大於:max'.", 第 $errorLine 行",
                    'date'=>':attribute 欄位時間格式錯誤,  應為 xxxx/xx/xx'.",  第 $errorLine 行"
                ];
                $validator = Validator::make($item, $rules, $message);

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
                        case '身分教授副教授助理教授或博士後研究員':
                            switch ($value) {
                                case "教授":
                                    $value = 1;
                                    break;
                                case "副教授":
                                    $value = 2;
                                    break;
                                case "助理教授":
                                    $value = 3;
                                    break;
                                case "博士後研究員":
                                    $value = 4;
                                    break;
                                default:
                                    $validator->errors()->add('身分', "身分內容填寫錯誤, 第 $errorLine 行");
                                    break;
                            }
                            $item['profLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '演講研習活動或講學':
                            $item['lecture'] = $value;
                            unset($item[$key]);
                            break;
                        case '境內外':
                            switch ($value) {
                                case "境內":
                                    $value = 0;
                                    break;
                                case "境外":
                                    $value = 1;
                                    break;
                                default:
                                    $validator->errors()->add('境內外', "境內外內容填寫錯誤, 第 $errorLine 行");
                                    break;
                            }
                            $item['isForeign'] = $value;
                            unset($item[$key]);
                            break;
                        case '地點':
                            $item['place'] = $value;
                            unset($item[$key]);
                            break;
                        case '地點':
                            $item['place'] = $value;
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

                if ($item['startDate'] > $item['endDate']) {
                    $validator->errors()->add('date', '開始時間必須在結束時間前'.", 第 $errorLine 行");
                }
                if (CollegeData::where('college', $item['college'])->where('dept', $item['dept'])->first()==null) {
                    $validator->errors()->add('number', '系所代碼錯誤'.", 第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object)$item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之系所部門'.", 第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('prof_speech_lecture')->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            ProfSpeechLecture::insert($newArray);
        });
        return redirect('prof_speech_lecture');
    }

    public function example(Request $request) {
        return response()->download(public_path().'/Excel_example/prof/prof_speech_lecture.xlsx', "本校教師赴國外研究.xlsx");
    }

    private function isAllNull($array) {
        foreach ($array as $item) {
            if ($item != null)
                return false;
        }
        return true;
    }
}
