<?php

namespace Customize\Twig\Extension;

use Customize\Repository\BusinessPlanRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EccubeExtension extends AbstractExtension
{
    /**
     * @var BusinessPlanRepository
     */
    protected $businessPlanRepository;

    public function __construct(
        BusinessPlanRepository $businessPlanRepository
    ) {
        $this->businessPlanRepository = $businessPlanRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_report_breakdown', [$this, 'getReportBreakdown']),
        ];
    }

    /**
     * テンプレートファイル(.twig)から呼び出し可能にする
     */
    public function getReportBreakdown($row){

        // 契約情報を取得 -------------------------------------------------------------------
        $BusinessPlan = $this->businessPlanRepository->getActivePlan([
            'contractor_id' => $row->getBusiness()->getContractorId()   // 契約対象(法人/個人)
            ,'kind_id' => $row->getBusiness()->getKindId() // 契約種類(定期/単発)
        ]);
        // 時給を取得
        $business_plan_bill_basic = $BusinessPlan->getBasicTimeBill();
        $business_plan_bill_outside = $BusinessPlan->getBillOutside();
        // 基本時間と時間外を算出 ------------------------------------------------------------
        $worktime_outside = 0;
        // 開始時間
        if ( strstr($row->getWorkingTimeStart(), ':', true) >= $BusinessPlan->getBasicTimeStart()
          && strstr($row->getWorkingTimeStart(), ':', true) <= $BusinessPlan->getBasicTimeEnd()
        ){
            // 基本時間内
            $time1 = strtotime(date("Y/m/d ". $row->getWorkingTimeStart(). ":00"));
        }else{
            // 基本時間外
            $time1 = strtotime(date("Y/m/d ". $BusinessPlan->getBasicTimeStart(). ":00:00"));
            $work_s = strtotime(date("Y/m/d ". $row->getWorkingTimeStart(). ":00"));
            // 時間外を算出
            $start_diff_s = $time1 - $work_s;
            $start_diff_m = $start_diff_s / 60; // 秒→分
            $worktime_outside += $start_diff_m / 60;
        }
        // 終了時間
        if ( strstr($row->getWorkingTimeEnd(), ':', true) >= $BusinessPlan->getBasicTimeStart()
          && strstr($row->getWorkingTimeEnd(), ':', true) <= $BusinessPlan->getBasicTimeEnd()
        ){
            // 基本時間内
            $time2 = strtotime(date("Y/m/d ". $row->getWorkingTimeEnd(). ":00"));
        }else{
            // 基本時間外
            $time2 = strtotime(date("Y/m/d ". $BusinessPlan->getBasicTimeEnd(). ":00:00"));
            $work_e = strtotime(date("Y/m/d ". $row->getWorkingTimeEnd(). ":00"));
            // 時間外を算出
            $end_diff_s = $work_e - $time2;
            $end_diff_m = $end_diff_s / 60; // 秒→分
            $worktime_outside += $end_diff_m / 60;
        }

        // 労働時間の算出
        $diff_s = $time2 - $time1;
        $diff_m = $diff_s / 60; // 秒→分
        if( $row->getWorkingTimeBreak() != null && $row->getWorkingTimeBreak() > 0 ){
            $diff_m = $diff_m - $row->getWorkingTimeBreak();
        }
        $workingTime = $diff_m / 60;
        $hours = floor($diff_m / 60);
        $minutes = floor($diff_m % 60);
        $worktime_basic = $workingTime;
        // 時間外
        $over_hours = floor($worktime_outside);
        $over_minutes = floor(($worktime_outside * 60) % 60);
        // 合計
        $worktime_total = $worktime_basic + $worktime_outside;
        $total_hours = floor($worktime_total);
        $total_minutes = floor(($worktime_total * 60) % 60);

        // 給与を算出
        $working_amout_basic = $worktime_basic * $business_plan_bill_basic;
        $working_amout_outside = $worktime_outside * $business_plan_bill_outside;

        // 合計金額を算出（合計＋交通費）
        $amout_total = $working_amout_basic + $working_amout_outside + $row['fare'];

        $result['plan']['contractor'] = $BusinessPlan->getContractorId();
        $result['plan']['kind'] = $BusinessPlan->getKindId();
        $result['working']['basic'] = sprintf("%02d:%02d", $hours, $minutes);
        $result['working']['overtime'] = sprintf("%02d:%02d", $over_hours, $over_minutes);
        $result['working']['total'] = sprintf("%02d:%02d", $total_hours , $total_minutes);
        $result['amout']['basic'] = $working_amout_basic;
        $result['amout']['overtime'] = $working_amout_outside;
        $result['amout']['total'] = $amout_total;

        return $result;
    }
}
