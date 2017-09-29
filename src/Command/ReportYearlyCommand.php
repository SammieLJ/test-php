<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
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

        //get summarized data per year
        $UsersYearReport = [];
        foreach($allReportedYears as $ReportedYear) {
            foreach($profiles as $profile_ID => $profile_Data) {

                //var_dump($profile_Data);

                $tempString = "CALL TotalUserViewsByYear('".$ReportedYear['years']."', ".$profile_Data["profile_id"].");".PHP_EOL;
                $tempRowYearReport = $db->query($tempString)->fetchAll();

                $UsersYearReport[] = $tempRowYearReport[0];
            }

            //clean array of null, where null replace with 'n/a'
            $UsersYearReportFinal = self::replace_null_with_empty_string($UsersYearReport);

            // Show data in a table - headers, data            
            $io->table(['Profile year '.$ReportedYear['years']], $UsersYearReportFinal);
            $UsersYearReport = []; // reset data for new year table
        }

        // Show data in a table - headers, data
        //$io->table(['Profile'], $profiles);
        //$io->table(['Profile years '], $UsersYearReportFinal);

    }

    static function replace_null_with_empty_string($array)
    {
        foreach ($array as $key => $value) 
        {
            if(is_array($value))
                $array[$key] = self::replace_null_with_empty_string($value);
            else
            {
                if (is_null($value))
                    $array[$key] = "n/a";
            }
        }
        return $array;
    }
}
