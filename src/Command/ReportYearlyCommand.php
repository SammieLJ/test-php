<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * ConstantExport Trait implements getConstants() method which allows 
 * to return class constant as an assosiative array
 */
Trait ConstantExport 
{
    /**
     * @return [const_name => 'value', ...]
     */
    static function getConstants(){
        $refl = new \ReflectionClass(__CLASS__);
        return $refl->getConstants();
    }
}

class Months {
    const __default = self::January;
    
    const January = 1;
    const February = 2;
    const March = 3;
    const April = 4;
    const May = 5;
    const June = 6;
    const July = 7;
    const August = 8;
    const September = 9;
    const October = 10;
    const November = 11;
    const December = 12;

    use ConstantExport;
}

class ReportYearlyCommand extends ContainerAwareCommand
{
    //static Months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        // get array of how many distinct years are in the data
        $allReportedYears = $db->query('SELECT DISTINCT(year(v.date)) as years FROM views v')->fetchAll();

        //get array of users IDs and their Names
        $profiles = $db->query('SELECT p.* FROM profiles p ORDER BY p.profile_name;')->fetchAll();

        //Get all defined months
        $Months = Months::getConstants(); 

        //get summarized data per year
        $UsersYearReport = [];
        foreach($allReportedYears as $ReportedYear) {
            foreach($profiles as $profile => $profile_Data) {

                //var_dump($profile_Data); // DEBUG

                //thir for statement for each month
                //SELECT profile_id, date, views FROM views WHERE profile_id=1 AND YEAR(Date) = 2015 AND MONTH(Date) = 5

                $tempRowYearReport = array();
                $tempRowYearReport[1] = $profile_Data["profile_name"];

                foreach ($Months as $month) {
                    //echo 'Trenutni mesec je: ' . $month . PHP_EOL;
                    $tempString = "SELECT views FROM views WHERE profile_id=".$profile_Data["profile_id"]." AND YEAR(Date) = ".$ReportedYear['years']." AND MONTH(Date) = ".$month;
                    $tempMonth = $db->query($tempString)->fetchAll();

                    //var_dump($tempString); // DEBUG
                    //var_dump($tempMonth); // DEBUG

                    $sumTempMonth = 0;
                    foreach ($tempMonth as $key => $value) {
                        //echo $views;
                        $sumTempMonth += $value["views"];
                    }
                    //$sumTempMonth = array_sum($tempMonth);

                    if ($sumTempMonth > 0) {
                        $tempRowYearReport[$month+1] = $sumTempMonth;    
                    } else {
                        $tempRowYearReport[$month+1] = 'n/a';
                    }
                    
                }

                //var_dump($tempRowYearReport); // DEBUG

                $UsersYearReport[] = $tempRowYearReport;
            }

            // Show data in a table - headers, data            
            $io->table(['Profile year '.$ReportedYear['years']], $UsersYearReport);
            $UsersYearReport = []; // reset data for new year table
        }

        // Show data in a table - headers, data
        //$io->table(['Profile'], $profiles);

    }

}
