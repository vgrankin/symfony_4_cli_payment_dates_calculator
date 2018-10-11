<?php

namespace App\Command;

use App\Service\CSVDocumentGenerator;
use App\Service\PaymentDatesCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


/**
 * This is a Command class to create a CSV document with payment dates.
 *
 * Please see documentation on how Commands are working in Symfony 4.1 here:
 * https://symfony.com/doc/current/console.html
 *
 */
class CreatePaymentDatesDocumentCommand extends Command
{
    /**
     * Upcoming months to generate payment (base and bonuses) dates for
     */
    const MONTHS_COUNT = 12;

    private $csvGenerator;
    private $calculator;

    public function __construct(PaymentDatesCalculator $calculator, CSVDocumentGenerator $csvGenerator)
    {
        $this->csvGenerator = $csvGenerator;
        $this->calculator = $calculator;

        parent::__construct();
    }

    /**
     * Configure the name of the command, define help message and the input options and arguments
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-payment-dates-csv-document')
            // the short description shown while running "php bin/console list"
            ->setDescription('Generates payment dates CSV document and saves it to given path.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(
                'This command allows you to generate CSV file'
                .' which contains payment dates for telesales staff for the next '
                .self::MONTHS_COUNT.' months ...'
            );

        $this
            ->addArgument('csv_save_path', InputArgument::REQUIRED, 'CSV file save path (including file name)');
    }

    /**
     * Generate CSV document with payment dates to specified path
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = '<info>Will calculate payment dates now and save them to given CSV path: '
            .'<options=bold>'.$input->getArgument('csv_save_path').'</></info>';
        $output->writeln($text);

        try {
            $yearMonth = date("Y-m");
            $data = $this->calculator->getPaymentDatesTable($yearMonth, self::MONTHS_COUNT);
            $res = $this->csvGenerator->generateDocument($input->getArgument('csv_save_path'), $data);
            if (false !== $res) {
                $output->writeln('<info>Done! You can find generated CSV document in the path specified!</info>');
                $output->writeln('<comment>Have a great day & Cheers! :-)</comment>');
            } else {
                $output->writeln(
                    '<error>'
                    .'Unable to save CSV document. '
                    .'Please fix your path (make sure to use existing directory with required permissions) '
                    .'or try another one!'
                    .'</error>'
                );
            }
        } catch (\Exception $e) {
            $output->writeln(
                '<error>'
                .'Unable to save CSV document. '
                .'Please try again using different path or contact system administrator!'
                .'</error>'
            );
        }
    }
}