<?php

namespace App\Http\Controllers\prof;

use App\CollegeData;
use App\Http\Controllers\Controller;
use App\ProfForeignResearch;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class ProfForeignResearchController extends Controller
{
    public function index(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $Pforeignresearch = ProfForeignResearch::join('college_data', function ($join) {
            $join->on('prof_foreign_research.college', 'college_data.college');
            $join->on('prof_foreign_research.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college);
        } else if ($user->permission == 3) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college)
                ->where('prof_foreign_research.dept', $user->dept);
        }

        $Pforeignresearch = $Pforeignresearch->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $Pforeignresearch->appends($request->except('page'));
        $data = compact('Pforeignresearch', 'user');
        return view('prof/prof_foreign_research', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:20',
            'profLevel' => 'required|max:11',
            'nation'    => 'required|max:20',
            'startDate' => 'required',
            'endDate'   => 'required',
            'comments'  => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect('prof_foreign_research')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('prof_foreign_research')->withErrors($validator)->withInput();
        }

        ProfForeignResearch::create($request->all());
        return redirect('prof_foreign_research')->with('success', '新增成功');
    }

    public function search(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $Pforeignresearch = ProfForeignResearch::join('college_data', function ($join) {
            $join->on('prof_foreign_research.college', 'college_data.college');
            $join->on('prof_foreign_research.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $Pforeignresearch = $Pforeignresearch
                ->where('prof_foreign_research.college', $request->college);
        }

        if ($request->dept != 0) {
            $Pforeignresearch = $Pforeignresearch
                ->where('prof_foreign_research.dept', $request->dept);
        }

        if ($request->name != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('name', "like", "%$request->name%");
        }

        if ($request->profLevel != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('profLevel', $request->profLevel);
        }

        if ($request->nation != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('nation', "like", "%$request->nation%");
        }

        if ($request->startDate != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $Pforeignresearch = $Pforeignresearch
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college);
        } else if ($user->permission == 3) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college)
                ->where('prof_foreign_research.dept', $user->dept);
        }

        $Pforeignresearch = $Pforeignresearch->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $Pforeignresearch->appends($request->except('page'));
        $data = compact('Pforeignresearch', 'user');
        return view('prof/prof_foreign_research', $data);
    }

    public function edit($id) {
        $Pforeignresearch = ProfForeignResearch::find($id);
        if (Gate::allows('permission', $Pforeignresearch)) {
            return view('prof/prof_foreign_research_edit', $Pforeignresearch);
        }

        return redirect('prof_foreign_research');
    }

    public function update($id, Request $request) {
        $Pforeignresearch = ProfForeignResearch::find($id);
        if (!Gate::allows('permission', $Pforeignresearch)) {
            return redirect('prof_foreign_research');
        }

        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:20',
            'profLevel' => 'required|max:11',
            'nation'    => 'required|max:20',
            'startDate' => 'required',
            'endDate'   => 'required',
            'comments'  => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("prof_foreign_research/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("prof_foreign_research/$id")->withErrors($validator)->withInput();
        }

        $Pforeignresearch->update($request->all());
        return redirect('prof_foreign_research')->with('success', '更新成功');
    }

    public function delete($id) {
        $Pforeignresearch = ProfForeignResearch::find($id);
        if (!Gate::allows('permission', $Pforeignresearch)) {
            return redirect('prof_foreign_research');
        }

        $Pforeignresearch->delete();
        return redirect('prof_foreign_research');
    }

    public function upload(Request $request) {
        Excel::load($request->file('file'), function ($reader) {
            $array    = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {

                if ($this->isAllNull($item)) {
                    continue;
                }

                $errorLine = $arrayKey + 2;
                $rules     = [
                    '所屬一級單位'             => 'required|max:11',
                    '所屬系所部門'             => 'required|max:11',
                    '姓名'                 => 'required|max:20',
                    '身分教授副教授助理教授或博士後研究員' => 'required|max:11',
                    '前往國家'               => 'required|max:20',
                    '開始時間'               => 'required|date',
                    '結束時間'               => 'required|date',
                    '備註'                 => 'max:500',
                ];
                $message = [
                    'required' => "必須填寫 :attribute 欄位,第 $errorLine 行",
                    'max'      => ':attribute 欄位的輸入長度不能大於:max' . ",第 $errorLine 行",
                    'date'     => ':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx' . ", 第 $errorLine 行",
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
                                    $validator->errors()->add('身分', "身分內容填寫錯誤,第 $errorLine 行");
                                    break;
                            }
                            $item['profLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '前往國家':
                            $item['nation'] = $value;
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
                    $validator->errors()->add('date', '開始時間必須在結束時間前' . ",第 $errorLine 行");
                }
                if (CollegeData::where('college', $item['college'])
                    ->where('dept', $item['dept'])->first() == null) {
                    $validator->errors()->add('number', '系所代碼錯誤' . ",第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object) $item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之系所部門' . ",第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('prof_foreign_research')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            ProfForeignResearch::insert($newArray);
        });
        return redirect('prof_foreign_research');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/prof/prof_foreign_research.xlsx', "本校教師赴國外研究.xlsx");
    }

    public function download(Request $request) {
        $sortBy  = 'id';
        $orderBy = "desc";
        $user    = Auth::user();

        if ($request->sortBy != null) {
            $sortBy = $request->sortBy;
        }

        if ($request->orderBy != null) {
            $orderBy = $request->orderBy;
        }

        $Pforeignresearch = ProfForeignResearch::join('college_data', function ($join) {
            $join->on('prof_foreign_research.college', 'college_data.college');
            $join->on('prof_foreign_research.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college);
        } else if ($user->permission == 3) {
            $Pforeignresearch = $Pforeignresearch->where('prof_foreign_research.college', $user->college)
                ->where('prof_foreign_research.dept', $user->dept);
        }

        $Pforeignresearch         = $Pforeignresearch->orderBy($sortBy, $orderBy)->get()->toArray();
        $Pforeignresearch_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '姓名', '身份', '前往國家', '開始時間', '結束時間', '備註');
        // dd($Pforeignresearch);
        foreach ($Pforeignresearch as $Pforeignresearch_data) {
            $profLevel = $Pforeignresearch_data['profLevel'];
            if ($profLevel == 1) $profLevel = '教授';
            else if ($profLevel == 2) $profLevel = '副教授';
            else if ($profLevel == 3) $profLevel = '助理教授';
            else if ($profLevel == 4) $profLevel = '博士後研究員';
            else if ($profLevel == 5) $profLevel = '研究生';

            $Pforeignresearch_array[] = array(
                // 'id'         => $Pforeignresearch_data['id'],
                'chtCollege' => $Pforeignresearch_data['chtCollege'],
                'chtDept'    => $Pforeignresearch_data['chtDept'],
                'name'       => $Pforeignresearch_data['name'],
                'profLevel'  => $profLevel,
                'nation'     => $Pforeignresearch_data['nation'],
                'startDate'  => $Pforeignresearch_data['startDate'],
                'endDate'    => $Pforeignresearch_data['endDate'],
                'comments'   => $Pforeignresearch_data['comments'],
            );
        }

        Excel::create('本校教師赴國外研究', function ($excel) use ($Pforeignresearch_array) {
            $excel->setTitle('本校教師赴國外研究');
            $excel->sheet('表單', function ($sheet) use ($Pforeignresearch_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($Pforeignresearch_array);
                $sheet->fromArray($Pforeignresearch_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

    private function isAllNull($array) {
        foreach ($array as $item) {
            if ($item != null) {
                return false;
            }
        }
        return true;
    }
}
