<?php

namespace App\Http\Controllers\prof;

use App\CollegeData;
use App\ForeignProfExchange;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class ForeignProfExchangeController extends Controller
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

        $foreignPexchange = ForeignProfExchange::
            join('college_data', function ($join) {
            $join->on('foreign_prof_exchange.college', 'college_data.college');
            $join->on('foreign_prof_exchange.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college);
        } else if ($user->permission == 3) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college)
                ->where('foreign_prof_exchange.dept', $user->dept);
        }
        $foreignPexchange = $foreignPexchange->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $foreignPexchange->appends($request->except('page'));

        $data = compact('foreignPexchange', 'user');

        return view('prof/foreign_prof_exchange', $data);
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
            return redirect('foreign_prof_exchange')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('foreign_prof_exchange')->withErrors($validator)->withInput();
        }

        foreignprofexchange::create($request->all());
        return redirect('foreign_prof_exchange')->with('success', '新增成功');
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

        $foreignPexchange = ForeignProfExchange::join('college_data', function ($join) {
            $join->on('foreign_prof_exchange.college', 'college_data.college');
            $join->on('foreign_prof_exchange.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $foreignPexchange = $foreignPexchange
                ->where('foreign_prof_exchange.college', $request->college);
        }

        if ($request->dept != 0) {
            $foreignPexchange = $foreignPexchange
                ->where('foreign_prof_exchange.dept', $request->dept);
        }

        if ($request->name != "") {
            $foreignPexchange = $foreignPexchange
                ->where('name', "like", "%$request->name%");
        }

        if ($request->profLevel != "") {
            $foreignPexchange = $foreignPexchange
                ->where('profLevel', $request->profLevel);
        }

        if ($request->nation != "") {
            $foreignPexchange = $foreignPexchange
                ->where('nation', "like", "%$request->nation%");
        }

        if ($request->startDate != "") {
            $foreignPexchange = $foreignPexchange
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $foreignPexchange = $foreignPexchange
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($request->comments != "") {
            $foreignPexchange = $foreignPexchange
                ->where('comments', "like", "%$request->comments%");
        }

        if ($user->permission == 2) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college);
        } else if ($user->permission == 3) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college)
                ->where('foreign_prof_exchange.dept', $user->dept);
        }

        $foreignPexchange = $foreignPexchange->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $foreignPexchange->appends($request->except('page'));
        $data = compact('foreignPexchange', 'user');
        return view('prof/foreign_prof_exchange', $data);
    }

    public function edit($id) {
        $foreignPexchange = ForeignProfexchange::find($id);
        if (Gate::allows('permission', $foreignPexchange)) {
            return view('prof/foreign_prof_exchange_edit', $foreignPexchange);
        }

        return redirect('foreign_prof_exchange');
    }

    public function update($id, Request $request) {
        $foreignPexchange = ForeignProfExchange::find($id);
        if (!Gate::allows('permission', $foreignPexchange)) {
            return redirect('foreign_prof_exchange');
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
            return redirect("foreign_prof_exchange/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("foreign_prof_exchange/$id")->withErrors($validator)->withInput();
        }

        $foreignPexchange->update($request->all());
        return redirect('foreign_prof_exchange')->with('success', '更新成功');
    }

    public function delete($id) {
        $AIO = foreignprofexchange::find($id);
        if (!Gate::allows('permission', $AIO)) {
            return redirect('foreign_prof_exchange');
        }

        $AIO->delete();
        return redirect('foreign_prof_exchange');
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
                    '邀請單位一級單位名稱'             => 'required|max:11',
                    '邀請單位二級單位名稱'             => 'required|max:11',
                    '國籍'                     => 'required|max:20',
                    '境外學者姓名'                 => 'required|max:20',
                    '境外學者身分教授副教授助理教授或博士後研究員' => 'required|max:20',
                    '開始時間'                   => 'required|date',
                    '結束時間'                   => 'required|date',
                    '備註'                     => 'max:500',
                ];
                $message = [
                    'required' => "必須填寫 :attribute 欄位,第 $errorLine 行",
                    'max'      => ':attribute 欄位的輸入長度不能大於:max' . ",第 $errorLine 行",
                    'date'     => ':attribute 欄位時間格式錯誤, 應為 xxxx/xx/xx' . ", 第 $errorLine 行",
                ];
                $validator = Validator::make($item, $rules, $message);

                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '邀請單位一級單位名稱':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '邀請單位二級單位名稱':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '境外學者姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '境外學者身分教授副教授助理教授或博士後研究員':
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
                    $validator->errors()->add('number', "系所代碼錯誤,第 $errorLine 行");
                }
                if (!Gate::allows('permission', (object) $item)) {
                    $validator->errors()->add('permission', "無法新增未有權限之系所部門,第 $errorLine 行");
                }
                if (count($validator->errors()) > 0) {
                    return redirect('foreign_prof_exchange')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            foreignprofexchange::insert($newArray);
        });
        return redirect('foreign_prof_exchange');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/prof/foreign_prof_exchange.xlsx', "境外學者蒞校交換.xlsx");
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

        $foreignPexchange = ForeignProfExchange::join('college_data', function ($join) {
            $join->on('foreign_prof_exchange.college', 'college_data.college');
            $join->on('foreign_prof_exchange.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college);
        } else if ($user->permission == 3) {
            $foreignPexchange = $foreignPexchange->where('foreign_prof_exchange.college', $user->college)
                ->where('foreign_prof_exchange.dept', $user->dept);
        }

        $foreignPexchange         = $foreignPexchange->orderBy($sortBy, $orderBy)->get()->toArray();
        $foreignPexchange_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '姓名', '身份', '國籍', '開始時間', '結束時間', '備註');
        // dd($foreignPexchange);
        foreach ($foreignPexchange as $foreignPexchange_data) {
            $profLevel = $foreignPexchange_data['profLevel'];
            if ($profLevel == 1) $profLevel = '教授';
            else if ($profLevel == 2) $profLevel = '副教授';
            else if ($profLevel == 3) $profLevel = '助理教授';
            else if ($profLevel == 4) $profLevel = '博士後研究員';
            else if ($profLevel == 5) $profLevel = '研究生';

            $foreignPexchange_array[] = array(
                // 'id'         => $foreignPexchange_data['id'],
                'chtCollege' => $foreignPexchange_data['chtCollege'],
                'chtDept'    => $foreignPexchange_data['chtDept'],
                'name'       => $foreignPexchange_data['name'],
                'profLevel'  => $profLevel,
                'nation'     => $foreignPexchange_data['nation'],
                'startDate'  => $foreignPexchange_data['startDate'],
                'endDate'    => $foreignPexchange_data['endDate'],
                'comments'   => $foreignPexchange_data['comments'],
            );
        }

        Excel::create('境外學者來校交換', function ($excel) use ($foreignPexchange_array) {
            $excel->setTitle('境外學者來校交換');
            $excel->sheet('表單', function ($sheet) use ($foreignPexchange_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($foreignPexchange_array);
                $sheet->fromArray($foreignPexchange_array, null, 'A1', false, false);
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
