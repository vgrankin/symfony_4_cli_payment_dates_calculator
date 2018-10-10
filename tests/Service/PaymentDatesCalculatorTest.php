<?php

namespace App\Tests\Service;

use App\Service\PaymentDatesCalculator;
use PHPUnit\Framework\TestCase;

class PaymentDatesCalculatorTest extends TestCase
{
    public function testGetPaymentDatesTable____when_Calling_With_Valid_Parameters____Response_Array_Contains_Correct_Month_Names_List()
    {
        $calculator = new PaymentDatesCalculator();
        $yearMonth = "2018-10";
        $monthsCount = 3;
        $table = $calculator->getPaymentDatesTable($yearMonth, $monthsCount);

        $this->assertEquals($monthsCount, sizeof($table));
        $this->assertArrayHasKey("month_name", $table[0]);

        $monthNames = array_column($table, 'month_name');

        $this->assertEquals('November', $monthNames[0]);
        $this->assertEquals('December', $monthNames[1]);
        $this->assertEquals('January', $monthNames[2]);
    }

    public function testGetPaymentDatesTable____when_Calling_With_Valid_Parameters____Response_Array_Contains_Correct_Basic_Pay_Dates_List()
    {
        $calculator = new PaymentDatesCalculator();
        $yearMonth = "2018-10";
        $monthsCount = 12;
        $table = $calculator->getPaymentDatesTable($yearMonth, $monthsCount);

        $this->assertEquals($monthsCount, sizeof($table));
        $this->assertArrayHasKey("basic_pay_date", $table[0]);

        $basicPayDates = array_column($table, 'basic_pay_date');

        $this->assertEquals('2018-11-30', $basicPayDates[0]);
        $this->assertEquals('2018-12-31', $basicPayDates[1]);
        $this->assertEquals('2019-01-31', $basicPayDates[2]);
        $this->assertEquals('2019-02-28', $basicPayDates[3]);
        $this->assertEquals('2019-03-29', $basicPayDates[4]);
        $this->assertEquals('2019-04-30', $basicPayDates[5]);
        $this->assertEquals('2019-05-31', $basicPayDates[6]);
        $this->assertEquals('2019-06-28', $basicPayDates[7]);
        $this->assertEquals('2019-07-31', $basicPayDates[8]);
        $this->assertEquals('2019-08-30', $basicPayDates[9]);
        $this->assertEquals('2019-09-30', $basicPayDates[10]);
        $this->assertEquals('2019-10-31', $basicPayDates[11]);
    }

    public function testGetPaymentDatesTable____when_Calling_With_Valid_Parameters____Response_Array_Contains_Correct_Bonuses_Pay_Dates_List()
    {
        $calculator = new PaymentDatesCalculator();
        $yearMonth = "2018-10";
        $monthsCount = 12;
        $table = $calculator->getPaymentDatesTable($yearMonth, $monthsCount);

        $this->assertEquals($monthsCount, sizeof($table));
        $this->assertArrayHasKey("bonuses_pay_date", $table[0]);

        $bonusesPayDates = array_column($table, 'bonuses_pay_date');

        $this->assertEquals('2018-11-12', $bonusesPayDates[0]);
        $this->assertEquals('2018-12-12', $bonusesPayDates[1]);
        $this->assertEquals('2019-01-15', $bonusesPayDates[2]);
        $this->assertEquals('2019-02-12', $bonusesPayDates[3]);
        $this->assertEquals('2019-03-12', $bonusesPayDates[4]);
        $this->assertEquals('2019-04-12', $bonusesPayDates[5]);
        $this->assertEquals('2019-05-14', $bonusesPayDates[6]);
        $this->assertEquals('2019-06-12', $bonusesPayDates[7]);
        $this->assertEquals('2019-07-12', $bonusesPayDates[8]);
        $this->assertEquals('2019-08-12', $bonusesPayDates[9]);
        $this->assertEquals('2019-09-12', $bonusesPayDates[10]);
        $this->assertEquals('2019-10-15', $bonusesPayDates[11]);
    }
}
