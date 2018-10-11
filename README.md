# Symfony 4 CLI application

This is a simple CLI application which sole purpose is to calculate when to pay base salary and 
bonuses to staff. As a result CSV file with payment dates is produced. 
It was created according to given specification document. 

Regarding project itself. Several ideas were in mind, like thin-command and TDD approach. 
SOLID principles, speaking names and other good design practices were also kept in mind 
(thankfully Symfony itself is a good primer of this). Most business logic is moved from 
command to corresponding services, which in turn use other services to fulfill business
logic requirements.

## Technical details / Requirements:
- Current project is built using Symfony 4.1 framework
- It is based on a minimal version of Symfony, skeleton project, which is created specifically 
  for cases like CLI applications. Please see: https://symfony.com/doc/current/setup.html
- PHPUnit is used for tests	
	* Note: it is better to run symfony's built-in PHPUnit, not the global one you have on your system, 
			  because different versions of PHPUnit expect different syntax. Tests for this project 
			  were built using preinstalled PHPUnit which comes with Symfony (located in bin folder). 
			  You can run all tests by running this command from project directory: 
			  ./bin/phpunit (php bin/phpunit on Windows). 
			  * Read more here: https://symfony.com/doc/current/testing.html
- In addition to PHPUnit vfsStream library is used in some unit tests to mock the real file system.
  * Read more here: https://github.com/mikey179/vfsStream     	 
- PHP 7.2.9 is used so you will need something similar available on your system 
  (there are many options to install it: Docker/XAMPP/standalone version etc.)

## Installation:
	
	- Use the Symfony Requirements Checker tool to make sure your system is set up. 
	  (https://symfony.com/doc/current/reference/requirements.html)
	  
    - Configure "date.timezone" option in php.ini if required. Example: 
    
    [Date]
    date.timezone=Europe/London
	
    - unzip to desired project directory
    
    - go to project directory and run: composer install
    
    - In order to run PHPUnit tests yourself, you will need to create local version of phpunit.xml:
        - for that, just copy phpunit.xml.dist and rename it to phpunit.xml
        * you can also simply use original file phpunit.xml.dist, because we don't change it in any way 
    * after performing step above don't forget to set phpunit.xml as a PHPUnit configuration file in your IDE

## Implementation details:

- Current application is based on Symfony skeleton project and its Console component.
  That said, all business logic is coded from scratch. Symfony is only used as a good starting point/base. 
- In terms of workflow the following design solution is used: Command 
  (App\Command\CreatePaymentDatesDocumentCommand) is used as a controller to execute business logic 
  to generate required CSV document. To make Command thin we use services (which in turn call other services). 
  This way we have a good thin command/controller along with practices like Separation of Concerns, 
  Single responsibility principle etc. It also promotes good code testability.
  * Read more about commands here: https://symfony.com/doc/current/console.html
- App\Service\PaymentDatesCalculator is used to implement business logic required by this project.
- App\Service\PaymentDatesCalculator is used to encode calculated data to CSV format 
  and save it as file to specified location.
- All application code is in /src folder
- All tests are located in /tests folder
- In most cases the following test-case naming convention is used: MethodUnderTest____Scenario____Behavior()

## Usage/testing:

- Usage is very simple. You just need to go to project directory and run the following command from console:
`php bin/cli D:/test.csv` where test.csv is a full path of a file. In this case output will be 
saved to D:/test.csv. Before running this CLI app make sure directory you save 
to exists and that you have required permissions. If everything is correct CSV file with payment dates 
for the next 12 months will be saved to specified location.

Here is an example on Windows 10:

    D:\dev\payment-dates-calculator>php bin/cli D:/test.csv
    Will calculate payment dates now and save them to given CSV path: D:/test.csv
    Done! You can find generated CSV document in the path specified!
    Have a great day & Cheers! :-)

- To run all tests execute this command from project's root folder: 
"php bin/phpunit" 

## Notes

- currently any national days or company work-free days are not taken into account. 
  Strictly following specification in this case.
- to keep time budget for this project reasonable only several unit tests and integration tests were implemented
  (to show that I'm aware of TDD and different scenarios of execution).
- some validation logic is also included to show my awareness of input validation and edge cases problems.
 (see violations check in App\Service\PaymentDatesCalculator -> getPaymentDatesTable() method)
- This project was mostly developed using TDD approach (red/green/refactor technique).
- I will implement any further features/tests by request, but hopefully current project is implemented enough
  to demonstrate/evaluate my skills for the role. Thank you! 
  

