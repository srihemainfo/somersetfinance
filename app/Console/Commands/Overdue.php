<?php

namespace App\Console\Commands;

use App\Models\BookIssueModel;
use App\Models\BookReservationModel;
use App\Models\UserAlert;
use DB;
use Illuminate\Console\Command;

class Overdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdue:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'book issue overdue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $check = BookIssueModel::all();
        if ($check != null) {

            $data = BookIssueModel::where(function ($query) {
                $query->where('status', 'On Loan')
                    ->orWhere('status', 'OverDue');
            })
                ->whereDate('due_date', '<', $date)
                ->update([
                    'fine' => DB::raw('fine + 1'),
                    'status' => 'Overdue'
                ]);
        }

        $user_id = DB::table('book_reservation')
            ->where('book_reservation.status', 0)
            ->leftJoin('book_data', 'book_reservation.book_id', '=', 'book_data.book_id')
            ->leftJoin('book_details', 'book_reservation.book_id', '=', 'book_details.id')
            ->groupBy('book_reservation.user_name_id', 'book_details.name')
            ->select('book_details.name', 'book_reservation.user_name_id', DB::raw('SUM(CASE WHEN book_data.availability = "Yes" THEN 1 ELSE 0 END) as availability_count'))
            ->get();


        foreach ($user_id as $key => $user) {
            // dd('1' > 0);
            if ($user->availability_count > 0) {
                $userAlert = new UserAlert;
                $userAlert->alert_text = $user->name . ' book is ready for you to pick up';
                $userAlert->save();
                $userAlert->users()->sync($user->user_name_id);
                // $userAlert->alert_link = url('admin/student-request-leaves/index/' . $student->user_name_id . '/' . $student->name);
            }
        }

        \Log::info("cron overdue update");

    }
}
