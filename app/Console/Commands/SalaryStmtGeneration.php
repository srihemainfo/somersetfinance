<?php

namespace App\Console\Commands;

use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\salarystatement;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SalaryStmtGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salarystatement:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Salary Statement Generation For Staffs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $month = Carbon::now()->format('n');
        $monthName = date('F', strtotime("2000-$month-01"));

        if ($month < 10) {
            $persent_month = '0' . $month;
        } else {
            $persent_month = $month;
        }
        $year = Carbon::now()->format('Y');

        $day_array = [];

        $previousMonth = Carbon::createFromDate($year, $persent_month, 26)->subMonth();

        if ($previousMonth->month < 10) {
            $previousmonth = '0' . $previousMonth->month;
        } else {
            $previousmonth = $previousMonth->month;
        }

        if ($month == '01') {
            $previousYear = (int) $year - 1;
        } else {
            $previousYear = $year;
        }

        $previousMonthEnd = Carbon::createFromDate($year, $month, 1)->subMonth()->endOfMonth();

        for ($date = $previousMonth; $date->lte($previousMonthEnd); $date->addDay()) {

            $dayOfWeek = $date->format('l');

            array_push($day_array, [$date->toDateString(), $dayOfWeek]);

        }

        $startDate = Carbon::createFromDate($year, $persent_month, 1);

        $endDate = Carbon::createFromDate($year, $persent_month, 25);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            $dayOfWeek = $date->format('l');

            array_push($day_array, [$date->toDateString(), $dayOfWeek]);

        }
        $teaching_staff = TeachingStaff::get();
        $non_teaching_staff = NonTeachingStaff::get();

        foreach ($teaching_staff as $staff) {

            $query = StaffBiometric::where('staff_code', $staff->StaffCode)
                ->whereBetween('date', [$previousYear . '-' . $previousmonth . '-26', $year . '-' . $month . '-25'])
                ->get();

            if (!$query->count() <= 0) {

                $attend_rep = $query;

                for ($a = 0; $a < count($attend_rep); $a++) {
                    if ($attend_rep[$a]->shift != '' || $attend_rep[$a]->shift != null) {
                        $shift = $attend_rep[$a]->shift;
                        break;
                    } else {
                        $shift = '';
                    }
                }


                $leave = 0;
                $too_late = 0;
                $half_day_leave = 0;
                if ($attend_rep != '') {
                    $len = count($attend_rep);
                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            if ($attend_rep[$i]->details == "Fore Noon Casual Leave" || $attend_rep[$i]->details == "After Noon Casual Leave") {
                                $half_day_leave += 0.5;
                            }
                        }

                        for ($j = 0; $j < $len; $j++) {
                            //Casual Leave
                            if ($attend_rep[$j]->day != 'Sunday' && $attend_rep[$j]->status == 'Absent' && (($attend_rep[$j]->details != 'Holiday' && $attend_rep[$j]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j]->details != 'Admin OD' && $attend_rep[$j]->details != 'Exam OD' && $attend_rep[$j]->details != 'Training OD' && $attend_rep[$j]->details != 'Compensation Leave' && $attend_rep[$j]->details != 'Winter Vacation' && $attend_rep[$j]->details != 'Summer Vacation') && ($attend_rep[$j]->details == 'Casual Leave' || $attend_rep[$j]->details == ''))) {
                                $leave++;
                            }

                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == 'Present') && ($attend_rep[$j]->details == 'Too Late' || $attend_rep[$j]->details == 'Too Late & Early Out')) {
                                $too_late += 0.5;
                            }
                            //Sunday
                            $temStatus = false;
                            if ($attend_rep[$j]->day == 'Sunday' && $attend_rep[$j]->status == 'Absent' && $attend_rep[$j]->details != 'Holiday' && $attend_rep[$j]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j]->details != 'Admin OD' && $attend_rep[$j]->details != 'Exam OD' && $attend_rep[$j]->details != 'Training OD' && $attend_rep[$j]->details != 'Compensation Leave' && $attend_rep[$j]->details != 'Winter Vacation' && $attend_rep[$j]->details != 'Summer Vacation' && $attend_rep[$j]->details != 'Too Late' && $attend_rep[$j]->details != 'Too Late & Early Out' && $attend_rep[$j]->details != 'Casual Leave') {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && $attend_rep[$j - 1]->status == 'Absent' && $attend_rep[$j - 1]->details != 'Holiday' && $attend_rep[$j - 1]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j - 1]->details != 'Admin OD' && $attend_rep[$j - 1]->details != 'Exam OD' && $attend_rep[$j - 1]->details != 'Training OD' && $attend_rep[$j - 1]->details != 'Compensation Leave' && $attend_rep[$j - 1]->details != 'Winter Vacation' && $attend_rep[$j - 1]->details != 'Summer Vacation') {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if ($attend_rep[$j - 1]->details == 'Holiday') {
                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$k]->status == 'Absent' && $attend_rep[$k]->details != 'Holiday' && $attend_rep[$k]->details != 'Casual Leave (CL Provided)' && $attend_rep[$k]->details != 'Admin OD' && $attend_rep[$k]->details != 'Exam OD' && $attend_rep[$k]->details != 'Training OD' && $attend_rep[$k]->details != 'Compensation Leave' && $attend_rep[$k]->details != 'Winter Vacation' && $attend_rep[$k]->details != 'Summer Vacation') {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        if ($temStatus == true) {
                                            break;
                                        }
                                    }
                                }
                            }

                            //Holiday
                            $temStatus = false;
                            if ($attend_rep[$j]->day != 'Sunday' && $attend_rep[$j]->status == 'Absent' && $attend_rep[$j]->details == 'Holiday') {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && $attend_rep[$j - 1]->status == 'Absent' && $attend_rep[$j - 1]->details != 'Holiday' && $attend_rep[$j - 1]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j - 1]->details != 'Admin OD' && $attend_rep[$j - 1]->details != 'Exam OD' && $attend_rep[$j - 1]->details != 'Training OD' && $attend_rep[$j - 1]->details != 'Compensation Leave' && $attend_rep[$j - 1]->details != 'Winter Vacation' && $attend_rep[$j - 1]->details != 'Summer Vacation') {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if ($attend_rep[$j - 1]->details == 'Holiday' || $attend_rep[$j - 1]->day == 'Sunday') {

                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$k]->status == 'Absent' && $attend_rep[$k]->details != 'Holiday' && $attend_rep[$k]->details != 'Casual Leave (CL Provided)' && $attend_rep[$k]->details != 'Admin OD' && $attend_rep[$k]->details != 'Exam OD' && $attend_rep[$k]->details != 'Training OD' && $attend_rep[$k]->details != 'Compensation Leave' && $attend_rep[$k]->details != 'Winter Vacation' && $attend_rep[$k]->details != 'Summer Vacation') {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                                if ($temStatus == true) {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }
                }

                $doj_query = PersonalDetail::where('user_name_id', $staff->user_name_id)->get();
                if ($doj_query) {
                    if (!$doj_query->count() <= 0) {

                        $doj = $doj_query[0]->DOJ;

                    } else {

                        $doj = null;

                    }
                } else {
                    $doj = null;
                }

                $late = 0;
                $permission_shift_1 = 0;
                $permission_shift_2 = 0;

                foreach ($attend_rep as $day) {
                    if ($attendance = $day) {
                        if ($attendance->details == 'Late' || $attendance->details == 'Early Out' || $attendance->details == 'Late & Early Out') {
                            $late++;
                        }

                        if ($attendance->shift == '1') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_1 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_1++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_1++;
                            }
                        } elseif ($attendance->shift == '2') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_2 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_2++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_2++;
                            }
                        }

                    }
                }
                if ($late > 3) {
                    $late_lop = 0.5;
                } else {
                    $late_lop = 0;
                }

                $m_total_paid_days = count($day_array) - ($leave + $too_late + $late_lop);

                $m_total_working_days = count($day_array);

                $m_leave = $leave + $late_lop + $too_late;

                $salary = $staff;
                // Basic Pay Calculation
                if (isset($salary->basicPay) && !empty($salary->basicPay && !is_nan($salary->basicPay))) {
                    $m_per_day_basic_pay = $salary->basicPay / $m_total_working_days;

                    $m_half_day_basic_pay = $m_per_day_basic_pay / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 9) * ($permission_shift_2 - 2);
                    } else {
                        $basic_pay_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_basic_pay = $late - 3;

                        $late_deduct_basic_pay = $m_half_day_basic_pay * $deduct_basic_pay;
                    } else {
                        $late_deduct_basic_pay = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_basic_pay = $m_half_day_basic_pay * $too_late;
                    } else {
                        $too_late_deduct_basic_pay = 0;
                    }

                    $m_basic_pay = round($salary->basicPay * ($m_total_paid_days / $m_total_working_days) - ($basic_pay_permis_deduct + $late_deduct_basic_pay + $too_late_deduct_basic_pay), 2);
                    $m_basic_pay_loss = $salary->basicPay - $m_basic_pay;
                } else {
                    $m_basic_pay = 0;
                    $m_basic_pay_loss = 0;
                }

                // AGP Calculation
                if (isset($salary->agp) && !empty($salary->agp && !is_nan($salary->agp))) {
                    $m_per_day_agp = $salary->agp / $m_total_working_days;

                    $m_half_day_agp = $m_per_day_agp / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 9) * ($permission_shift_2 - 2);
                    } else {
                        $agp_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_agp = $late - 3;

                        $late_deduct_agp = $m_half_day_agp * $deduct_agp;
                    } else {
                        $late_deduct_agp = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_agp = $m_half_day_agp * $too_late;
                    } else {
                        $too_late_deduct_agp = 0;
                    }

                    $m_agp = round($salary->agp * ($m_total_paid_days / $m_total_working_days) - ($agp_permis_deduct + $late_deduct_agp + $too_late_deduct_agp), 2);
                    $m_agp_loss = $salary->agp - $m_agp;
                } else {
                    $m_agp = 0;
                    $m_agp_loss = 0;
                }

                // DA Calculation
                $m_da = round(($m_basic_pay + $m_agp) * 0.55, 2);
                $m_da_loss = $salary->da - $m_da;

                // HRA Calculation
                if ($salary->hra == '' || $salary->hra == null) {
                    $salary_hra = 0;
                } else {
                    $salary_hra = $salary->hra;
                }
                $m_hra = round(($m_agp + $m_da) * ($salary_hra / 100), 2);

                $m_hra_loss = $salary->hra_amount - $m_hra;

                // SpecialFee Calculation
                if (isset($salary->specialFee) && !empty($salary->specialFee && !is_nan($salary->specialFee))) {
                    $m_per_day_specialFee = $salary->specialFee / $m_total_working_days;

                    $m_half_day_specialFee = $m_per_day_specialFee / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 9) * ($permission_shift_2 - 2);
                    } else {
                        $specialFee_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_specialFee = $late - 3;

                        $late_deduct_specialFee = $m_half_day_specialFee * $deduct_specialFee;
                    } else {
                        $late_deduct_specialFee = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_specialFee = $m_half_day_specialFee * $too_late;
                    } else {
                        $too_late_deduct_specialFee = 0;
                    }

                    $m_specialFee = round($salary->specialFee * ($m_total_paid_days / $m_total_working_days) - ($specialFee_permis_deduct + $late_deduct_specialFee + $too_late_deduct_specialFee), 2);
                    $m_specialFee_loss = $salary->specialFee - $m_specialFee;
                } else {
                    $m_specialFee = 0;
                    $m_specialFee_loss = 0;
                }

                // Phd Allowance Calculation
                if (isset($salary->phdAllowance) && !empty($salary->phdAllowance && !is_nan($salary->phdAllowance))) {
                    $m_per_day_phdAllowance = $salary->phdAllowance / $m_total_working_days;

                    $m_half_day_phdAllowance = $m_per_day_phdAllowance / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 9) * ($permission_shift_2 - 2);
                    } else {
                        $phdAllowance_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_phdAllowance = $late - 3;

                        $late_deduct_phdAllowance = $m_half_day_phdAllowance * $deduct_phdAllowance;
                    } else {
                        $late_deduct_phdAllowance = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_phdAllowance = $m_half_day_phdAllowance * $too_late;
                    } else {
                        $too_late_deduct_phdAllowance = 0;
                    }

                    $m_phdAllowance = round($salary->phdAllowance * ($m_total_paid_days / $m_total_working_days) - ($phdAllowance_permis_deduct + $late_deduct_phdAllowance + $too_late_deduct_phdAllowance), 2);
                    $m_phdAllowance_loss = $salary->phdAllowance - $m_phdAllowance;
                } else {
                    $m_phdAllowance = 0;
                    $m_phdAllowance_loss = 0;
                }

                // Other Allowance Calculation
                if (isset($salary->otherAllowence) && !empty($salary->otherAllowence && !is_nan($salary->otherAllowence))) {
                    $m_per_day_otherAllowence = $salary->otherAllowence / $m_total_working_days;

                    $m_half_day_otherAllowence = $m_per_day_otherAllowence / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 9) * ($permission_shift_2 - 2);
                    } else {
                        $otherAllowence_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_otherAllowence = $late - 3;

                        $late_deduct_otherAllowence = $m_half_day_otherAllowence * $deduct_otherAllowence;
                    } else {
                        $late_deduct_otherAllowence = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_otherAllowence = $m_half_day_otherAllowence * $too_late;
                    } else {
                        $too_late_deduct_otherAllowence = 0;
                    }

                    $m_otherAllowence = round($salary->otherAllowence * ($m_total_paid_days / $m_total_working_days) - ($otherAllowence_permis_deduct + $late_deduct_otherAllowence + $too_late_deduct_otherAllowence), 2);
                    $m_otherAllowence_loss = $salary->otherAllowence - $m_otherAllowence;
                } else {
                    $m_otherAllowence = 0;
                    $m_otherAllowence_loss = 0;
                }

                $deduction = round($m_basic_pay_loss + $m_agp_loss + $m_da_loss + $m_hra_loss + $m_specialFee_loss + $m_phdAllowance_loss + $m_otherAllowence_loss, 2);
                $gross_salary = round($m_basic_pay + $m_agp + $m_da + $m_hra + $m_specialFee + $m_phdAllowance + $m_otherAllowence, 2);
                $net_salary = round($gross_salary - $deduction, 2);
                if ($net_salary <= 0) {
                    $net_salary = 0;
                }

                $checker = salarystatement::where(['user_name_id' => $staff->user_name_id, 'month' => $monthName, 'year' => $year])->get();

                if (count($checker) <= 0) {
                    $salary_statement = new salarystatement;
                    $salary_statement->basicpay = $m_basic_pay;
                    $salary_statement->agp = $m_agp;
                    $salary_statement->da = $m_da;
                    $salary_statement->hra = $m_hra;
                    $salary_statement->specialpay = $m_specialFee;
                    $salary_statement->phdallowance = $m_phdAllowance;
                    $salary_statement->otherall = $m_otherAllowence;
                    $salary_statement->gross_salary = $gross_salary;
                    $salary_statement->netpay = $net_salary;
                    $salary_statement->lop = $deduction;
                    $salary_statement->month = $monthName;
                    $salary_statement->year = $year;
                    $salary_statement->department = $staff->Dept;
                    $salary_statement->name = $staff->name;
                    $salary_statement->user_name_id = $staff->user_name_id;
                    $salary_statement->doj = $doj;
                    $salary_statement->total_working_days = $m_total_working_days;
                    $salary_statement->total_payable_days = $m_total_paid_days;
                    $salary_statement->total_lop_days = $m_leave;
                    $salary_statement->save();
                }

            }
        }

        foreach ($non_teaching_staff as $staff) {

            $query = StaffBiometric::where('staff_code', $staff->StaffCode)
                ->whereBetween('date', [$previousYear . '-' . $previousmonth . '-26', $year . '-' . $month . '-25'])
                ->get();

            if (!$query->count() <= 0) {

                $attend_rep = $query;

                for ($a = 0; $a < count($attend_rep); $a++) {
                    if ($attend_rep[$a]->shift != '' || $attend_rep[$a]->shift != null) {
                        $shift = $attend_rep[$a]->shift;
                        break;
                    } else {
                        $shift = '';
                    }
                }

                $doj_query = PersonalDetail::where('user_name_id', $staff->user_name_id)->get();
                if ($doj_query) {
                    if (!$doj_query->count() <= 0) {

                        $doj = $doj_query[0]->DOJ;

                    } else {

                        $doj = null;

                    }
                } else {
                    $doj = null;
                }

                $leave = 0;
                $too_late = 0;
                $half_day_leave = 0;
                if ($attend_rep != '') {
                    $len = count($attend_rep);
                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            if ($attend_rep[$i]->details == "Fore Noon Casual Leave" || $attend_rep[$i]->details == "After Noon Casual Leave") {
                                $half_day_leave += 0.5;
                            }
                        }

                        for ($j = 0; $j < $len; $j++) {
                            //Casual Leave
                            if ($attend_rep[$j]->day != 'Sunday' && $attend_rep[$j]->status == 'Absent' && (($attend_rep[$j]->details != 'Holiday' && $attend_rep[$j]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j]->details != 'Admin OD' && $attend_rep[$j]->details != 'Exam OD' && $attend_rep[$j]->details != 'Training OD' && $attend_rep[$j]->details != 'Compensation Leave' && $attend_rep[$j]->details != 'Winter Vacation' && $attend_rep[$j]->details != 'Summer Vacation') && ($attend_rep[$j]->details == 'Casual Leave' || $attend_rep[$j]->details == ''))) {
                                $leave++;
                            }

                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == 'Present') && ($attend_rep[$j]->details == 'Too Late' || $attend_rep[$j]->details == 'Too Late & Early Out')) {
                                $too_late += 0.5;
                            }
                            //Sunday
                            $temStatus = false;
                            if ($attend_rep[$j]->day == 'Sunday' && $attend_rep[$j]->status == 'Absent' && $attend_rep[$j]->details != 'Holiday' && $attend_rep[$j]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j]->details != 'Admin OD' && $attend_rep[$j]->details != 'Exam OD' && $attend_rep[$j]->details != 'Training OD' && $attend_rep[$j]->details != 'Compensation Leave' && $attend_rep[$j]->details != 'Winter Vacation' && $attend_rep[$j]->details != 'Summer Vacation' && $attend_rep[$j]->details != 'Too Late' && $attend_rep[$j]->details != 'Too Late & Early Out' && $attend_rep[$j]->details != 'Casual Leave') {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && $attend_rep[$j - 1]->status == 'Absent' && $attend_rep[$j - 1]->details != 'Holiday' && $attend_rep[$j - 1]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j - 1]->details != 'Admin OD' && $attend_rep[$j - 1]->details != 'Exam OD' && $attend_rep[$j - 1]->details != 'Training OD' && $attend_rep[$j - 1]->details != 'Compensation Leave' && $attend_rep[$j - 1]->details != 'Winter Vacation' && $attend_rep[$j - 1]->details != 'Summer Vacation') {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if ($attend_rep[$j - 1]->details == 'Holiday') {
                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$k]->status == 'Absent' && $attend_rep[$k]->details != 'Holiday' && $attend_rep[$k]->details != 'Casual Leave (CL Provided)' && $attend_rep[$k]->details != 'Admin OD' && $attend_rep[$k]->details != 'Exam OD' && $attend_rep[$k]->details != 'Training OD' && $attend_rep[$k]->details != 'Compensation Leave' && $attend_rep[$k]->details != 'Winter Vacation' && $attend_rep[$k]->details != 'Summer Vacation') {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        if ($temStatus == true) {
                                            break;
                                        }
                                    }
                                }
                            }

                            //Holiday
                            $temStatus = false;
                            if ($attend_rep[$j]->day != 'Sunday' && $attend_rep[$j]->status == 'Absent' && $attend_rep[$j]->details == 'Holiday') {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && $attend_rep[$j - 1]->status == 'Absent' && $attend_rep[$j - 1]->details != 'Holiday' && $attend_rep[$j - 1]->details != 'Casual Leave (CL Provided)' && $attend_rep[$j - 1]->details != 'Admin OD' && $attend_rep[$j - 1]->details != 'Exam OD' && $attend_rep[$j - 1]->details != 'Training OD' && $attend_rep[$j - 1]->details != 'Compensation Leave' && $attend_rep[$j - 1]->details != 'Winter Vacation' && $attend_rep[$j - 1]->details != 'Summer Vacation') {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if ($attend_rep[$j - 1]->details == 'Holiday' || $attend_rep[$j - 1]->day == 'Sunday') {

                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif ($attend_rep[$k]->status == 'Absent' && $attend_rep[$k]->details != 'Holiday' && $attend_rep[$k]->details != 'Casual Leave (CL Provided)' && $attend_rep[$k]->details != 'Admin OD' && $attend_rep[$k]->details != 'Exam OD' && $attend_rep[$k]->details != 'Training OD' && $attend_rep[$k]->details != 'Compensation Leave' && $attend_rep[$k]->details != 'Winter Vacation' && $attend_rep[$k]->details != 'Summer Vacation') {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif ($attend_rep[$m]->status == 'Absent' && $attend_rep[$m]->details != 'Holiday' && $attend_rep[$m]->details != 'Casual Leave (CL Provided)' && $attend_rep[$m]->details != 'Admin OD' && $attend_rep[$m]->details != 'Exam OD' && $attend_rep[$m]->details != 'Training OD' && $attend_rep[$m]->details != 'Compensation Leave' && $attend_rep[$m]->details != 'Winter Vacation' && $attend_rep[$m]->details != 'Summer Vacation') {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                                if ($temStatus == true) {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }
                }



                $late = 0;
                $permission_shift_1 = 0;
                $permission_shift_2 = 0;

                foreach ($attend_rep as $day) {
                    if ($attendance = $day) {
                        if ($attendance->details == 'Late' || $attendance->details == 'Early Out' || $attendance->details == 'Late & Early Out') {
                            $late++;
                        }


                        if ($attendance->shift == '1') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_1 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_1++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_1++;
                            }
                        } elseif ($attendance->shift == '2') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_2 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_2++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_2++;
                            }
                        }

                    }
                }

                if ($late > 3) {
                    $late_lop = 0.5;
                } else {
                    $late_lop = 0;
                }

                $m_total_paid_days = count($day_array) - ($leave + $too_late + $late_lop);

                $m_total_working_days = count($day_array);

                $m_leave = $leave + $late_lop + $too_late;

                $salary = $staff;

                // Basic Pay Calculation
                if (isset($salary->basicPay) && !empty($salary->basicPay && !is_nan($salary->basicPay))) {
                    $m_per_day_basic_pay = $salary->basicPay / $m_total_working_days;

                    $m_half_day_basic_pay = $m_per_day_basic_pay / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 9) * ($permission_shift_2 - 2);
                    } else {
                        $basic_pay_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_basic_pay = $late - 3;

                        $late_deduct_basic_pay = $m_half_day_basic_pay * $deduct_basic_pay;
                    } else {
                        $late_deduct_basic_pay = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_basic_pay = $m_half_day_basic_pay * $too_late;
                    } else {
                        $too_late_deduct_basic_pay = 0;
                    }

                    $m_basic_pay = round($salary->basicPay * ($m_total_paid_days / $m_total_working_days) - ($basic_pay_permis_deduct + $late_deduct_basic_pay + $too_late_deduct_basic_pay), 2);
                    $m_basic_pay_loss = $salary->basicPay - $m_basic_pay;
                } else {
                    $m_basic_pay = 0;
                    $m_basic_pay_loss = 0;
                }

                // AGP Calculation
                if (isset($salary->agp) && !empty($salary->agp && !is_nan($salary->agp))) {
                    $m_per_day_agp = $salary->agp / $m_total_working_days;

                    $m_half_day_agp = $m_per_day_agp / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 9) * ($permission_shift_2 - 2);
                    } else {
                        $agp_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_agp = $late - 3;

                        $late_deduct_agp = $m_half_day_agp * $deduct_agp;
                    } else {
                        $late_deduct_agp = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_agp = $m_half_day_agp * $too_late;
                    } else {
                        $too_late_deduct_agp = 0;
                    }

                    $m_agp = round($salary->agp * ($m_total_paid_days / $m_total_working_days) - ($agp_permis_deduct + $late_deduct_agp + $too_late_deduct_agp), 2);
                    $m_agp_loss = $salary->agp - $m_agp;
                } else {
                    $m_agp = 0;
                    $m_agp_loss = 0;
                }

                // DA Calculation
                $m_da = round(($m_basic_pay + $m_agp) * 0.55, 2);
                $m_da_loss = $salary->da - $m_da;

                // HRA Calculation
                if ($salary->hra == '' || $salary->hra == null) {
                    $salary_hra = 0;
                } else {
                    $salary_hra = $salary->hra;
                }
                $m_hra = round(($m_agp + $m_da) * ($salary_hra / 100), 2);

                $m_hra_loss = $salary->hra_amount - $m_hra;

                // SpecialFee Calculation
                if (isset($salary->specialFee) && !empty($salary->specialFee && !is_nan($salary->specialFee))) {
                    $m_per_day_specialFee = $salary->specialFee / $m_total_working_days;

                    $m_half_day_specialFee = $m_per_day_specialFee / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 9) * ($permission_shift_2 - 2);
                    } else {
                        $specialFee_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_specialFee = $late - 3;

                        $late_deduct_specialFee = $m_half_day_specialFee * $deduct_specialFee;
                    } else {
                        $late_deduct_specialFee = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_specialFee = $m_half_day_specialFee * $too_late;
                    } else {
                        $too_late_deduct_specialFee = 0;
                    }

                    $m_specialFee = round($salary->specialFee * ($m_total_paid_days / $m_total_working_days) - ($specialFee_permis_deduct + $late_deduct_specialFee + $too_late_deduct_specialFee), 2);
                    $m_specialFee_loss = $salary->specialFee - $m_specialFee;
                } else {
                    $m_specialFee = 0;
                    $m_specialFee_loss = 0;
                }

                // Phd Allowance Calculation
                if (isset($salary->phdAllowance) && !empty($salary->phdAllowance && !is_nan($salary->phdAllowance))) {
                    $m_per_day_phdAllowance = $salary->phdAllowance / $m_total_working_days;

                    $m_half_day_phdAllowance = $m_per_day_phdAllowance / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 9) * ($permission_shift_2 - 2);
                    } else {
                        $phdAllowance_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_phdAllowance = $late - 3;

                        $late_deduct_phdAllowance = $m_half_day_phdAllowance * $deduct_phdAllowance;
                    } else {
                        $late_deduct_phdAllowance = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_phdAllowance = $m_half_day_phdAllowance * $too_late;
                    } else {
                        $too_late_deduct_phdAllowance = 0;
                    }

                    $m_phdAllowance = round($salary->phdAllowance * ($m_total_paid_days / $m_total_working_days) - ($phdAllowance_permis_deduct + $late_deduct_phdAllowance + $too_late_deduct_phdAllowance), 2);
                    $m_phdAllowance_loss = $salary->phdAllowance - $m_phdAllowance;
                } else {
                    $m_phdAllowance = 0;
                    $m_phdAllowance_loss = 0;
                }

                // Other Allowance Calculation
                if (isset($salary->otherAllowence) && !empty($salary->otherAllowence && !is_nan($salary->otherAllowence))) {
                    $m_per_day_otherAllowence = $salary->otherAllowence / $m_total_working_days;

                    $m_half_day_otherAllowence = $m_per_day_otherAllowence / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 9) * ($permission_shift_2 - 2);
                    } else {
                        $otherAllowence_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_otherAllowence = $late - 3;

                        $late_deduct_otherAllowence = $m_half_day_otherAllowence * $deduct_otherAllowence;
                    } else {
                        $late_deduct_otherAllowence = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_otherAllowence = $m_half_day_otherAllowence * $too_late;
                    } else {
                        $too_late_deduct_otherAllowence = 0;
                    }

                    $m_otherAllowence = round($salary->otherAllowence * ($m_total_paid_days / $m_total_working_days) - ($otherAllowence_permis_deduct + $late_deduct_otherAllowence + $too_late_deduct_otherAllowence), 2);
                    $m_otherAllowence_loss = $salary->otherAllowence - $m_otherAllowence;
                } else {
                    $m_otherAllowence = 0;
                    $m_otherAllowence_loss = 0;
                }

                $deduction = round($m_basic_pay_loss + $m_agp_loss + $m_da_loss + $m_hra_loss + $m_specialFee_loss + $m_phdAllowance_loss + $m_otherAllowence_loss, 2);
                $gross_salary = round($m_basic_pay + $m_agp + $m_da + $m_hra + $m_specialFee + $m_phdAllowance + $m_otherAllowence, 2);
                $net_salary = round($gross_salary - $deduction, 2);
                if ($net_salary <= 0) {
                    $net_salary = 0;
                }

                $checker = salarystatement::where(['user_name_id' => $staff->user_name_id, 'month' => $monthName, 'year' => $year])->get();

                if (count($checker) <= 0) {
                    $salary_statement = new salarystatement;
                    $salary_statement->basicpay = $m_basic_pay;
                    $salary_statement->agp = $m_agp;
                    $salary_statement->da = $m_da;
                    $salary_statement->hra = $m_hra;
                    $salary_statement->specialpay = $m_specialFee;
                    $salary_statement->phdallowance = $m_phdAllowance;
                    $salary_statement->otherall = $m_otherAllowence;
                    $salary_statement->gross_salary = $salary->gross_salary;
                    $salary_statement->earnings = $gross_salary;
                    $salary_statement->netpay = $net_salary;
                    $salary_statement->lop = $deduction;
                    $salary_statement->month = $monthName;
                    $salary_statement->year = $year;
                    $salary_statement->department = $staff->Dept;
                    $salary_statement->name = $staff->name;
                    $salary_statement->user_name_id = $staff->user_name_id;
                    $salary_statement->doj = $doj;
                    $salary_statement->total_working_days = $m_total_working_days;
                    $salary_statement->total_payable_days = $m_total_paid_days;
                    $salary_statement->total_lop_days = $m_leave;
                    $salary_statement->save();
                }

            }
        }

        \Log::info("Salary Statement Generated");
    }
}
