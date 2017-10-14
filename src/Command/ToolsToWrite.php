<?php
/**
 * Created by PhpStorm.
 * @author: Samir Subašić
 * Date: 13.10.2017
 * Time: 1:06
 */

namespace BOF\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class ToolsToWrite
{
    private $reportList;

    public static function writeToFile($firstRow, $reportList)
    {
        $fs = new Filesystem();

        try {
            $text = ToolsToWrite::prepairTable($firstRow, $reportList);
            $fs->dumpFile('file.txt', $text);
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating file.txt ".$e->getFile();
        }
    }

    private static function prepairTable($firstRow, $reportList) {
        $offset = 1;
        $firstRow = ToolsToWrite::reindexArray($firstRow, $offset);
        $rowMaxLengths = ToolsToWrite::measureRowsLengths($reportList);

        $tableInText = '';
        $tableInText .= ToolsToWrite::drawRowLine($rowMaxLengths, count($firstRow));

        // set first row text
        $tableInText .= ToolsToWrite::putTextInRow($rowMaxLengths, $firstRow);

        $tableInText .= ToolsToWrite::drawRowLine($rowMaxLengths, count($firstRow));

        foreach($reportList as $report) {
            $tableInText .= ToolsToWrite::putTextInRow($rowMaxLengths, $report);
        }

        $tableInText .= ToolsToWrite::drawRowLine($rowMaxLengths, count($firstRow));

        return $tableInText;
    }

    private static function measureRowsLengths($reportList) {
        $rowMaxLengths = [];

        //check for each row max length
        foreach ($reportList as $report) {
            for ($i = 1; $i <= count($report); $i++) {
                // for the first time, array is empty
                if (!isset($rowMaxLengths[$i])) {
                    $rowMaxLengths[$i] = strlen($report[$i]);
                }

                //when array is already filled, checking if there is longer string than  before
                if (isset($rowMaxLengths[$i]) && strlen($report[$i]) > $rowMaxLengths[$i]) {
                    $rowMaxLengths[$i] = strlen($report[$i]);
                }

            }
        }
        return $rowMaxLengths;
    }

    private static function drawRowLine ($rowMaxLengths, $rows) {
        //first row line
        $tableInText = '';
        for ($i = 1; $i <= $rows; $i++) {
            $tableInText .= '+-';
            $tableInText .= str_repeat( '-', $rowMaxLengths[$i]+2);

        }
        // end and new row
        $tableInText .= '-+'.PHP_EOL;

        return $tableInText;

    }

    private static function putTextInRow($rowMaxLengths, $rowText) {
        $tableInText = '';
        $tableInText .= '| ';
        for ($i = 1; $i <= count($rowText); $i++) {
            if ($i > 1) $tableInText .= ' | ';
            $tableInText .= $rowText[$i];
            $tableInText .= str_repeat( ' ', ($rowMaxLengths[$i] - strlen($rowText[$i])) + 1);

        }
        $tableInText .= '  |'.PHP_EOL;
        return $tableInText;
    }

    public static function setArrayForTableHeader($report_year) {
        return ['Profile year '.$report_year, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

    private static function reindexArray($rowArray, $offset) {
        $reindexedArray = [];
        for ($i = 0; $i <= count($rowArray)-1; $i++) {
            $reindexedArray[$i+$offset] = $rowArray[$i];
        }
        return $reindexedArray;
    }
}