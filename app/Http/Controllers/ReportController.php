<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderModel;
use App\OrderItemsModel;
use CRUDBooster;

class ReportController extends Controller
{
    public function index(Request $req) {
        ini_set('memory_limit', '-1');
        if (CRUDBooster::myId() == "") {
            $url = url(config('crudbooster.ADMIN_PATH').'/login');
            return redirect($url)->with('message', trans('crudbooster.not_logged_in'));
        }
        $script_js = '
            $(function () {
                $(".input_date").datepicker({
                    format: "mm-yyyy",
                    viewMode: "months", 
                    minViewMode: "months",
                    autoclose: true
                });
                $(".open-datetimepicker").click(function () {
                    $(this).next(".input_date").datepicker("show");
                });

            });
        ';
        $params = $req->all();
        $month = date('m');
        $year = date('Y');
        if (!empty($params["date_select"])) {
            $yearMonth = explode("-", $params['date_select']);
            $year = $yearMonth[1] ?? date("Y");
            $month = $yearMonth[0] ?? date("m");
        }
        $month = '02';
        $last_date = date('Y-m-t', strtotime($year.'-'.$month.'-01'));
        $lastday = explode('-', $last_date)[2];
        $orders = new OrderModel();
        $orders = $orders->newQuery();
        $orders = $orders->selectRaw('COUNT(*) as total_order, SUM(grand_total) as sum_grand_total, DATE(created_at) as date_order')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date_order');
        return view('daily_report', compact('params','year','month','orders', 'script_js', 'lastday'));
    }

    public function monthly(Request $req) {
        ini_set('memory_limit', '-1');
        if (CRUDBooster::myId() == "") {
            $url = url(config('crudbooster.ADMIN_PATH').'/login');
            return redirect($url)->with('message', trans('crudbooster.not_logged_in'));
        }
        $script_js = '
            $(function () {
                $(".input_date").datepicker({
                    format: "yyyy",
                    viewMode: "years", 
                    minViewMode: "years",
                    autoclose: true
                });

                $(".open-datetimepicker").click(function () {
                    $(this).next(".input_date").datepicker("show");
                });

            });
        ';
        $params = $req->all();
        $year = date('Y');
        if (!empty($params["year_select"])) {
            $year = $params["year_select"] ?? date("Y");
        }
        $orders = new OrderModel();
        $orders = $orders->newQuery();
        $orders = $orders->selectRaw('COUNT(*) as total_order, SUM(grand_total) as sum_grand_total, MONTH(created_at) as month_order')
            ->whereYear('created_at', $year)
            ->groupBy(\DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month_order');
        return view('monthly_report', compact('params','year','orders', 'script_js'));
    }
}
