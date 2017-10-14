<?php
/**
 * Created by PhpStorm.
 * @author: Samir Subašić
 * Date: 12.10.2017
 * Time: 15:13
 */

namespace BOF\Repository;

use BOF\Model\Report;
use BOF\Model\Months;

class ReportRepository
{
    // arrays
    private $profiles;
    private $yearlyReport;
    private $months;

    // array of Report objects - single row in sql is one Report object
    private $listOfReports;

    //get summarized data per year - arrays
    private $UsersYearReport = [];

    // sigle value
    private $report_year;
    private $report_month;

    //class
    private $reportSQLCmd;

    /**
     * ReportRepository constructor.
     */
    public function __construct($db, $report_year, $report_month)
    {
        $this->report_year = $report_year;
        $this->report_month = $report_month;

        $this->reportSQLCmd = new ReportSQLCommand($db, $this->report_year, $this->report_month);
    }

    public function buildYearlyReport($listOfReports) {
        //Get array of months (from class Months)
        $this->months = Months::$MonthList;

        foreach($this->profiles as $profile => $profile_Data) {
            $tempRowYearReport = array();
            $tempRowYearReport[1] = $profile_Data["profile_name"];

            foreach ($this->months as $key => $month) {

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

    // create array of Reports, every entry is stored (info) to Report object
    public function buildProfilesAndYearlyReports() {
        $this->profiles = $this->reportSQLCmd->getProfiles();
        $this->yearlyReport = $this->reportSQLCmd->getAllYearlyReports();
        $this->listOfReports = $this->setReportsCollection();

        // create array of summarized views per month for every user
        $this->buildYearlyReport($this->listOfReports);
    }

    public function insertAndUpdateReportsTableInSQL() {
        // check if summarized users year report list is full
        if (isset($this->UsersYearReport)&& !is_null($this->UsersYearReport)) {
            $this->reportSQLCmd->updateReportsTableSQLCmd($this->UsersYearReport);
        } else {
            $this->buildProfilesAndYearlyReports();
            $this->reportSQLCmd->updateReportsTableSQLCmd($this->UsersYearReport);
        }
    }
}