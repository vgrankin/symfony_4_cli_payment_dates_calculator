<?php

namespace App\Tests\Service;

use App\Service\PaymentDatesCalculator;
use PHPUnit\Framework\TestCase;

class PaymentDatesCalculatorTest extends TestCase
{
    public function testGetPaymentDatesTable____when_Calling_With_Valid_Parameters____Response_Array_Contains_Correct_Months_List()
    {
        $calculator = new PaymentDatesCalculator();
        $yearMonth = "2018-10";
        $monthsCount = 3;
        $calculator->getPaymentDatesTable($yearMonth, 3);


    }
}
