<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfInternationalAcademicCooperation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ProfInternationalAcademicCooperationController extends Controller
{
    public function index (Request $request) {
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if ($request->sortBy != null)
            $sortBy = $request->sortBy;
        if ($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pinternationalacademiccooperation = ProfInternationalAcademicCooperation::join('college_data', function ($join) {
            $join->on('prof_international_academic_cooperation.college', 'college_data.college');
            $join->on('prof_international_academic_cooperation.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation->where('prof_international_academic_cooperation.college', $user->college);
        }
        else if ($user->permission == 3) {
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation->where('prof_international_academic_cooperation.college', $user->college)
                ->where('prof_international_academic_cooperation.dept', $user->dept);
        }

        $Pinternationalacademiccooperation= $Pinternationalacademiccooperation->orderBy($sortBy, $orderBy)->paginate(20);
        $Pinternationalacademiccooperation->appends($request->except('page'));
    	$data=compact('Pinternationalacademiccooperation', 'user');
    	return view ('prof/prof_international_academic_cooperation', $data);
    }

    public function insert (Request $request) {
    	$rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'partnerNation' => 'required|max:20',
            'projectName' => 'required|max:200',
            'startDate' => 'required',
            'endDate' => 'required',
            'money' => 'required|max:11',
            'comments' => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max' => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect('prof_international_academic_cooperation')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('prof_international_academic_cooperation')->withErrors($validator)->withInput();
        }

    	ProfInternationalAcademicCooperation::create($request->all());
        return redirect('prof_international_academic_cooperation')->with('success', '新增成功');
    }

    public function search (Request $request) {
        $sortBy = 'id';
        $orderBy = "desc";
        $user = Auth::user();

        if ($request->sortBy != null)
            $sortBy = $request->sortBy;
        if ($request->orderBy != null)
            $orderBy = $request->orderBy;

        $Pinternationalacademiccooperation = ProfInternationalAcademicCooperation::join('college_data', function ($join) {
                $join->on('prof_international_academic_cooperation.college', 'college_data.college');
                $join->on('prof_international_academic_cooperation.dept', 'college_data.dept');
            });

        if ($request->college != 0)
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('prof_international_academic_cooperation.college', $request->college);
        if ($request->dept != 0)
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('prof_international_academic_cooperation.dept', $request->dept);
        if ($request->name != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('name', "like", "%$request->name%");
        if ($request->profLevel != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('profLevel', $request->profLevel);
        if ($request->partnerNation != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('partnerNation', "like", "%$request->partnerNation%");
        if ($request->projectName != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('projectName', "like", "%$request->projectName%");
        if ($request->startDate != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('startDate', '>=', "$request->startDate");
        if ($request->endDate != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('endDate', '<=', "$request->endDate");
        if ($request->money != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('money', $request->money);
        if ($request->comments != "")
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation
                ->where('comments', "like", "%$request->comments%");

        if ($user->permission == 2) {
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation->where('prof_international_academic_cooperation.college', $user->college);
        }
        else if ($user->permission == 3) {
            $Pinternationalacademiccooperation = $Pinternationalacademiccooperation->where('prof_international_academic_cooperation.college', $user->college)
                ->where('prof_international_academic_cooperation.dept', $user->dept);
        }

        $Pinternationalacademiccooperation = $Pinternationalacademiccooperation->orderBy($sortBy, $orderBy)->paginate(20);
        $Pinternationalacademiccooperation->appends($request->except('page'));
        $data = compact('Pinternationalacademiccooperation', 'user');
        return view('prof/prof_international_academic_cooperation', $data);
    }

    public function edit ($id) {
        $Pinternationalacademiccooperation = ProfInternationalAcademicCooperation::find($id);
        if (Gate::allows('permission', $Pinternationalacademiccooperation))
            return view('prof/prof_international_academic_cooperation_edit', $Pinternationalacademiccooperation);
        return redirect('prof_international_academic_cooperation');
    }

    public function update ($id, Request $request) {
        $Pinternationalacademiccooperation = ProfInternationalAcademicCooperation::find($id);
        if (!Gate::allows('permission', $Pinternationalacademiccooperation))
            return redirect('prof_international_academic_cooperation');
        $rules = [
            'college' => 'required|max:11',
            'dept' => 'required|max:11',
            'name' => 'required|max:20',
            'profLevel' => 'required|max:11',
            'partnerNation' => 'required|max:20',
            'projectName' => 'required|max:200',
            'startDate' => 'required',
            'endDate' => 'required',
            'money' => 'required|max:11',
            'comments' => 'max:500',
        ];

        $message=[
            'required' => '必須填寫:attribute欄位',
            'max' => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("prof_international_academic_cooperation/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("prof_international_academic_cooperation/$id")->withErrors($validator)->withInput();
        }

        $Pinternationalacademiccooperation->update($request->all());
        return redirect('prof_international_academic_cooperation')->with('success', '更新成功');
    }

    public function delete ($id) {
        $Pinternationalacademiccooperation = ProfInternationalAcademicCooperation::find($id);
        if (!Gate::allows('permission', $Pinternationalacademiccooperation))
            return redirect('prof_international_academic_cooperation');
        $Pinternationalacademiccooperation->delete();
        return redirect('prof_international_academic_cooperation');
    }

    public function upload (Request $request) {
        Excel::load($request->file('file'), function($reader) {
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {
                if ($this->isAllNull($item))
                    continue;

                $errorLine = $arrayKey + 2;
                $rules = [
                    '所屬一級單位' => 'required|max:11',
                    '所屬系所部門' => 'required|max:11',
                    '姓名' => 'required|max:20',
                    '身分教授副教授助理教授或博士後研究員' => 'required|max:11',
                    '合作國家' => 'required|max:20',
                    '計畫名稱' => 'required|max:200',
                    '執行期限起' => 'required|date',
                    '執行期限迄' => 'required|date',
                    '總核定金額' => 'required|max:11',
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
                        case '合作國家':
                            $item['partnerNation'] = $value;
                            unset($item[$key]);
                            break;
                        case '計畫名稱':
                            $item['projectName'] = $value;
                            unset($item[$key]);
                            break;
                        case '執行期限起':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '執行期限迄':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '總核定金額':
                            $item['money'] = $value;
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
                        ->where('dept', $item['dept'])->first() == null) {
                    $validator->errors()->add('number', '系所代碼錯誤'.", 第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object)$item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之系所部門'.", 第 $errorLine 行");
                }
                if (count($validator->errors())>0) {
                    return redirect('prof_international_academic_cooperation')->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            ProfInternationalAcademicCooperation::insert($newArray);
        });
        return redirect('prof_international_academic_cooperation');
    }

    public function example (Request $request) {
        return response()->download(public_path().'/Excel_example/prof/prof_international_academic_cooperation.xlsx', "與國外及兩岸學校進行學術合作交流.xlsx");
    }

    private function isAllNull ($array) {
        foreach ($array as $item) {
            if ($item != null)
                return false;
        }
        return true;
    }
}
