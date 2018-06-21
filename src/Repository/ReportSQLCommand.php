<?php
/**
 * Created by PhpStorm.
 * @author: Samir Subašić
 * Date: 12.10.2017
 * Time: 15:14
 */

namespace BOF\Repository;

use BOF\Model\Months;

/**
 * Class ReportSQLCommand
 * @package BOF\Command
 */
class ReportSQLCommand
{
    private $db;

    // sigle value
    private $report_year;
    private $report_month;

    /**
     * ReportSQLCommand constructor.
     */
    public function __construct($db, $report_year, $report_month)
    {
        $this->db = $db;
        $this->report_year = $report_year;
        $this->report_month = $report_month;
    }

    public function getProfiles() {
        //get array of users IDs and their Names sorted alphabeticaly
        $profiles = $this->db->query('SELECT p.* FROM profiles p ORDER BY p.profile_name;')->fetchAll();
        return $profiles;

    }

    public function getAllYearlyReports()
    {
        $Months = Months::$MonthList;

        // set sql statement
        $sqlString = sprintf("SELECT v.profile_id, p.profile_name, v.date, v.views FROM views v 
                      LEFT JOIN profiles p ON v.profile_id = p.profile_id 
                      WHERE YEAR(Date)=%s", $this->report_year);

        // let's see if we have defined month for monthly report, add month to sql statement
        if (!is_null($this->report_month) && isset($this->report_month)){
            $sqlString .= sprintf(" AND MONTH(Date)=%s", $Months[$this->report_month]);
        }

        // add alphabetical order
        $sqlString .= " ORDER BY p.profile_name";

        // execute sql and get all data
        $yearlyReportFromSQL = $this->db->query($sqlString)->fetchAll();

        return $yearlyReportFromSQL;
    }

    public function updateReportsTableSQLCmd($reportsArray) {

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