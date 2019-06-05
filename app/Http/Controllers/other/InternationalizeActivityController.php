<?php

namespace App\Http\Controllers\other;

use App\CollegeData;
use App\Http\Controllers\Controller;
use App\InternationalizeActivity;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class InternationalizeActivityController extends Controller
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

        $internationalactivity = InternationalizeActivity::join('college_data', function ($join) {
            $join->on('internationalize_activity.college', 'college_data.college');
            $join->on('internationalize_activity.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college);
        } else if ($user->permission == 3) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college)
                ->where('internationalize_activity.dept', $user->dept);
        }

        $internationalactivity = $internationalactivity->orderBy($sortBy, $orderBy)->paginate(20);

        $internationalactivity->appends($request->except('page'));
        $data = compact('internationalactivity', 'user');

        return view('other/internationalize_activity', $data);
    }

    public function insert(Request $request) {
        $rules = [
            'college'      => 'required|max:11',
            'dept'         => 'required|max:11',
            'activityName' => 'required|max:200',
            'place'        => 'required|max:200',
            'host'         => 'required|max:200',
            'guest'        => 'required|max:200',
            'startDate'    => 'required',
            'endDate'      => 'required',
            'comments'     => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect('internationalize_activity')->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect('internationalize_activity')->withErrors($validator)->withInput();
        }

        InternationalizeActivity::create($request->all());
        return redirect('internationalize_activity')->with('success', '新增成功');
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

        $internationalactivity = InternationalizeActivity::join('college_data', function ($join) {
            $join->on('internationalize_activity.college', 'college_data.college');
            $join->on('internationalize_activity.dept', 'college_data.dept');
        });
        if ($request->college != 0) {
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.college', $request->college);
        }

        if ($request->dept != 0) {
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.dept', $request->dept);
        }

        if ($request->activityName != "") {
            $internationalactivity = $internationalactivity
                ->where('activityName', "like", "%$request->activityName%");
        }

        if ($request->place != "") {
            $internationalactivity = $internationalactivity
                ->where('place', "like", "%$request->place%");
        }

        if ($request->host != "") {
            $internationalactivity = $internationalactivity
                ->where('host', "like", "%$request->host%");
        }

        if ($request->guest != "") {
            $internationalactivity = $internationalactivity
                ->where('guest', "like", "%$request->guest%");
        }

        if ($request->startDate != "") {
            $internationalactivity = $internationalactivity
                ->where('startDate', '>=', "$request->startDate");
        }

        if ($request->endDate != "") {
            $internationalactivity = $internationalactivity
                ->where('endDate', '<=', "$request->endDate");
        }

        if ($user->permission == 2) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college);
        } else if ($user->permission == 3) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college)
                ->where('internationalize_activity.dept', $user->dept);
        }

        $internationalactivity = $internationalactivity->orderBy($sortBy, $orderBy)
            ->paginate(20);
        $internationalactivity->appends($request->except('page'));
        $data = compact('internationalactivity', 'user');
        return view('other/internationalize_activity', $data);
    }

    public function edit($id) {
        $internationalactivity = InternationalizeActivity::find($id);
        if (Gate::allows('permission', $internationalactivity)) {
            return view('other/internationalize_activity_edit', $internationalactivity);
        }

        return redirect('internationalize_activity');
    }

    public function update($id, Request $request) {
        $internationalactivity = InternationalizeActivity::find($id);
        if (!Gate::allows('permission', $internationalactivity)) {
            return redirect('internationalize_activity');
        }

        $rules = [
            'college'      => 'required|max:11',
            'dept'         => 'required|max:11',
            'activityName' => 'required|max:200',
            'place'        => 'required|max:200',
            'host'         => 'required|max:200',
            'guest'        => 'required|max:200',
            'startDate'    => 'required',
            'endDate'      => 'required',
            'comments'     => 'max:500',
        ];

        $message = [
            'required' => '必須填寫:attribute欄位',
            'max'      => ':attribute欄位的輸入長度不能大於:max',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($request->startDate > $request->endDate) {
            $validator->errors()->add('endDate', '開始時間必須在結束時間前');
            return redirect("internationalize_activity/$id")->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            return redirect("internationalize_activity/$id")->withErrors($validator)->withInput();
        }

        $internationalactivity->update($request->all());
        return redirect('internationalize_activity')->with('success', '更新成功');
    }

    public function delete($id) {
        $internationalactivity = InternationalizeActivity::find($id);
        if (!Gate::allows('permission', $internationalactivity)) {
            return redirect('internationalize_activity');
        }

        $internationalactivity->delete();
        return redirect('internationalize_activity');
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
                    '活動性質'   => 'required|max:200',
                    '活動地點'   => 'required|max:200',
                    '本校參加人員' => 'required|max:200',
                    '參加之外賓'  => 'required|max:200',
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
                        case '活動性質':
                            $item['activityName'] = $value;
                            unset($item[$key]);
                            break;
                        case '活動地點':
                            $item['place'] = $value;
                            unset($item[$key]);
                            break;
                        case '本校參加人員':
                            $item['host'] = $value;
                            unset($item[$key]);
                            break;
                        case '參加之外賓':
                            $item['guest'] = $value;
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
                    return redirect('internationalize_activity')
                        ->withErrors($validator, "upload");
                }
                array_push($newArray, $item);
            }
            InternationalizeActivity::insert($newArray);
        });
        return redirect('internationalize_activity');
    }

    public function example(Request $request) {
        return response()->download(public_path() . '/Excel_example/other/internationalize_activity.xlsx', "國際化活動.xlsx");
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

        $internationalactivity = InternationalizeActivity::join('college_data', function ($join) {
            $join->on('internationalize_activity.college', 'college_data.college');
            $join->on('internationalize_activity.dept', 'college_data.dept');
        });

        if ($user->permission == 2) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college);
        } else if ($user->permission == 3) {
            $internationalactivity = $internationalactivity->where('internationalize_activity.college', $user->college)
                ->where('internationalize_activity.dept', $user->dept);
        }

        $internationalactivity         = $internationalactivity->orderBy($sortBy, $orderBy)->get()->toArray();
        $internationalactivity_array[] = array(/*'索引值',*/ '一級單位', '系所部門', '活動性質', '活動地點', '本校參加人員', '參加之外賓', '開始時間', '結束時間', '備註');
        // dd($internationalactivity);
        foreach ($internationalactivity as $internationalactivity_data) {
            $internationalactivity_array[] = array(
                // 'id'           => $internationalactivity_data['id'],
                'chtCollege'   => $internationalactivity_data['chtCollege'],
                'chtDept'      => $internationalactivity_data['chtDept'],
                'activityName' => $internationalactivity_data['activityName'],
                'place'        => $internationalactivity_data['place'],
                'host'         => $internationalactivity_data['host'],
                'guest'        => $internationalactivity_data['guest'],
                'startDate'    => $internationalactivity_data['startDate'],
                'endDate'      => $internationalactivity_data['endDate'],
                'comments'     => $internationalactivity_data['comments'],
            );
        }

        Excel::create('國際化活動', function ($excel) use ($internationalactivity_array) {
            $excel->setTitle('國際化活動');
            $excel->sheet('表單', function ($sheet) use ($internationalactivity_array) {
                // fromArray(5) parameter:
                //   - source               要輸出的array
                //   - nullValue            array資料內null的呈現方式，預設null
                //   - startCell            資料起始位置，預設A1
                //   - strictNullComparison 預設情況下0會視為空白，若要顯示0則需改成false，預設true
                //   - headingGeneration    表頭是否自動產生。預設為true
                // dd($internationalactivity_array);
                $sheet->fromArray($internationalactivity_array, null, 'A1', false, false);
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
