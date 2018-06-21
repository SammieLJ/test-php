<?php
namespace BOF\Command;

use BOF\Repository\ReportRepository;
use BOF\Tests\RepositoryTest;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('display_to', InputArgument::REQUIRED, 'Where to display report? sql/file/display')
            ->addArgument('report_year', InputArgument::REQUIRED, 'Enter year to generate report!')
            ->addArgument('report_month', InputArgument::OPTIONAL, 'Optionally enter month to generate report!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // set where to display!
        $display_to = $input->getArgument('display_to');

        // set year to report!
        $report_year = $input->getArgument('report_year');

        // set month to report!
        $report_month= $input->getArgument('report_month');

        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');

        // All cases need to have initialised ReportRepository
        $reportRep = new ReportRepository($db, $report_year, $report_month);


        if ($display_to == 'display') {
            // set I/O operations
            $io = new SymfonyStyle($input,$output);

            echo "Data are being displayed to screen for year ".$report_year.PHP_EOL;
            // Show data in a table - headers, data
            $reportRep->buildProfilesAndYearlyReports();
            $io->table(ToolsToWrite::setArrayForTableHeader($report_year), $reportRep->getYearlyReport());
        }

        if ($display_to == 'file') {
            echo "Data are being written to file.txt for year ".$report_year.PHP_EOL;
            // Show data in a file table - headers, data
            $reportRep->buildProfilesAndYearlyReports();
            ToolsToWrite::writeToFile(ToolsToWrite::setArrayForTableHeader($report_year), $reportRep->getYearlyReport());
        }

        if ($display_to == 'sql') {
            // Show data in a sql table - headers, data
            echo "Data are being written to sql table: 'reports'".PHP_EOL;
            $reportRep->buildProfilesAndYearlyReports();
            $reportRep->insertAndUpdateReportsTableInSQL();
        }

        if ($display_to == 'test') {
            echo "Report object data is being tested for year ".$report_year.PHP_EOL;
            $repositoryTest = new RepositoryTest();
            $repositoryTest->testCanPersistAndFindReports($report_year);
        }

    }
}
