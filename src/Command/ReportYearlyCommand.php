<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Tests\RepositoryTest;

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
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');
        $reportSQLCmd = new ReportSQLCommand($db, $display_to, $report_year, $report_month);

        if ($display_to == 'display') {
            // Show data in a table - headers, data
            $io->table(ToolsToWrite::setArrayForTableHeader($report_year), $reportSQLCmd->execute());
        }

        if ($display_to == 'file') {
            // Show data in a file table - headers, data
            ToolsToWrite::writeToFile(ToolsToWrite::setArrayForTableHeader($report_year), $reportSQLCmd->execute());
        }

        if ($display_to == 'sql') {
            // Show data in a sql table - headers, data
            $reportSQLCmd->execute('UpdateReports', $reportSQLCmd->execute('GetData'));
        }

        if ($display_to == 'test') {
            $repositoryTest = new RepositoryTest();
            $repositoryTest->testCanPersistAndFindReports($report_year);
        }

    }
}
