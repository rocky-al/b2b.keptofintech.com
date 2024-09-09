<?php

/* Created on: 27-Aug-2021
 * Description: Controller for Employee Manage .
 * Created by: Harshita Tripathi
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables,
    Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MasterAgency;
use App\Models\MasterStandardTime;
use App\Models\MasterLeaveTypes;
use App\Models\MasterCountry;
use App\Models\MasterCity;
use App\Models\MasterState;
use App\Models\EmployeeDefaultLeaveCredit;
use App\Models\EmployeeBaCopies;
use App\Models\MasterCourse;
use App\Models\MasterCourseType;
use App\Models\MasterEmployeePosition;
use App\Models\MasterEmailTemplate;
use App\Models\MasterNotification;
use App\Models\Notification;
use App\Mail\SignUpMail;
use Mail;
use App\Models\MasterCompanySetting;


class EmployeeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {

        $employee_count = Employee::where('is_deleted', '0')->where('is_superadmin', '!=', '1')->count();
        $singaporeans_count = Employee::where('is_deleted', '0')->whereIn('type', array('0', '1'))->count();
        $foreigners_count = Employee::where('is_deleted', '0')->where('type', '2')->count();
        $employee_type = '1';
        $type = 'employee';
        $positions = MasterEmployeePosition::where('delete', '0')->where('status', '1')->pluck('title', 'id');

        return view('employee/list')->with(array('employee_count' => $employee_count, 'singaporeans_count' => $singaporeans_count, 'foreigners_count' => $foreigners_count, 'employee_type' => $employee_type, 'type' => $type, 'positions' => $positions));
    }

    //fecth employee list records
    public function list(Request $request) {
        $search_data = $request->data_search_value;
        $employee_type = $request->employee_type;

        $query = Employee::leftjoin('master_permit_types as mpt', 'users.permit_type', '=', 'mpt.id')
                        ->select('users.*', 'mpt.title as permitTypeName', 'mpt.short_name')->where('is_deleted', '0')->where('is_superadmin', '!=', '1');
        //employee count queries for management and non management
        $employee_count_query = Employee::where('is_deleted', '0')->where('is_superadmin', '!=', '1');
        $employee_count_pop = Employee::where([['is_deleted', '0']])->where('is_superadmin', '!=', '1');
        $staffuser_count = Employee::where([['is_deleted', '0']])->where('is_superadmin', '!=', '1');

        //singaporeans citizen count queries
        $singaporeans_count_query = Employee::where('is_deleted', '0');
        $sc_query = Employee::where('is_deleted', '0');
        $pr_query = Employee::where('is_deleted', '0');

        $foreigners_count_query = Employee::where('is_deleted', '0')->where('type', '2');

        // filter data according to the first_name
        if (isset($search_data['first_name']) && !empty($search_data['first_name'])) {
            $f_name = $search_data['first_name'];
            $query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });

            $employee_count_query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });
            $employee_count_pop->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });
            $staffuser_count->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });

            $singaporeans_count_query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });
            $sc_query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });
            $pr_query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });

            $foreigners_count_query->where(function ($q) use ($f_name) {
                $q->where('first_name', 'LIKE', "%$f_name%")
                        ->orWhere('last_name', 'LIKE', "%$f_name%");
            });
        }

        // filter data according to the address 
        if (isset($search_data['address']) && !empty($search_data['address'])) {
            $query->where('address', 'LIKE', "%{$search_data['address']}%");
            $employee_count_query->where('address', 'LIKE', "%{$search_data['address']}%");
            $employee_count_pop->where('address', 'LIKE', "%{$search_data['address']}%");
            $staffuser_count->where('address', 'LIKE', "%{$search_data['address']}%");
            $singaporeans_count_query->where('address', 'LIKE', "%{$search_data['address']}%");
            $sc_query->where('address', 'LIKE', "%{$search_data['address']}%");
            $pr_query->where('address', 'LIKE', "%{$search_data['address']}%");
            $foreigners_count_query->where('address', 'LIKE', "%{$search_data['address']}%");
        }


        // filter data according to the phone
        if (isset($search_data['phone']) && !empty($search_data['phone'])) {
            $query->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $employee_count_query->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $employee_count_pop->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $staffuser_count->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $singaporeans_count_query->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $sc_query->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $pr_query->where('phone', 'LIKE', "%{$search_data['phone']}%");
            $foreigners_count_query->where('phone', 'LIKE', "%{$search_data['phone']}%");
        }

        // filter data according to the status
        if (isset($search_data['status']) && $search_data['status'] != '') {

            $query->where('status', '=', $search_data['status']);
            $employee_count_query->where('status', '=', $search_data['status']);
            $employee_count_pop->where('status', '=', $search_data['status']);
            $staffuser_count->where('status', '=', $search_data['status']);
            $singaporeans_count_query->where('status', '=', $search_data['status']);
            $sc_query->where('status', '=', $search_data['status']);
            $pr_query->where('status', '=', $search_data['status']);
            $foreigners_count_query->where('status', '=', $search_data['status']);
        }

        // filter data according to the position
        if (isset($search_data['position']) && $search_data['position'] != '') {
            $query->where('position', '=', $search_data['position']);
            $employee_count_query->where('position', '=', $search_data['position']);
            $employee_count_pop->where('position', '=', $search_data['position']);
            $staffuser_count->where('position', '=', $search_data['position']);
            $singaporeans_count_query->where('position', '=', $search_data['position']);
            $sc_query->where('position', '=', $search_data['position']);
            $pr_query->where('position', '=', $search_data['position']);
            $foreigners_count_query->where('position', '=', $search_data['position']);
        }

        // filter data according to the hire date 
        if (isset($search_data['start_date']) && !empty($search_data['start_date']) && isset($search_data['end_date']) && !empty($search_data['end_date'])) {
            $start_date = date('Y-m-d', strtotime($search_data['start_date']));
            $end_date = date('Y-m-d', strtotime($search_data['end_date']));
            $query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $employee_count_query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $employee_count_pop->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $staffuser_count->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $singaporeans_count_query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $sc_query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $pr_query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
            $foreigners_count_query->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
        }

        if (isset($search_data['employee_type']) && $search_data['employee_type'] != '') {
            $query->where('employee_type', '=', $search_data['employee_type']);
            $employee_count_query->where('employee_type', '=', $search_data['employee_type']);
            $singaporeans_count_query->where('employee_type', '=', $search_data['employee_type']);
            $sc_query->where('employee_type', '=', $search_data['employee_type']);
            $pr_query->where('employee_type', '=', $search_data['employee_type']);
            $foreigners_count_query->where('employee_type', '=', $search_data['employee_type']);

            if ($search_data['employee_type'] == '0') {
                $staffuser = $staffuser_count->where('employee_type', '=', $search_data['employee_type'])->count();
                $emp_count = 0;
            } else {
                $emp_count = $employee_count_pop->where('employee_type', '=', '1')->count();
                $staffuser = 0;
            }
        } else {

            $emp_count = $employee_count_pop->where('employee_type', '=', '1')->count();
            $staffuser = $staffuser_count->where('employee_type', '=', '0')->count();
        }

        // filter data according to the type
        if (isset($search_data['type']) && $search_data['type'] != '') {
            $query->where('type', '=', $search_data['type']);
            $employee_count_query->where('type', '=', $search_data['type']);
            $employee_count_pop->where('type', '=', $search_data['type']);
            $staffuser_count->where('type', '=', $search_data['type']);
            $singaporeans_count_query->where('type', '=', $search_data['type']);
            $foreigners_count_query->where('type', '=', $search_data['type']);

            if ($search_data['type'] == '1') {
                $pr_count = $pr_query->where('type', '=', $search_data['type'])->count();
                $sc_count = 0;
            } else {
                $sc_count = $sc_query->where('type', '=', $search_data['type'])->count();
                $pr_count = 0;
            }
        } else {
            $singaporeans_count_query->whereIn('type', array('0', '1'));
            $pr_count = $pr_query->where('type', '=', '1')->count();
            $sc_count = $sc_query->where('type', '=', '0')->count();
        }

        $data = $query->get();
        $employee_count = $employee_count_query->count();
        $singaporeans_count = $singaporeans_count_query->count();
        $foreigners_count = $foreigners_count_query->count();

        $employee_count_popover = '<div><b>Management:</b> ' . $staffuser . ' </div><div><b>Non Management:</b> ' . $emp_count . ' </div>';
        $singaporeans_count_popover = '<div><b>Singaporean Citizen:</b> ' . $sc_count . '</div><div><b>Permanent Resident:</b> ' . $pr_count . '</div>';
        $foreigners_count_popover = $this->get_employee_count_acc_permit_type($search_data);

        return Datatables::of($data, $employee_count, $singaporeans_count, $foreigners_count, $employee_count_popover, $foreigners_count_popover, $singaporeans_count_popover)
                        ->addIndexColumn()
                        ->addColumn('type', function ($data) {

                            //check for employee type and return type name
                            if ($data->type == '0') {
                                return "<p data-toggle='tooltip' title='Singaporean Citizen' data-placement='left'>SC</p>";
                            } else if ($data->type == '1') {
                                return "<p data-toggle='tooltip' title='Permanent Resident' data-placement='left'>PR</p>";
                            } else {
                                return "<p data-toggle='tooltip' title='" . $data->permitTypeName . "' data-placement='left'>" . $data->short_name . "</p>";
                            }
                        })
                        ->addColumn('position', function ($data) {

                            if ($data->employee_type == '0') {
                                $user_role = $data->roles->first();
                                if (isset($user_role) && !empty($user_role)) {
                                    return $user_role->name;
                                }
                                return '';
                            } else {
                                if (!empty($data->position)) {
                                    $position = MasterEmployeePosition::where('delete', '0')->where('status', '1')->find($data->position);
                                    if (isset($position)) {
                                        return $position->title;
                                    }
                                    return '';
                                }
                            }

                            return '';
                        })
                        ->addColumn('first_name', function ($data) {
                            $html = '';

                            if ($data->employee_type == '1') {

                                $html = '<a href="' . url('employee/employee/view/' . $data->id) . '" data-toggle="tooltip" title="View" class="anchor-link">' . $data->first_name . ' ' . $data->last_name . '</a>';
                            }

                            if ($data->employee_type == '0') {
                                $html = '<a href="' . url('employee/staff/view/' . $data->id) . '" data-toggle="tooltip" title="View" class="anchor-link">' . $data->first_name . ' ' . $data->last_name . '</a>';
                            }
                            return $html;
                        })
                        ->addColumn('status', function ($data) {

                            //check for status and return status name
                            if ($data->status == '0') {
                                return 'Inactive';
                            } elseif ($data->status == '1') {
                                return 'Active';
                            } elseif ($data->status == '2') {
                                return 'Terminated';
                            } elseif ($data->status == '3') {
                                return 'Resigned';
                            } else {
                                return '';
                            }
                        })
                        ->addColumn('phone', function ($data) {

                            $phone = substr($data->phone, 0, 4) . ' ' . substr($data->phone, 4, 4); // 123

                            return $phone;
                        })
                        ->addColumn('action', function ($data) {

                            if ($data->id == '1') {
                                return '';
                            } elseif ((Auth::user()->can('delete_employee') || Auth::user()->can('view_employee'))) {
                                $html = '<div class="table-actions">';

                                if ($data->employee_type == '1') {
                                    //check employee view permission
                                    if (Auth::user()->can('view_employee')) {
                                        $html .= '<a href="' . url('employee/employee/view/' . $data->id) . '" data-toggle="tooltip" title="View"><i class="ik ik-eye f-16 mr-15 text-green"></i></a>';
                                    }
                                    //check delete permission
                                    if (Auth::user()->can('delete_employee')) {
                                        $html .= '<a href="javascript:void(0)" onclick="deleteItem(' . $data->id . ')" data-toggle="tooltip" title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>';
                                    }
                                } else {
                                    //check employee view permission
                                    if (Auth::user()->can('view_employee')) {
                                        $html .= '<a href="' . url('employee/staff/view/' . $data->id) . '" data-toggle="tooltip" title="View"><i class="ik ik-eye f-16 mr-15 text-green"></i></a>';
                                    }
                                    //check delete permission
                                    if (Auth::user()->can('delete_employee')) {
                                        $html .= '<a href="javascript:void(0)" onclick="deleteItem(' . $data->id . ')" data-toggle="tooltip" title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>';
                                    }
                                }

                                $html .= '</div>';

                                return $html;
                            } else {
                                return '';
                            }
                        })
                        ->rawColumns(['type', 'action', 'position', 'status', 'first_name'])
                        ->with(array('employee_count' => $employee_count, 'singaporeans_count' => $singaporeans_count, 'foreigners_count' => $foreigners_count, 'employee_count_popover' => $employee_count_popover, 'singaporeans_count_popover' => $singaporeans_count_popover, 'foreigners_count_popover' => $foreigners_count_popover))
                        ->make(true);
    }

    //fetch employee count acroding to permit type
    public function get_employee_count_acc_permit_type($search_data) {
        $html = '';
        $permitTypes = MasterPermitTypes::where([['status', '1'], ['delete', '0']])->get();

        if (count($permitTypes) > 0) {
            foreach ($permitTypes as $permitType) {
                $emp_count = Employee::where([['is_deleted', '0'], ['permit_type', $permitType->id]]);

                // filter data according to the first_name
                if (isset($search_data['first_name']) && !empty($search_data['first_name'])) {
                    $f_name = $search_data['first_name'];
                    $emp_count->where(function ($q) use ($f_name) {
                        $q->where('first_name', 'LIKE', "%$f_name%")
                                ->orWhere('last_name', 'LIKE', "%$f_name%");
                    });
                }
                // filter data according to the address 
                if (isset($search_data['address']) && !empty($search_data['address'])) {
                    $emp_count->where('address', 'LIKE', "%{$search_data['address']}%");
                }

                // filter data according to the phone
                if (isset($search_data['phone']) && !empty($search_data['phone'])) {
                    $emp_count->where('phone', 'LIKE', "%{$search_data['phone']}%");
                }

                // filter data according to the status
                if (isset($search_data['status']) && $search_data['status'] != '') {
                    $emp_count->where('status', '=', $search_data['status']);
                }

                // filter data according to the position
                if (isset($search_data['position']) && $search_data['position'] != '') {
                    $emp_count->where('position', '=', $search_data['position']);
                }

                // filter data according to the hire date 
                if (isset($search_data['start_date']) && !empty($search_data['start_date']) && isset($search_data['end_date']) && !empty($search_data['end_date'])) {
                    $start_date = date('Y-m-d', strtotime($search_data['start_date']));
                    $end_date = date('Y-m-d', strtotime($search_data['end_date']));
                    $emp_count->whereBetween(DB::raw("(STR_TO_DATE(hire_date,'%Y-%m-%d'))"), [$start_date, $end_date]);
                }
                if (isset($search_data['employee_type']) && $search_data['employee_type'] != '') {
                    $emp_count->where('employee_type', '=', $search_data['employee_type']);
                }
                // filter data according to the type
                if (isset($search_data['type']) && $search_data['type'] != '') {
                    $emp_count->where('type', '=', $search_data['type']);
                }
                $html .= '<div><b>' . $permitType->title . ': </b>' . $emp_count->count() . '</div>';
            }
        }
        return $html;
    }

    //display add employee form
    public function create(Request $request) {
        
    }

    //add new  employee data
    public function store(Request $request) {
        $rules = [
            'type' => 'required ',
            'first_name' => 'required | string ',
            'last_name' => 'required | string ',
            'dob' => 'required ',
            'standard_time' => 'required ',
            'hire_date' => 'required',
            'profile_image' => 'mimes:jpeg,png,jpg',
            'nric_copy' => 'mimes:jpeg,png,jpg',
            'bank_account_copy' => 'mimes:jpeg,png,jpg,pdf',
            'email' => 'required|email|unique:users,email,1,is_deleted',
            'document' => 'required',
            'salary' => 'required',
//          'ba_copy[]' => 'mimes:jpeg,png,jpg',
//          'passport_copy[]' => 'mimes:jpeg,png,jpg',
        ];
        if (isset($request->document)) {
            for ($z = 0; $z < count($request->document); $z++) {

                if ($request->document[$z] == '0') {
                    $rules['nric_no'] = 'required';
                }
                if ($request->document[$z] == '1') {
                    $rules['passport_no'] = 'required';
                    $rules['expiry_date'] = 'required';
                }
            }
        }
        if ($request->type == '2') {
            $rules['permit_type'] = 'required';
        }

        // create employee 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            //return redirect()->back()->withInput()->with('error', $validator->messages());
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }

        try {
            // store employee information
            $user = new Employee;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = str_replace(' ', '', $request->phone);
            $user->dob = date_time_format($request->dob, 'Y-m-d');
            $user->standard_time = $request->standard_time;
            $user->hire_date = date_time_format($request->hire_date, 'Y-m-d');
            $user->status = $request->status;
            $user->type = $request->type;
            $user->agency = $request->select_agency;
            $user->nationality = $request->nationality;
            $user->permit_type = $request->permit_type;
            $user->address = $request->address;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->country = 1;
            $user->pin = $request->zipcode;
            $user->gender = $request->gender;
            $user->bank_name = $request->bank_name;
            $user->bank_account_no = $request->bank_account_no;
            $user->passport_no = $request->passport_no;
            $user->passport_expiry_date = (isset($request->expiry_date) && !empty($request->expiry_date)) ? date_time_format($request->expiry_date, 'Y-m-d') : null;
            $user->nric_no = $request->nric_no;
            $user->probation_period_type = $request->probation_period_type;
            $user->probation_period = $request->probation_period;
            $user->leave_scheme = $request->leave_scheme;
            $user->is_deleted = '0';
            $user->status = '1';
            $user->employee_type = $request->employee_type;

            if ($request->employee_type == '1') {
                $user->position = $request->position;
            } else {
                $user->position = null;
            }

            if ($user->employee_type == '0') {
                $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
                $user->password = Hash::make($pass);
            }

            if (isset($request->probation_period_type) && !empty($request->probation_period_type) && $request->probation_period_type == '1') {
                if (!empty($request->date_range) && $request->date_range != "-") {

                    list($startDate, $endDate) = explode('-', $request->date_range);
                    $user->probation_start_date = date_time_format($startDate, 'Y-m-d');
                    $user->probation_end_date = date_time_format($endDate, 'Y-m-d');
                }
            }


            //if profile image set then upload
            if ($profile_image = $request->file('profile_image')) {
                $destination_path = 'employee_images';
                $source_path = $profile_image;
                $file_name = upload_file($destination_path, $source_path);
                $user->profile_image = $file_name;
            }

            //if nric copy set then upload
            if ($nric_copy_image = $request->file('nric_copy')) {
//                    $destinationPath = public_path('employee_images');
//                    $profileImage = date('YmdHis') . "." . $nric_copy_image->getClientOriginalExtension();
//                    $nric_copy_image->move($destinationPath, $profileImage);
                $destination_path = 'employee_images';
                $source_path = $nric_copy_image;
                $file_name = upload_file($destination_path, $source_path);
                $user->nric_copy = $file_name;
            }

            //store passport copy
            if (isset($request->passport_copy_name) && !empty($request->passport_copy_name)) {
                $user->passport_copy = json_encode($request->passport_copy_name);
            }

            $user->save();

            // assign new role to the user
            if ($user->employee_type == '0') {
                $user->syncRoles($request->role);
            }

            if ($user) {

                if ($request->employee_type == '1') {
                    $type = "employee";
                } else {
                    $type = "staff";
                }

                $leaves = MasterLeaveTypes::where('delete', '0')->where('status', '1')->where('is_deductable', '!=', '1')->get();

                foreach ($leaves as $leave) {
                    $name = 'leave_default_' . $leave->id;
                    $defaultLeave = new EmployeeDefaultLeaveCredit;
                    $defaultLeave->employee_id = $user->id;
                    $defaultLeave->leave_type = $leave->id;
                    $defaultLeave->no_of_leave = $request->$name;
                    $defaultLeave->save();
                }

                //store ba copy
                if (isset($request->ba_copy_name) && !empty($request->ba_copy_name)) {
                    $count_ba = count($request->ba_copy_name);
                    $ba_copy_image = $request->ba_copy_name;

                    for ($x = 0; $x < $count_ba; $x++) {
                        $baCopy = new EmployeeBaCopies;
                        $baCopy->employee_id = $user->id;
                        $baCopy->file = $ba_copy_image[$x];
                        $baCopy->save();
                    }
                }
                

                //check for employee type and send mail
                if ($user->employee_type == '0') {

                    $master_email = MasterEmailTemplate::find('1');
                    $emailval = $master_email->description;
                    $subject = $master_email->title;
                    $email = $request->email;

                    $company = MasterCompanySetting::first();
                    $logo = 'https://vjroot.orbitnapp.com/company_logo/' . $company->company_logo;
                    $employee = [
                        '@name' => $request->first_name . ' ' . $request->last_name,
                        '@email' => $email,
                        '@password' => $pass,
                        '@company' => $company->company_name,
                        '@logo' => $logo,
                    ];

//              Mail::to($email)->send(new SignUpMail($employee));

                    foreach ($employee as $key => $value) {
                        $emailval = str_replace($key, $value, $emailval);
                    }

                    Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                        $message->to($email)
                                ->subject($subject)
                                ->setBody($emailval, 'text/html');
                    });
                }

                //return redirect('employees')->with('success', 'New Employee created!');
                $res = array('code' => 200, 'msg' => config('constants.add_employee'), 'emp_type' => $type);
            } else {
                //return redirect('employees')->with('error', 'Failed to create new employee! Try again.');
                $res = array('code' => 201, 'msg' => config('constants.failed_message'));
            }
        } catch (\Exception $e) {

            $bug = $e->getMessage();
            //return redirect()->back()->with('error', $bug);
            //$res = array('code' => 201, 'msg' => $bug);
            $res = array('code' => 201, 'msg' => $bug . config('constants.went_wrong_msg'));
        }
        return json_encode($res);
    }

    // display user view page 
    public function edit($id, Request $request) {

        $pro_type = $request->segment(2);
        try {
            $user = Employee::select('users.*')->with('roles', 'permissions')->find($id);
            //dd($user);
            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::where('id', '!=', 1)->pluck('name', 'id');
                $role_name = '';
                if (isset($user_role) && !empty($user_role)) {
                    $role_name = $user_role->name;
                }
                if ($pro_type == 'profile') {
                    return view('employee/profile', compact('user', 'role_name', 'roles'));
                } else {
                    return view('employee/view', compact('user', 'role_name', 'roles'));
                }
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    //delete employee 
    public function update_status(Request $request) {
        // set validation rule
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'type' => 'required',
                    'value' => 'required'
        ]);

        //check validation
        if ($validator->fails()) {
            $res = array('code' => 201, 'msg' => 'All fields required');
            return json_encode($res);
        }
        try {

            $user = Employee::find($request->id);
            $user->is_deleted = '1';
            $user->email = 'delete_' . $user->email;
            //check for employee status is active or not 
            if ($user->status == '1') {
                $user->status = '0';
                $user->termination_resign_date = date('Y-m-d');
            }
            $user->save();

            if ($user) {
                $res = array('code' => 200, 'msg' => config('constants.delete_message'));
            } else {
                $res = array('code' => 201, 'msg' => config('constants.failed_message'));
            }
        } catch (\Exception $e) {
            $res = array('code' => 201, 'msg' => config('constants.went_wrong_msg'));
        }


        return json_encode($res);
    }

    //get states option according to company id 
    public function get_state(Request $request) {

        $option = '<option value="">Select State</option>';
        if ($request->country_id != '') {
            $states = MasterState::where('status', '1')->where('delete', '0')->where('country', $request->country_id)->get();

            if (!empty($states)) {
                foreach ($states as $state) {

                    $option .= '<option value="' . $state->id . '">' . $state->title . '</option>';
                }
            }
        }
        return $option;
    }

    //get city options according to state id 
    public function get_city(Request $request) {
        if ($request->state_id != '') {
            $cities = MasterCity::where('status', '1')->where('delete', '0')->where('state', $request->state_id)->get();
            $option = '<option value="">Select City</option>';
            if (!empty($cities)) {
                foreach ($cities as $city) {

                    $option .= '<option value="' . $city->id . '">' . $city->title . '</option>';
                }
            }

            return $option;
        }
    }

    //update  employee data
    public function update(Request $request) {

        $check_password = $request->segment(2);
        //echo $check_password; die;
        //check for request is for password
        if ($check_password == 'password') {
            // set validation rules 
            $validator = Validator::make($request->all(), [
                        'old_password' => 'required ',
                        'new_password' => 'required_with:confirm_password|same:confirm_password',
            ]);
        } elseif ($check_password == 'profile') { //check for request is from profile
            // set validation rules 
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required | string ',
                        'last_name' => 'required | string ',
                        //'dob' => 'required ',
                        'profile_image' => 'mimes:jpeg,png,jpg',
                        'id' => 'required',
                        'email' => 'required|unique:users,email,' . $request->id . ',id'
            ]);
        } else { //else request is from employee module
            // set validation rules 
            $rules = [
                'type' => 'required ',
                'first_name' => 'required | string ',
                'last_name' => 'required | string ',
                'dob' => 'required ',
                'standard_time' => 'required ',
                'hire_date' => 'required|date_format:d/m/Y|after_or_equal:dob',
                'profile_image' => 'mimes:jpeg,png,jpg',
                'nric_copy' => 'mimes:jpeg,png,jpg',
                'bank_account_copy' => 'mimes:jpeg,png,jpg,pdf',
                'id' => 'required',
                'status' => 'required',
                'email' => 'required|email|unique:users,email,' . $request->id . ',id,is_deleted,0',
                'document' => 'required',
            ];
            //validaion rule for document field
            if (isset($request->document)) {
                for ($z = 0; $z < count($request->document); $z++) {

                    if ($request->document[$z] == '0') {
                        $rules['nric_no'] = 'required';
                    }
                    if ($request->document[$z] == '1') {
                        $rules['passport_no'] = 'required';
                        $rules['expiry_date'] = 'required';
                    }
                }
            }
            //if employee is Foreigner 
            if ($request->type == '2') {
                $rules['permit_type'] = 'required';
            }
            //check for save from 2nd tab or first tab
            if($request->Save == "Update"){
                $rules['salary'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
        }

        //check validation
        if ($validator->fails()) {
            //return redirect()->back()->withInput()->with('error', $validator->messages());
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }

        try {
            // store employee information
            $user = Employee::leftjoin('user_has_roles', 'users.id', '=', 'user_has_roles.employee_id')->find($request->id);

            //check for request is for password
            if ($check_password == 'password') {
                //check for employee type
                //if($user->employee_type == '0'){

                if (Hash::check($request->old_password, $user->password)) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                   
                } else {
                    $res = array('code' => 201, 'msg' => "Old password doesn't match");
                    return json_encode($res);
                }

                //}
            }
            //if request is from profile
            if ($check_password == 'profile') {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->phone = str_replace(' ', '', $request->phone);

                //if profile image set then upload
                if ($profile_image = $request->file('profile_image')) {
                    $destination_path = 'employee_images';
                    $source_path = $profile_image;
                    $file_name = upload_file($destination_path, $source_path);
                    $user->profile_image = $file_name;
                }

                $user->save();
            }

            //check for request is for password
            if ($check_password != 'password' && $check_password != 'profile') {
                $old_data = $user->toArray();
                // $user->roles = $user->roles->first()->toArray();

                if ($user->email != $request->email) {
                    $flag = 1;
                } else {
                    $flag = 0;
                }

                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->phone = str_replace(' ', '', $request->phone);
                $user->dob = date_time_format($request->dob, 'Y-m-d');
                $user->standard_time = $request->standard_time;
                $user->hire_date = date_time_format($request->hire_date, 'Y-m-d');
                $user->status = $request->status;
                $user->type = $request->type;
                $user->agency = $request->select_agency;
                $user->nationality = $request->nationality;
                $user->permit_type = $request->permit_type;
                $user->address = $request->address;
                $user->state = $request->state;
                $user->city = $request->city;
                $user->country = 1;
                $user->pin = $request->zipcode;
                $user->gender = $request->gender;
                $user->position = $request->position;
                $user->passport_expiry_date = (isset($request->expiry_date) && !empty($request->expiry_date)) ? date_time_format($request->expiry_date, 'Y-m-d') : null;
                $user->bank_name = $request->bank_name;
                $user->bank_account_no = $request->bank_account_no;
                $user->passport_no = $request->passport_no;
                $user->nric_no = $request->nric_no;
                $user->probation_period_type = $request->probation_period_type;
                $user->probation_period = $request->probation_period;
                $user->is_deleted = '0';
                $user->status = $request->status;
                //check for save from 2nd tab or first tab
                if($request->Save == 'Update'){
                $user->leave_scheme = $request->leave_scheme;
                }
                //if password is set or not
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                    $pass = $request->password;
                } else {
                    if ($flag == 1) {
                        $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
                        $user->password = Hash::make($pass);
                    }
                }

                if ($request->status == '2') {
                    if (!empty($request->termination_resign_date)) {
                        $user->termination_resign_date = date_time_format($request->termination_resign_date, 'Y-m-d');
                    } else {
                        $user->termination_resign_date = date('Y-m-d');
                    }
                    $user->termination_resign_letter = $request->termination_notice_period;
                }

                if ($request->status == '3') {

                    if (!empty($request->termination_resign_date)) {
                        $user->termination_resign_date = date_time_format($request->termination_resign_date, 'Y-m-d');
                    } else {
                        $user->termination_resign_date = date('Y-m-d');
                    }

                    if ($letter = $request->file('termination_resign_letter')) {
                        $destination_path = 'employee_images';
                        $source_path = $letter;
                        $file_name = upload_file($destination_path, $source_path);
                        $user->termination_resign_letter = $file_name;
                    }
                }

                if ($request->status == '0') {
                    $user->termination_resign_date = date('Y-m-d');
                }
                //check for probation preiod type
                if (isset($request->probation_period_type) && !empty($request->probation_period_type) && $request->probation_period_type == '1') {
                    //if date range is set
                    if (!empty($request->date_range) && $request->date_range != "-") {

                        list($startDate, $endDate) = explode('-', $request->date_range);

                        $user->probation_start_date = date_time_format($startDate, 'Y-m-d');
                        $user->probation_end_date = date_time_format($endDate, 'Y-m-d');
                    }
                }


                //if profile image set then upload
                if ($profile_image = $request->file('profile_image')) {
                    $destination_path = 'employee_images';
                    $source_path = $profile_image;
                    $file_name = upload_file($destination_path, $source_path);
                    $user->profile_image = $file_name;
                }

                //if nric copy set then upload
                if ($nric_copy_image = $request->file('nric_copy')) {
                    $destination_path = 'employee_images';
                    $source_path = $nric_copy_image;
                    $file_name = upload_file($destination_path, $source_path);
                    $user->nric_copy = $file_name;
                }

                //store passport copy
                if (isset($request->passport_copy_name) && !empty($request->passport_copy_name)) {
                    $user->passport_copy = json_encode($request->passport_copy_name);
                }
                $user->save();
            }



            //check for request is for password
            if ($check_password != 'password' && $check_password != 'profile') {
                //for save new date of user
                $new_data = $user->toArray();
                // assign new role to the user
                if ($user->employee_type == '0') {
                    $user->syncRoles($request->role);
                }
                //dd($new_data);
            }

            if ($user) {
                //check for request is for password
                if ($check_password != 'password' && $check_password != 'profile') {
                    //EmployeeDefaultLeaveCredit::where('employee_id', $user->id)->delete();
                   if($request->Save == 'Update'){
                    $arr_leave_id = array();
                    
                    $employeeDefaultLeaveCredits = EmployeeDefaultLeaveCredit::where('employee_id', $user->id)->get();
                    $leaves = MasterLeaveTypes::select('leave as no_of_leave', 'id', 'title')->where('delete', '0')->where('status', '1')->where('is_deductable', '!=', '1')->get();
                    
                    if(count($employeeDefaultLeaveCredits) > 0){
                     foreach($employeeDefaultLeaveCredits as $employeeDefaultLeaveCredit){
                          array_push($arr_leave_id,$employeeDefaultLeaveCredit->leave_type);
                     }
                    }
                     
                    foreach ($leaves as $leave) {
                    
                       if(in_array($leave->id,$arr_leave_id))
                       {  
                        $name = 'leave_default_' . $leave->id;
                        $defaultLeave = EmployeeDefaultLeaveCredit::where([['employee_id',$user->id],['leave_type',$leave->id]])->first();
                        if(!empty($defaultLeave)){
                            $defaultLeave->employee_id = $user->id;
                            $defaultLeave->leave_type = $leave->id;
                            $defaultLeave->no_of_leave = $request->$name;
                            $defaultLeave->save(); 
                        }else{
                            $defaultLeave = new EmployeeDefaultLeaveCredit;
                            $defaultLeave->employee_id = $user->id;
                            $defaultLeave->leave_type = $leave->id;
                            $defaultLeave->no_of_leave = $request->$name;
                            $defaultLeave->save(); 
                        }
                      
                       }else{
                        $name = 'leave_default_' . $leave->id;
                        $defaultLeave = new EmployeeDefaultLeaveCredit;
                        $defaultLeave->employee_id = $user->id;
                        $defaultLeave->leave_type = $leave->id;
                        $defaultLeave->no_of_leave = $request->$name;
                        $defaultLeave->save();
                       }   
                    }
                   }

                    //store ba copy
                    if (isset($request->ba_copy_name) && !empty($request->ba_copy_name)) {
                        $count_ba = count($request->ba_copy_name);
                        $ba_copy_image = $request->ba_copy_name;

                        for ($x = 0; $x < $count_ba; $x++) {
                            $baCopy = new EmployeeBaCopies;
                            $baCopy->employee_id = $user->id;
                            $baCopy->file = $ba_copy_image[$x];
                            $baCopy->save();
                        }
                    }
                    

                    //check for employee type and send mail
                    if ($user->employee_type == '0') {
                        //check for email send or not 
                        if ($flag == 1 || !empty($request->password)) {

                            $master_email = MasterEmailTemplate::find('1');
                            $emailval = $master_email->description;
                            $subject = $master_email->title;
                            $email = $request->email;

                            $company = MasterCompanySetting::first();
                            $logo = 'https://vjroot.orbitnapp.com/company_logo/' . $company->company_logo;
                            $employee = [
                                '@name' => $user->first_name . ' ' . $user->last_name,
                                '@email' => $email,
                                '@password' => $pass,
                                '@company' => $company->company_name,
                                '@logo' => $logo,
                            ];

//              Mail::to($email)->send(new SignUpMail($employee));

                            foreach ($employee as $key => $value) {
                                $emailval = str_replace($key, $value, $emailval);
                            }

                            Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                                $message->to($email)
                                        ->subject($subject)
                                        ->setBody($emailval, 'text/html');
                            });
                        }
                    }
                }

                //return redirect('employees')->with('success', 'New Employee created!');
                if ($check_password != 'password') {
                    $res = array('code' => 200, 'msg' => config('constants.update_employee'));
                } else {
                    $res = array('code' => 200, 'msg' => config('constants.password_update'));
                }
            } else {
                //return redirect('employees')->with('error', 'Failed to create new employee! Try again.');
                $res = array('code' => 201, 'msg' => config('constants.failed_message'));
            }
        } catch (\Exception $e) {

            $bug = $e->getMessage();
            //return redirect()->back()->with('error', $bug);
            $res = array('code' => 201, 'msg' => '111' . $bug . config('constants.went_wrong_msg'));
            //$res = array('code' => 201, 'msg' => $bug);
        }
        return json_encode($res);
    }

    //uploadImages
    public function uploadImages(Request $request) {

        try {

            if ($request->hasfile('files')) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                $count_ba = count($request->file('files'));
                $images = $request->file('files');

                $data_image = array();
                $data_image_name = array();

                for ($x = 0; $x < $count_ba; $x++) {
                    $destination_path = 'employee_images';
                    $source_path = $images[$x];
                    $extension = $images[$x]->getClientOriginalExtension();
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $image = upload_file($destination_path, $source_path);
                        $data_image[] = get_file_url($image);
                        $data_image_name[] = $image;
                    } else {
                        $res = array('code' => 201, 'msg' => 'Only formats are allowed : JPG,JPEG,PNG');
                        return json_encode($res);
                    }
                }
                $res = array('code' => 200, 'images' => $data_image, 'image_name' => $data_image_name);
            }
        } catch (Exception $ex) {
            $res = array('code' => 201, 'msg' => config('constants.went_wrong_msg'));
        }

        return json_encode($res);
    }

    //remove ba copy image
    public function baCopyImageRemove(Request $request) {
        // set validation rules 
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        //check validation
        if ($validator->fails()) {
            //return redirect()->back()->withInput()->with('error', $validator->messages());
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }

        try {

            $item = EmployeeBaCopies::find($request->id);
            $item->delete();
            $res = array('code' => 200, 'msg' => config('constants.delete_message'));
        } catch (\Exception $e) {
            $res = array('code' => 201, 'msg' => config('constants.went_wrong_msg'));
        }

        return json_encode($res);
    }

    //For employee history ...comparing array 
    public function employee_fields_update_check($old_data, $new_data, $new_salary, $old_salary) {
        $data = "";

        //employee data difference from users
        $result = array_diff($old_data, $new_data);

        //employee salary data difference from employee_salary_info
        $employee_salary_diff = array_diff($old_salary, $new_salary);

        $json_data = array();

        //check for difference is empty or not
        if (!empty($result)) {
            //loop for differnce value
            foreach ($result as $key => $val) {
                if ($key != 'updated_at' && $key != 'status') {
                    $json_data[$key]['old'] = $old_data[$key];
                    $json_data[$key]['new'] = $new_data[$key];
                }
            }
        }

        //check for status field
        if ($old_data['status'] != $new_data['status']) {
            $json_data['status']['old'] = config('constants.employee_status.' . $old_data['status']);
            $json_data['status']['new'] = config('constants.employee_status.' . $new_data['status']);
        }

        if (!empty($employee_salary_diff)) {
            //loop for differnce value
            foreach ($employee_salary_diff as $key => $val_salary) {
                if ($key != 'updated_at') {
                    $json_data[$key]['old'] = $old_salary[$key];
                    $json_data[$key]['new'] = $new_salary[$key];
                }
            }
        }


        if (!empty($json_data)) {
            $data = json_encode($json_data);
        }

        return $data;
    }

    //change password
    public function change_password($id) {
        return view('employee/change_password')->with(array('id' => $id));
    }

    //uploadImages
    public function profile_image_update(Request $request) {
        //dd($request->hasfile('files')); die;
        // set validation rules 
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'files' => 'required|mimes:jpeg,png,jpg'
        ]);

        //check validation
        if ($validator->fails()) {
            //return redirect()->back()->withInput()->with('error', $validator->messages());
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }
        try {
            $user = Employee::find($request->id);

            if ($request->hasfile('files')) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png'];
                $images = $request->file('files');
                $destination_path = 'employee_images';
                $source_path = $images;
                $extension = $images->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $image = upload_file($destination_path, $source_path);
                    $user->profile_image = $image;
                    $user->save();
                } else {
                    $res = array('code' => 201, 'msg' => 'Only formats are allowed : JPG,JPEG,PNG');
                    return json_encode($res);
                }
            }
            $res = array('code' => 200, 'msg' => 'Profile Image Updated Successfully');
        } catch (Exception $ex) {
            $res = array('code' => 201, 'msg' => config('constants.went_wrong_msg'));
        }

        return json_encode($res);
    }

}
