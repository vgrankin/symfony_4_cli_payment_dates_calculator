<?php

namespace App\Tests\Service;

use App\Service\PaymentDatesCalculator;
use App\Tests\BaseTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamException;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class CSVDocumentGeneratorTest extends BaseTestCase
{
    public function setUp()
    {
        /**
         * "vfsStream is a stream wrapper for a virtual filesystem that may be helpful
         * in unit tests to mock the real filesystem."
         * https://phpunit.de/manual/6.5/en/test-doubles.html#test-doubles.mocking-the-filesystem
         *
         * vfsStream usage is inspired by this article:
         * https://www.sitepoint.com/hassle-free-filesystem-operations-during-testing/
         */
        try {
            vfsStreamWrapper::register();
            vfsStreamWrapper::setRoot(new vfsStreamDirectory('testDir'));
        } catch (vfsStreamException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGenerateDocument____when_Calling_With_Valid_Path_And_Data____CSV_Document_With_Correct_Data_Is_Created()
    {
        $calculator = new PaymentDatesCalculator();
        $yearMonth = "2018-12";
        $monthsCount = 3;
        $table = $calculator->getPaymentDatesTable($yearMonth, $monthsCount);

        $container = $this->getPrivateContainer();
        $csvGenerator = $container
            ->get('App\Service\CSVDocumentGenerator');

        $fileName = 'test.csv';

        $this->assertFalse(vfsStreamWrapper::getRoot()->hasChild($fileName));

        $csvGenerator->generateDocument(vfsStream::url('testDir') . '/' . $fileName, $table);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($fileName));

        $csv = file_get_contents(vfsStream::url('testDir') . '/' . $fileName);

        $serializer = $container
            ->get('serializer');
        $data = $serializer->decode($csv, 'csv');

        $expectedData = [
            [
                'month_name' => 'January',
                'basic_pay_date' => '2019-01-31',
                'bonuses_pay_date' => '2019-01-15'
            ],
            [
                'month_name' => 'February',
                'basic_pay_date' => '2019-02-28',
                'bonuses_pay_date' => '2019-02-12'
            ],
            [
                'month_name' => 'March',
                'basic_pay_date' => '2019-03-29',
                'bonuses_pay_date' => '2019-03-14'
            ]
        ];

        $this->assertEquals($expectedData, $data);
    }
}
