<?php
/**
 * Created by PhpStorm.
 * User: StormTrooper
 * Date: 12.10.2017
 * Time: 15:14
 */

namespace BOF\Command;
use BOF\Model\Months;
use BOF\Repository\ReportRepository;

/**
 * Class ReportSQLCommand
 * @package BOF\Command
 */
class ReportSQLCommand
{
    private $db;
    private $display_to;
    private $report_year;
    private $report_month;

    /**
     * ReportSQLCommand constructor.
     */
    public function __construct($db, $display_to, $report_year, $report_month)
    {
        $this->db = $db;
        $this->display_to = $display_to;
        $this->report_year = $report_year;
        $this->report_month = $report_month;
    }

    // security reason. Cannot directly expose SQL executing method, use of proxy public method
    public function execute($whichCmd = 'GetData', $reportsArray = NULL){
        switch ($whichCmd) {
            case 'GetData':
                return $this->getDataSQLCmd();
                break;
            case 'UpdateReports':
                return $this->updateReportsTableSQLCmd($reportsArray);
                break;
        }
    }

    private function getDataSQLCmd()
    {
        //get array of users IDs and their Names sorted alphabeticaly
        $profiles = $this->db->query('SELECT p.* FROM profiles p ORDER BY p.profile_name;')->fetchAll();

        //Get array of months (from class Months)
        //$Months = \BOF\Model\Months::$MonthList;
        $Months = Months::$MonthList;

        // set sql statement
        $sqlString = sprintf("SELECT v.profile_id, p.profile_name, v.date, v.views FROM views v 
                      LEFT JOIN profiles p ON v.profile_id = p.profile_id 
                      WHERE YEAR(Date)=%s", $this->report_year);

        // let's see if we have defined month for monthly report, add month to sql statement
        if (!is_null($this->report_month) && isset($this->report_month)){
            $sqlString .= sprintf(" AND MONTH(Date)=%s", $Months[$this->report_month]);
        }

        // alphabetical order
        $sqlString .= " ORDER BY p.profile_name";

        // execute sql
        $yearlyReportFromSQL = $this->db->query($sqlString)->fetchAll();

        $createReports = new ReportRepository($profiles, $Months, $yearlyReportFromSQL);
        $listOfReports = $createReports->setReportsCollection();


        $createReports->buildYearlyReport($listOfReports);
        return $createReports->getYearlyReport();
    }

    private function updateReportsTableSQLCmd($reportsArray) {
        if (isset($reportsArray) && !is_null($reportsArray)) {

            // delete previous data in sql
            $this->db->query('DELETE FROM reports')->execute();

            $key_for_params = ['Profiles', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'];
            $sqlCmd = 'INSERT INTO reports VALUES (:Profiles, :Jan, :Feb, :Mar, :Apr, :May, :Jun, :Jul, :Avg, :Sep, :Oct, :Nov, :Dec);';
            $sqlStatementDB = $this->db->prepare($sqlCmd);

            //fill data in 'reports' table
            foreach ($reportsArray as $report){
                for ($i = 1; $i <= count($report); $i++) {
                   $sqlStatementDB->bindValue($key_for_params[$i-1], $report[$i]);
                }
                $sqlStatementDB->execute();

            }
        } else {
            echo 'Cannot update Reports Table in SQL! Table is empty!';
        }
    }
}