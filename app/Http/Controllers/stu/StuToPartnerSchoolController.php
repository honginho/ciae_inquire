<?php

namespace App\Http\Controllers\stu;

use App\CollegeData;
use App\Http\Controllers\Controller;
use App\StuToPartnerSchool;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class StuToPartnerSchoolController extends Controller
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

        $topartnerdata = StuToPartnerSchool::join('college_data', function ($join) {
            $join->on('stu_to_partner_school.college', 'college_data.college');
            $join->on('stu_to_partner_school.dept', 'college_data.dept');
        });
        if ($user->permission == 2) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college);
        } else if ($user->permission == 3) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college)
                ->where('stu_to_partner_school.dept', $user->dept);
        }

        $topartnerdata = $topartnerdata->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $topartnerdata->appends($request->except('page'));
        $data = compact('topartnerdata', 'user');
        return view('stu/stu_to_partner_school', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:20',
            'stuLevel'  => 'required|max:11',
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
            return redirect('stu_to_partner_school')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('stu_to_partner_school')->withErrors($validator)->withInput();
        }

        StuToPartnerSchool::create($request->all());
        return redirect('stu_to_partner_school')->with('success', '新增成功');
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

        $topartnerdata = StuToPartnerSchool::join('college_data', function ($join) {
            $join->on('stu_to_partner_school.college', 'college_data.college');
            $join->on('stu_to_partner_school.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $topartnerdata = $topartnerdata
                ->where('stu_to_partner_school.college', $request->college);
        }

        if ($request->dept != 0) {
            $topartnerdata = $topartnerdata
                ->where('stu_to_partner_school.dept', $request->dept);
        }

        if ($request->name != "") {
            $topartnerdata = $topartnerdata
                ->where('name', "like", "%$request->name%");
        }

        if ($request->stuLevel != "") {
            $topartnerdata = $topartnerdata
                ->where('stuLevel', $request->stuLevel);
        }

        if ($request->nation != "") {
            $topartnerdata = $topartnerdata
                ->where('nation', "like", "%$request->nation%");
        }

        if ($request->startDate != "") {
            $topartnerdata = $topartnerdata
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $topartnerdata = $topartnerdata
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $topartnerdata = $topartnerdata
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college);
        } else if ($user->permission == 3) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college)
                ->where('stu_to_partner_school.dept', $user->dept);
        }

        $topartnerdata = $topartnerdata->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $topartnerdata->appends($request->except('page'));
        $data = compact('topartnerdata', 'user');
        return view('stu/stu_to_partner_school', $data);
    }

    public function edit($id) {
        $topartnerdata = StuToPartnerSchool::find($id);
        if (Gate::allows('permission', $topartnerdata)) {
            return view('stu/stu_to_partner_school_edit', $topartnerdata);
        }

        return redirect('stu_to_partner_school');
    }

    public function update($id, Request $request) {
        $topartnerdata = StuToPartnerSchool::find($id);
        if (!Gate::allows('permission', $topartnerdata)) {
            return redirect('stu_to_partner_school');
        }

        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:20',
            'stuLevel'  => 'required|max:11',
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
            return redirect("stu_to_partner_school/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("stu_to_partner_school/$id")->withErrors($validator)->withInput();
        }

        $topartnerdata->update($request->all());
        return redirect('stu_to_partner_school')->with('success', '更新成功');
    }

    public function delete($id) {
        $topartnerdata = StuToPartnerSchool::find($id);
        if (!Gate::allows('permission', $topartnerdata)) {
            return redirect('stu_to_partner_school');
        }

        $topartnerdata->delete();
        return redirect('stu_to_partner_school');
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
                    '姓名'        => 'required|max:20',
                    '身分學士碩士或博士' => 'required|max:11',
                    '前往國家'      => 'required|max:20',
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
                    return redirect('stu_to_partner_school')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            StuToPartnerSchool::insert($newArray);
        });
        return redirect('stu_to_partner_school');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/stu/stu_to_partner_school.xlsx', "本校學生出國赴姊妹校參加交換計畫.xlsx");
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

        $topartnerdata = StuToPartnerSchool::join('college_data', function ($join) {
            $join->on('stu_to_partner_school.college', 'college_data.college');
            $join->on('stu_to_partner_school.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college);
        } else if ($user->permission == 3) {
            $topartnerdata = $topartnerdata->where('stu_to_partner_school.college', $user->college)
                ->where('stu_to_partner_school.dept', $user->dept);
        }

        $topartnerdata         = $topartnerdata->orderBy($sortBy, $orderBy)->get()->toArray();
        $topartnerdata_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '姓名', '身份', '前往國家', '開始時間', '結束時間', '備註');
        // dd($topartnerdata);
        foreach ($topartnerdata as $topartnerdata_data) {
            $stuLevel = $topartnerdata_data['stuLevel'];
            if ($stuLevel == 1) $stuLevel = '博士班';
            else if ($stuLevel == 2) $stuLevel = '碩士班';
            else if ($stuLevel == 3) $stuLevel = '學士班';

            $topartnerdata_array[] = array(
                // 'id'         => $topartnerdata_data['id'],
                'chtCollege' => $topartnerdata_data['chtCollege'],
                'chtDept'    => $topartnerdata_data['chtDept'],
                'name'       => $topartnerdata_data['name'],
                'stuLevel'   => $stuLevel,
                'nation'     => $topartnerdata_data['nation'],
                'startDate'  => $topartnerdata_data['startDate'],
                'endDate'    => $topartnerdata_data['endDate'],
                'comments'   => $topartnerdata_data['comments'],
            );
        }

        Excel::create('本校學生出國赴姊妹校參加交換計畫', function ($excel) use ($topartnerdata_array) {
            $excel->setTitle('本校學生出國赴姊妹校參加交換計畫');
            $excel->sheet('表單', function ($sheet) use ($topartnerdata_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($topartnerdata_array);
                $sheet->fromArray($topartnerdata_array, null, 'A1', false, false);
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
