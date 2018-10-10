<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class CreatePaymentDatesDocumentCommand extends Command
{
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
                . ' which contains payment dates for telesales staff for the next 12 months ...'
            );

        $this
            ->addArgument('csv_save_path', InputArgument::REQUIRED, 'CSV file save path (including file name)?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Will calculate payment dates now and save them to given CSV path: ' . $input->getArgument('csv_save_path');
        $output->writeln($text);



        $output->writeln('done!');
    }
}