<?php
/**
 * Created by PhpStorm.
 * User: StormTrooper
 * Date: 13.10.2017
 * Time: 13:57
 */

namespace BOF\Tests;

use BOF\Model\Report;
use PHPUnit\Framework\TestCase;

/* Using PHPUnit 5.7 for PHP 5.6 */
class RepositoryTest extends TestCase
{
    public function testCanPersistAndFindReports($report_year=NULL) {
        $testDate = $report_year.'-10-13';

        $report = new Report(1, 'Sammy', $testDate, 100);

        $this->assertEquals(1, $report->getProfileId());
        $this->assertEquals('Sammy', $report->getProfileName());
        $this->assertEquals($testDate, $report->getDate());
        $this->assertEquals(100, $report->getViews());
    }
}