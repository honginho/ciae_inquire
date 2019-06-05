<?php

namespace App\Http\Controllers\other;

use App\CollegeData;
use App\Http\Controllers\Controller;
use App\TransnationalDegree;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class TransnationalDegreeController extends Controller
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

        $transnational = TransnationalDegree::join('college_data', function ($join) {
            $join->on('transnational_degree.college', 'college_data.college');
            $join->on('transnational_degree.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $transnational = $transnational->where('transnational_degree.college', $user->college);
        } else if ($user->permission == 3) {
            $transnational = $transnational->where('transnational_degree.college', $user->college)
                ->where('transnational_degree.dept', $user->dept);
        }

        $transnational = $transnational->orderBy($sortBy, $orderBy)->paginate(20);
        $transnational->appends($request->except('page'));
        $data = compact('transnational', 'user');
        return view('other/transnational_degree', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'    => 'required|max:11',
            'dept'       => 'required|max:11',
            'nation'     => 'required|max:20',
            'chtName'    => 'required|max:200',
            'engName'    => 'required|max:200',
            'stuLevel'   => 'required',
            'year'       => 'required|max:200',
            'classMode'  => 'required|max:200',
            'degreeMode' => 'required|max:200',
            'comments'   => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];
        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect('transnational_degree')->withErrors($validator)->withInput();
        }

        transnationalDegree::create($request->all());
        return redirect('transnational_degree')->with('success', '新增成功');
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

        $transnational = TransnationalDegree::join('college_data', function ($join) {
            $join->on('transnational_degree.college', 'college_data.college');
            $join->on('transnational_degree.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $transnational = $transnational
                ->where('transnational_degree.college', $request->college);
        }

        if ($request->dept != 0) {
            $transnational = $transnational
                ->where('transnational_degree.dept', $request->dept);
        }

        if ($request->nation != "") {
            $transnational = $transnational
                ->where('nation', "like", "%$request->nation%");
        }

        if ($request->chtName != "") {
            $transnational = $transnational
                ->where('chtName', "like", "%$request->chtName%");
        }

        if ($request->engName != "") {
            $transnational = $transnational
                ->where('engName', "like", "%$request->engName%");
        }

        if ($request->bachelor != "") {
            $transnational = $transnational
                ->where('bachelor', $request->bachelor);
        }

        if ($request->master != "") {
            $transnational = $transnational
                ->where('master', $request->master);
        }

        if ($request->PHD != "") {
            $transnational = $transnational
                ->where('PHD', $request->PHD);
        }

        if ($request->teachMode != "") {
            $transnational = $transnational
                ->where('teachMode', "like", "%$request->teachMode%");
        }

        if ($request->degreeMode != "") {
            $transnational = $transnational
                ->where('degreeMode', 'like', "%$request->degreeMode%");
        }

        if ($request->comments != "") {
            $transnational = $transnational
                ->where('comments', 'like', "%$request->comments%");
        }

        if ($user->permission == 2) {
            $transnational = $transnational->where('transnational_degree.college', $user->college);
        } else if ($user->permission == 3) {
            $transnational = $transnational->where('transnational_degree.college', $user->college)
                ->where('transnational_degree.dept', $user->dept);
        }

        $transnational = $transnational->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $transnational->appends($request->except('page'));
        $user = Auth::user();
        $data = compact('transnational', 'user');
        return view('other/transnational_degree', $data);
    }

    public function edit($id) {
        $transnational = TransnationalDegree::find($id);
        if (Gate::allows('permission', $transnational)) {
            return view('other/transnational_degree_edit', $transnational);
        }

        return redirect('transnational_degree');
    }

    public function update($id, Request $request) {
        $transnational = TransnationalDegree::find($id);
        if (!Gate::allows('permission', $transnational)) {
            return redirect('transnational_degree');
        }

        $rules = [
            'college'    => 'required|max:11',
            'dept'       => 'required|max:11',
            'nation'     => 'required|max:20',
            'chtName'    => 'required|max:200',
            'engName'    => 'required|max:200',
            'stuLevel'   => 'required',
            'year'       => 'required|max:200',
            'classMode'  => 'required|max:200',
            'degreeMode' => 'required|max:200',
            'comments'   => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect("transnational_degree/$id")->withErrors($validator)->withInput();
        }

        $transnational->update($request->all());
        return redirect('transnational_degree')->with('success', '更新成功');

    }

    public function delete($id) {
        $transnational = TransnationalDegree::find($id);
        if (!Gate::allows('permission', $transnational)) {
            return redirect('transnational_degree');
        }

        $transnational->delete();
        return redirect('transnational_degree');
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
                    '所屬一級單位'     => 'required|max:11',
                    '所屬系所部門'     => 'required|max:11',
                    '國家'         => 'required|max:20',
                    '中文校名'       => 'required|max:200',
                    '英文校名'       => 'required|max:200',
                    '身分_學士碩士或博士' => 'required',
                    '修業年限'       => 'required|max:200',
                    '授課方式'       => 'required|max:200',
                    '學位授予方式'     => 'required|max:200',
                    '備註'         => 'max:500',
                ];
                $message = [
                    'required' => "必須填寫 :attribute 欄位,第 $errorLine 行",
                    'max'      => ':attribute 欄位的輸入長度不能大於:max' . ",第 $errorLine 行",
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
                        case '國家':
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
                        case '身分_學士碩士或博士':
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
                        case '修業年限';
                            $item['year'] = $value;
                            unset($item[$key]);
                            break;
                        case '授課方式':
                            $item['classMode'] = $value;
                            unset($item[$key]);
                            break;
                        case '學位授予方式':
                            $item['degreeMode'] = $value;
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

                if (CollegeData::where('college', $item['college'])
                    ->where('dept', $item['dept'])->first() == null) {
                    $validator->errors()->add('number', '系所代碼錯誤' . ",第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object) $item)) {
                    $validator->errors()->add('permission', '無法新增未有權限之系所部門' . ",第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('transnational_degree')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            TransnationalDegree::insert($newArray);
        });
        return redirect('transnational_degree');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/other/transnational_degree.xlsx', "跨國學位.xlsx");
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

        $transnational = TransnationalDegree::join('college_data', function ($join) {
            $join->on('transnational_degree.college', 'college_data.college');
            $join->on('transnational_degree.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $transnational = $transnational->where('transnational_degree.college', $user->college);
        } else if ($user->permission == 3) {
            $transnational = $transnational->where('transnational_degree.college', $user->college)
                ->where('transnational_degree.dept', $user->dept);
        }

        $transnational         = $transnational->orderBy($sortBy, $orderBy)->get()->toArray();
        $transnational_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '國家', '中文校名', '英文校名', '身分', '修業年限', '授課方式', '學位授予方式', '備註');
        // dd($transnational);
        foreach ($transnational as $transnational_data) {
            $stuLevel = $transnational_data['stuLevel'];
            if ($stuLevel == 1) $stuLevel = '博士班';
            else if ($stuLevel == 2) $stuLevel = '碩士班';
            else if ($stuLevel == 3) $stuLevel = '學士班';

            $transnational_array[] = array(
                // 'id'         => $transnational_data['id'],
                'chtCollege' => $transnational_data['chtCollege'],
                'chtDept'    => $transnational_data['chtDept'],
                'nation'     => $transnational_data['nation'],
                'chtName'    => $transnational_data['chtName'],
                'engName'    => $transnational_data['engName'],
                'stuLevel'   => $stuLevel,
                'year'       => $transnational_data['year'],
                'classMode'  => $transnational_data['classMode'],
                'degreeMode' => $transnational_data['degreeMode'],
                'comments'   => $transnational_data['comments'],
            );
        }

        Excel::create('跨國學位', function ($excel) use ($transnational_array) {
            $excel->setTitle('跨國學位');
            $excel->sheet('表單', function ($sheet) use ($transnational_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($transnational_array);
                $sheet->fromArray($transnational_array, null, 'A1', false, false);
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
