<?php

namespace App\Http\Controllers\other;

use App\CollegeData;
use App\CooperationProj;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class CooperationProjController extends Controller
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

        $cooperationproj = CooperationProj::join('college_data', function ($join) {
            $join->on('cooperation_proj.college', 'college_data.college');
            $join->on('cooperation_proj.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college);
        } else if ($user->permission == 3) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college)
                ->where('cooperation_proj.dept', $user->dept);
        }

        $cooperationproj = $cooperationproj->orderBy($sortBy, $orderBy)->paginate(20);
        $cooperationproj->appends($request->except('page'));
        $data = compact('cooperationproj', 'user');
        return view('other/cooperation_proj', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:10',
            'projName'  => 'required|max:200',
            'projOrg'   => 'required|max:200',
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
            return redirect('cooperation_proj')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('cooperation_proj')->withErrors($validator)->withInput();
        }

        cooperationproj::create($request->all());
        return redirect('cooperation_proj')->with('success', '新增成功');

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

        $cooperationproj = CooperationProj::join('college_data', function ($join) {
            $join->on('cooperation_proj.college', 'college_data.college');
            $join->on('cooperation_proj.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.college', $request->college);
        }

        if ($request->dept != 0) {
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.dept', $request->dept);
        }

        if ($request->projOrg != "") {
            $cooperationproj = $cooperationproj
                ->where('projOrg', "like", "%$request->projOrg%");
        }

        if ($request->name != "") {
            $cooperationproj = $cooperationproj
                ->where('name', "like", "%$request->name%");
        }

        if ($request->projName != "") {
            $cooperationproj = $cooperationproj
                ->where('projName', "like", "%$request->projName%");
        }

        if ($request->startDate != "") {
            $cooperationproj = $cooperationproj
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $cooperationproj = $cooperationproj
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $cooperationproj = $cooperationproj
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college);
        } else if ($user->permission == 3) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college)
                ->where('cooperation_proj.dept', $user->dept);
        }

        $cooperationproj = $cooperationproj->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $cooperationproj->appends($request->except('page'));
        $data = compact('cooperationproj', 'user');
        return view('other/cooperation_proj', $data);

    }

    public function edit($id) {
        $cooperationproj = CooperationProj::find($id);
        if (Gate::allows('permission', $cooperationproj)) {
            return view('other/cooperation_proj_edit', $cooperationproj);
        }

        return redirect('cooperation_proj');
    }

    public function update($id, Request $request) {
        $cooperationproj = CooperationProj::find($id);
        if (!Gate::allows('permission', $cooperationproj)) {
            return redirect('cooperation_proj');
        }

        $rules = [
            'college'   => 'required|max:11',
            'dept'      => 'required|max:11',
            'name'      => 'required|max:10',
            'projName'  => 'required|max:200',
            'projOrg'   => 'required|max:200',
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
            return redirect("cooperation_proj/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("cooperation_proj/$id")->withErrors($validator)->withInput();
        }

        $cooperationproj->update($request->all());
        return redirect('cooperation_proj')->with('success', '更新成功');
    }

    public function delete($id) {
        $cooperationproj = CooperationProj::find($id);
        if (!Gate::allows('permission', $cooperationproj)) {
            return redirect('cooperation_proj');
        }

        $cooperationproj->delete();
        return redirect('cooperation_proj');
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
                    '所屬一級單位' => 'required|max:11',
                    '所屬系所部門' => 'required|max:11',
                    '主辦人'    => 'required|max:10',
                    '計畫名稱'   => 'required|max:200',
                    '合作機構'   => 'required|max:200',
                    '開始時間'   => 'required|date',
                    '結束時間'   => 'required|date',
                    '備註'     => 'max:500',
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
                        case '主辦人':
                            $item['name'] = $value;
                            unset($item[$key]);
                        case '合作機構':
                            $item['projOrg'] = $value;
                            unset($item[$key]);
                            break;
                        case '計畫名稱':
                            $item['projName'] = $value;
                            unset($item[$key]);
                            break;
                        // case '計畫內容':
                        //     $item['projContent'] = $value;
                        //     unset($item[$key]);
                        //     break;
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
                    return redirect('cooperation_proj')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            CooperationProj::insert($newArray);
        });
        return redirect('cooperation_proj');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/other/cooperation_proj.xlsx', "國際合作交流計畫.xlsx");
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

        $cooperationproj = CooperationProj::join('college_data', function ($join) {
            $join->on('cooperation_proj.college', 'college_data.college');
            $join->on('cooperation_proj.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college);
        } else if ($user->permission == 3) {
            $cooperationproj = $cooperationproj->where('cooperation_proj.college', $user->college)
                ->where('cooperation_proj.dept', $user->dept);
        }

        $cooperationproj         = $cooperationproj->orderBy($sortBy, $orderBy)->get()->toArray();
        $cooperationproj_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '主辦人', '合作機構', '計畫名稱', '開始時間', '結束時間', '備註');
        // dd($cooperationproj);
        foreach ($cooperationproj as $cooperationproj_data) {
            $cooperationproj_array[] = array(
                // 'id'         => $cooperationproj_data['id'],
                'chtCollege' => $cooperationproj_data['chtCollege'],
                'chtDept'    => $cooperationproj_data['chtDept'],
                'name'       => $cooperationproj_data['name'],
                'projOrg'    => $cooperationproj_data['projOrg'],
                'projName'   => $cooperationproj_data['projName'],
                'startDate'  => $cooperationproj_data['startDate'],
                'endDate'    => $cooperationproj_data['endDate'],
                'comments'   => $cooperationproj_data['comments'],
            );
        }

        Excel::create('國際合作交流計畫', function ($excel) use ($cooperationproj_array) {
            $excel->setTitle('國際合作交流計畫');
            $excel->sheet('表單', function ($sheet) use ($cooperationproj_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($cooperationproj_array);
                $sheet->fromArray($cooperationproj_array, null, 'A1', false, false);
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
