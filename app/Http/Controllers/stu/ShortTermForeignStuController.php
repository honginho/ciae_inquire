<?php

namespace App\Http\Controllers\stu;

use App\CollegeData;
use App\Http\Controllers\Controller;
use App\ShortTermForeignStu;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class ShortTermForeignStuController extends Controller
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

        $shortterm = ShortTermForeignStu::join('college_data', function ($join) {
            $join->on('short_term_foreign_stu.college', 'college_data.college');
            $join->on('short_term_foreign_stu.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college);
        } else if ($user->permission == 3) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college)
                ->where('short_term_foreign_stu.dept', $user->dept);
        }

        $shortterm = $shortterm->orderBy($sortBy, $orderBy)
            ->paginate(20);

        $shortterm->appends($request->except('page'));
        $data = compact('shortterm', 'user');
        return view('stu/short_term_foreign_stu', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:50',
            'stuLevel'  => 'required|max:11',
            'nation'    => 'required|max:50',
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
            return redirect('short_term_foreign_stu')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('short_term_foreign_stu')->withErrors($validator)->withInput();
        }

        ShortTermForeignStu::create($request->all());
        return redirect('short_term_foreign_stu')->with('success', '新增成功');
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

        $shortterm = ShortTermForeignStu::join('college_data', function ($join) {
            $join->on('short_term_foreign_stu.college', 'college_data.college');
            $join->on('short_term_foreign_stu.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.college', $request->college);
        }

        if ($request->dept != 0) {
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.dept', $request->dept);
        }

        if ($request->name != "") {
            $shortterm = $shortterm
                ->where('name', "like", "%$request->name%");
        }

        if ($request->stuLevel != "") {
            $shortterm = $shortterm
                ->where('stuLevel', $request->stuLevel);
        }

        if ($request->nation != "") {
            $shortterm = $shortterm
                ->where('nation', "like", "%$request->nation%");
        }

        if ($request->startDate != "") {
            $shortterm = $shortterm
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $shortterm = $shortterm
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $shortterm = $shortterm
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college);
        } else if ($user->permission == 3) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college)
                ->where('short_term_foreign_stu.dept', $user->dept);
        }

        $shortterm = $shortterm->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $shortterm->appends($request->except('page'));
        $data = compact('shortterm', 'user');
        return view('stu/short_term_foreign_stu', $data);
    }

    public function edit($id) {
        $shortterm = ShortTermForeignStu::find($id);
        if (Gate::allows('permission', $shortterm)) {
            return view('stu/short_term_foreign_stu_edit', $shortterm);
        }

        return redirect('short_term_foreign_stu');
    }

    public function update($id, Request $request) {
        $shortterm = ShortTermForeignStu::find($id);
        if (!Gate::allows('permission', $shortterm)) {
            return redirect('short_term_foreign_stu');
        }

        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:50',
            'stuLevel'  => 'required|max:11',
            'nation'    => 'required|max:50',
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
            return redirect("short_term_foreign_stu/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("short_term_foreign_stu/$id")->withErrors($validator)->withInput();
        }

        $shortterm->update($request->all());
        return redirect('short_term_foreign_stu')->with('success', '更新成功');
    }

    public function delete($id) {
        $shortterm = ShortTermForeignStu::find($id);
        if (!Gate::allows('permission', $shortterm)) {
            return redirect('short_term_foreign_stu');
        }

        $shortterm->delete();
        return redirect('short_term_foreign_stu');
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
                    '所屬一級單位'    => 'required|max:11',
                    '所屬系所部門'    => 'required|max:11',
                    '姓名'        => 'required|max:50',
                    '身分學士碩士或博士' => 'required|max:11',
                    '國籍'        => 'required|max:50',
                    '開始時間'      => 'required|date',
                    '結束時間'      => 'required|date',
                    '備註'        => 'max:500',
                ];

                $message = [
                    'required' => '必須填寫:attribute欄位',
                    'max'      => ':attribute欄位的輸入長度不能大於:max',
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
                        case '身分學士碩士或博士':
                            switch ($value) {
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
                                    $validator->errors()->add('身分', "身分內容填寫錯誤,第 $errorLine 行");
                                    break;
                            }
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '國籍':
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
                    return redirect('short_term_foreign_stu')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            ShortTermForeignStu::insert($newArray);
        });
        return redirect('short_term_foreign_stu');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/stu/short_term_foreign_stu.xlsx', "外籍學生至本校短期交流訪問或實習.xlsx");
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

        $shortterm = ShortTermForeignStu::join('college_data', function ($join) {
            $join->on('short_term_foreign_stu.college', 'college_data.college');
            $join->on('short_term_foreign_stu.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college);
        } else if ($user->permission == 3) {
            $shortterm = $shortterm->where('short_term_foreign_stu.college', $user->college)
                ->where('short_term_foreign_stu.dept', $user->dept);
        }

        $shortterm         = $shortterm->orderBy($sortBy, $orderBy)->get()->toArray();
        $shortterm_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '姓名', '身份', '國籍', '開始時間', '結束時間', '備註');
        // dd($shortterm);
        foreach ($shortterm as $shortterm_data) {
            $stuLevel = $shortterm_data['stuLevel'];
            if ($stuLevel == 1) $stuLevel = '博士班';
            else if ($stuLevel == 2) $stuLevel = '碩士班';
            else if ($stuLevel == 3) $stuLevel = '學士班';

            $shortterm_array[] = array(
                // 'id'         => $shortterm_data['id'],
                'chtCollege' => $shortterm_data['chtCollege'],
                'chtDept'    => $shortterm_data['chtDept'],
                'name'       => $shortterm_data['name'],
                'stuLevel'   => $stuLevel,
                'nation'     => $shortterm_data['nation'],
                'startDate'  => $shortterm_data['startDate'],
                'endDate'    => $shortterm_data['endDate'],
                'comments'   => $shortterm_data['comments'],
            );
        }

        Excel::create('境外學生至本校短期交流訪問或實習', function ($excel) use ($shortterm_array) {
            $excel->setTitle('境外學生至本校短期交流訪問或實習');
            $excel->sheet('表單', function ($sheet) use ($shortterm_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($shortterm_array);
                $sheet->fromArray($shortterm_array, null, 'A1', false, false);
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
