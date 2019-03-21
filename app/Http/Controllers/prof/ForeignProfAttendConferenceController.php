<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfAttendConference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ForeignProfAttendConferenceController extends Controller
{
    public function index(Request $request) {
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if ($request->sortBy != null)
            $sortBy = $request->sortBy;
        if ($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignPattendconference = ForeignProfAttendConference::join('college_data', function ($join) {
            $join->on('foreign_prof_attend_conference.college', 'college_data.college');
            $join->on('foreign_prof_attend_conference.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $foreignPattendconference = $foreignPattendconference->where('foreign_prof_attend_conference.college', $user->college);
        }
        else if ($user->permission == 3) {
            $foreignPattendconference = $foreignPattendconference->where('foreign_prof_attend_conference.college', $user->college)->where('foreign_prof_attend_conference.dept', $user->dept);
        }

        $foreignPattendconference = $foreignPattendconference->orderBy($sortBy, $orderBy)->paginate(20);
        $foreignPattendconference->appends($request->except('page'));
    	$data = compact('foreignPattendconference', 'user');
    	return view ('prof/foreign_prof_attend_conference', $data);
    }
    public function insert(Request $request) {
    	 $rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'nation' => 'required|max:20',
            'confName' => 'required|max:200',
            'publishedPaperName' => 'required|max:100',
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
            return redirect('foreign_prof_attend_conference')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('foreign_prof_attend_conference')->withErrors($validator)->withInput();
        }

    	ForeignProfAttendConference::create($request->all());
        return redirect('foreign_prof_attend_conference')->with('success', '新增成功');
    }

    public function search (Request $request) {
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if ($request->sortBy != null)
            $sortBy = $request->sortBy;
        if ($request->orderBy != null)
            $orderBy = $request->orderBy;

        $foreignPattendconference = ForeignProfAttendConference::join('college_data', function ($join) {
                $join->on('foreign_prof_attend_conference.college', 'college_data.college');
                $join->on('foreign_prof_attend_conference.dept', 'college_data.dept');
        });
        if ($request->college != 0)
            $foreignPattendconference = $foreignPattendconference
                ->where('foreign_prof_attend_conference.college', $request->college);
        if ($request->dept != 0)
            $foreignPattendconference = $foreignPattendconference
                ->where('foreign_prof_attend_conference.dept', $request->dept);
        if ($request->name != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('name', "like", "%$request->name%");
        if ($request->profLevel != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('profLevel', $request->profLevel);
        if ($request->nation != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('nation', "like", "%$request->nation%");
        if ($request->confName != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('confName', "like", "%$request->confName%");
        if ($request->publishedPaperName != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('publishedPaperName', "like", "%$request->publishedPaperName%");
        if ($request->comments != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('comments', "like", "%$request->comments%");
        if ($request->startDate != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('startDate', '>=', "$request->startDate");
        if ($request->endDate != "")
            $foreignPattendconference = $foreignPattendconference
                ->where('endDate', '<=', "$request->endDate");

        if ($user->permission == 2) {
            $foreignPattendconference = $foreignPattendconference->where('foreign_prof_attend_conference.college', $user->college);
        }else if ($user->permission == 3) {
            $foreignPattendconference = $foreignPattendconference->where('foreign_prof_attend_conference.college', $user->college)
                ->where('foreign_prof_attend_conference.dept', $user->dept);
        }

        $foreignPattendconference = $foreignPattendconference->orderBy($sortBy, $orderBy)->paginate(20);
        $foreignPattendconference->appends($request->except('page'));
        $data = compact('foreignPattendconference', 'user');
        return view('prof/foreign_prof_attend_conference', $data);
    }

    public function edit($id) {
        $foreignPattendconference = ForeignProfAttendConference::find($id);
        if (Gate::allows('permission', $foreignPattendconference))
            return view('prof/foreign_prof_attend_conference_edit', $foreignPattendconference);
        return redirect('foreign_prof_attend_conference');
    }

    public function update($id, Request $request) {
        $foreignPattendconference = ForeignProfAttendConference::find($id);
        if (!Gate::allows('permission', $foreignPattendconference))
            return redirect('foreign_prof_attend_conference');

        $rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'nation' => 'required|max:20',
            'confName' => 'required|max:200',
            'publishedPaperName' => 'required|max:100',
            'startDate' => 'required',
            'endDate' => 'required',
            'comments' => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max' => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("foreign_prof_attend_conference/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("foreign_prof_attend_conference/$id")->withErrors($validator)->withInput();
        }

        $foreignPattendconference->update($request->all());
        return redirect('foreign_prof_attend_conference')->with('success', '更新成功');
    }

    public function delete($id) {
        $foreignPattendconference = ForeignProfAttendConference::find($id);
        if (!Gate::allows('permission', $foreignPattendconference))
            return redirect('foreign_prof_attend_conference');
        $foreignPattendconference->delete();
        return redirect('foreign_prof_attend_conference');
    }


    public function upload(Request $request) {
        Excel::load($request->file('file'), function($reader) {
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {
                if ($this->isAllNull($item))
                    continue;

                $errorLine = $arrayKey + 2;
                $rules = [
                    '一級邀請單位' => 'required|max:11',
                    '二級邀請單位' => 'required|max:11',
                    '境外學者姓名' => 'required|max:20',
                    '境外學者身分教授副教授助理教授或博士後研究員' => 'required|max:11',
                    '國籍' => 'required|max:20',
                    '會議名稱' => 'required|max:200',
                    '發表論文名稱' => 'required|max:100',
                    '開始時間' => 'required|date',
                    '結束時間' => 'required|date',
                    '備註' => 'max:500',
                ];
                $message = [
                    'required' => "必須填寫 :attribute 欄位, 第 $errorLine 行",
                    'max' => ':attribute 欄位的輸入長度不能大於:max'.", 第 $errorLine 行",
                    'date' => ':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx'.", 第 $errorLine 行"
                ];
                $validator = Validator::make($item, $rules, $message);

                foreach ($item as $key => $value) {
                    switch ($key) {
                        case '一級邀請單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '二級邀請單位':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '境外學者姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '境外學者身分教授副教授助理教授或博士後研究員':
                            switch($value) {
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
                        case '國籍':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '會議名稱':
                            $item['confName'] = $value;
                            unset($item[$key]);
                            break;
                        case '發表論文名稱':
                            $item['publishedPaperName'] = $value;
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
                if (CollegeData::where('college', $item['college'])
                        ->where('dept', $item['dept'])->first()==null) {
                    $validator->errors()->add('number', '單位代碼錯誤'.", 第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object)$item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之單位'.", 第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('foreign_prof_attend_conference')->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            ForeignProfAttendConference::insert($newArray);
        });
        return redirect('foreign_prof_attend_conference');
    }

    public function example(Request $request) {
        return response()->download(public_path().'/Excel_example/prof/foreign_prof_attend_conference.xlsx', "境外學者來校出席國際會議.xlsx");
    }

    private function isAllNull($array) {
        foreach($array as $item) {
            if ($item != null)
                return false;
        }
        return true;
    }
}
