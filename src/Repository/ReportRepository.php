<?php
/**
 * Created by PhpStorm.
 * User: StormTrooper
 * Date: 12.10.2017
 * Time: 15:13
 */

namespace BOF\Repository;

use BOF\Model\Report;

class ReportRepository
{
    private $yearlyReport;

    //get summarized data per year
    private $UsersYearReport = [];

    /**
     * ReportRepository constructor.
     */
    public function __construct($profiles, $Months, $yearlyReport)
    {
        $this->profiles = $profiles;
        $this->Months = $Months;
        $this->yearlyReport = $yearlyReport;
    }

    public function buildYearlyReport($listOfReports) {
        foreach($this->profiles as $profile => $profile_Data) {
            $tempRowYearReport = array();
            $tempRowYearReport[1] = $profile_Data["profile_name"];

            foreach ($this->Months as $key => $month) {

                $sumTempMonth = 0;
                foreach($listOfReports as $report) {
                    if ($report->getProfileId() == $profile_Data["profile_id"] && date('m', strtotime($report->getDate())) == $month) {
                        $sumTempMonth += $report->getViews();
                    }
                }
                // all years past and future are returning 'n/a' if there is no summarized results
                if ($sumTempMonth > 0) {
                    $tempRowYearReport[$month+1] = number_format($sumTempMonth);
                } else {
                    $tempRowYearReport[$month+1] = 'n/a';
                }
            }
            $this->UsersYearReport[] = $tempRowYearReport;
        }

    }

    public function getYearlyReport() {
        return $this->UsersYearReport;
    }

    public function setReportsCollection() {
        return $this->buildCollection($this->yearlyReport);
    }

    private function buildCollection($items)
    {
        $collection = [];
        if (0 < count($items)) {
            foreach ($items as $item) {
                $collection[] = Report::create(
                    $item['profile_id'],
                    $item['profile_name'],
                    $item['date'],
                    $item['views']
                );
            }
        }
        return $collection;
    }
}