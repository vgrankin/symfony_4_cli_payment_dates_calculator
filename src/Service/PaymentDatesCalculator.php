<?php

namespace App\Service;


class PaymentDatesCalculator
{
    /**
     * Get payment dates table for the next $monthsCount months.
     *
     * @param string $yearMonth The following format is expected: "2018-10"
     * @param int $monthsCount How many upcoming months we want to get payment dates for
     * @return array List of basic and bonuses payment dates for given months count
     * @throws \Exception
     */
    public function getPaymentDatesTable(string $yearMonth, int $monthsCount): array
    {
        $table = [];
        foreach($this->getUpcomingMonths($yearMonth, $monthsCount) as $date){
            $table[] = [
                'month_name' => $date->format('F'),
                'basic_pay_date' => $this->getBasicPayDate($date)->format('Y-m-d'),
                'bonuses_pay_date' => $this->getBonusesPayDate($date)->format('Y-m-d'),
            ];
        }

        return $table;
    }

    /**
     * Get range/list of upcoming dates/months (in terms of \DatePeriod)
     * by given months-count and month-of-the-year to start range right after
     *
     * @param string $yearMonth The following format is expected: "2018-10"
     *                          So the returned range for this example will begin right
     *                          after month 10 and will start with month 11
     * @param int $monthsCount Range size. Specifies how many future dates we want
     *                         in our range.
     * @return \DatePeriod Represents a date period. A date period allows iteration over
     *                     a set of dates and times, recurring at regular intervals, over
     *                     a given period.
     * @throws \Exception
     */
    private function getUpcomingMonths(string $yearMonth, int $monthsCount): \DatePeriod
    {
        $start = new \DateTime($yearMonth);
        $start->modify('+1 month');
        $start->modify('first day of this month');

        $end = clone $start;
        $end->modify("+$monthsCount months");

        $interval = new \DateInterval('P1M');

        return new \DatePeriod($start, $interval, $end);
    }

    /**
     * Calculate/get basic pay date for the month retrieved from the given date
     *
     * Business rules to calculate basic pay date are as follows:
     *
     *      Basic pay is paid on the last working day of the month (Mon-Fri).
     *      So if the last day of January is the 31st, and this is a Saturday,
     *      the payment date is Friday the 30th. The same logic applies to Sunday.
     *
     * @param \DateTime $date Date to extract month to calculate-basic-pay-for from.
     * @return \DateTime
     */
    private function getBasicPayDate(\DateTime $date): \DateTime
    {
        $date = clone $date;
        $date->modify('last day of this month');

        // fix payment day according to business rules (if appropriate)
        $saturdaySundayDayNumbers = [0,6];
        while (in_array($date->format('w'), $saturdaySundayDayNumbers)) {
            $date->modify('-1 day');
        }

        return $date;
    }

    /**
     * Get bonuses pay date based on month extracted from given date
     *
     * Business rules to calculate bonuses pay date are as follows:
     *
     *      On the 12th of every month bonuses are paid for the previous month,
     *      unless that day is a weekend. In that case, they are paid the first
     *      Tuesday after the 12th
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    private function getBonusesPayDate(\DateTime $date): \DateTime
    {
        $paymentDay = 12;
        $date = clone $date;
        $date->setDate($date->format('Y'), $date->format('m'), $paymentDay);

        // fix payment day according to business rules (if appropriate)
        $saturdaySundayDayNumbers = [0,6];
        $tuesdayDayNumber = 2;
        if (in_array($date->format('w'), $saturdaySundayDayNumbers)) {
            while ($date->format('w') != $tuesdayDayNumber) {
                $date->modify('+1 day');
            }
        }

        return $date;
    }
}