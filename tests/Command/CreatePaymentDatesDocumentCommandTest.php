<?php

namespace App\Tests\Command;

use App\Service\PaymentDatesCalculator;
use App\Tests\BaseTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamException;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Testing Commands documentation:
 * https://symfony.com/doc/current/console.html#testing-commands
 */
class CreateUserCommandTest extends BaseTestCase
{
    public function setUp()
    {
        try {
            vfsStreamWrapper::register();
            vfsStreamWrapper::setRoot(new vfsStreamDirectory('testDir'));
        } catch (vfsStreamException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $fileName = 'test.csv';
        $command = $application->find('app:create-payment-dates-csv-document');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),

            // pass arguments to the helper
            'csv_save_path' => vfsStream::url('testDir') . '/' . $fileName,

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains(
            'Will calculate payment dates now and save them to given CSV path: vfs://testDir/test.csv',
            $output
        );
        $this->assertContains('Done! You can find generated CSV document in the path specified!', $output);
        $this->assertContains('Have a great day & Cheers! :-)', $output);

        $calculator = new PaymentDatesCalculator();
        $yearMonth = date("Y-m");
        $monthsCount = 12;
        $data = $calculator->getPaymentDatesTable($yearMonth, $monthsCount);

        $csv = file_get_contents(vfsStream::url('testDir') . '/' . $fileName);

        $container = $this->getPrivateContainer();
        $serializer = $container
            ->get('serializer');
        $csvDecoded = $serializer->decode($csv, 'csv');

        $this->assertEquals($data, $csvDecoded);
    }
}