<?php


namespace App\Commands;


use Cassandra\Date;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CalculateSalaryPaymentDates extends Command
{
    protected static $defaultName = 'calculate-salary-dates';

    private const DEFAULT_FILE_LOCATION = 'command-output\\';
    private const DEFAULT_FILE_NAME = 'salary-dates.csv';

    private const COLUMN_HEADINGS = ['Period', 'BasicPayment', 'BonusPayment'];

    private const DEFAULT_DATE_FORMAT = "d-m-Y";
    private const DEFAULT_PERIOD_FORMAT = "M/y";

    private const DEBUG_DATE_FORMAT = "d-m-Y_D";

    private $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Calculate salary dates')
            ->setHelp('Calculate the salary dates for a given time interval. Default will be 12 months from the current date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileLocation = $this->rootPath . "\\" . self::DEFAULT_FILE_LOCATION . self::DEFAULT_FILE_NAME;

//        $output->writeln("The default file location is: " . $fileLocation);

        $fp = fopen($fileLocation, 'w');

        fputcsv($fp, self::COLUMN_HEADINGS);

        $firstDayOfCurrentMonth = new \DateTime(date("Y-m-01", time()));

        for($i = 0; $i < 12; $i++) {
            fputcsv($fp, [$firstDayOfCurrentMonth->format(self::DEFAULT_PERIOD_FORMAT), $this->getLastWorkingDayOfDate($firstDayOfCurrentMonth)]);

            $firstDayOfCurrentMonth->add(new \DateInterval('P1M')); // add 1 month to the date
        }

        fclose($fp);

        return 0;
    }

    private function getLastWorkingDayOfDate($date): string
    {
        $newDate = new \DateTime($date->format(self::DEFAULT_DATE_FORMAT));

        $numberOfDays = $newDate->format("t");

        $newDate->add(new \DateInterval('P' . ($numberOfDays - 1) . 'D'));

        $dayNumber = $newDate->format("N");

        if ($dayNumber == "6") // if Saturday get 1 day before
        {
            $newDate->sub(new \DateInterval('P1D'));
        }
        elseif ($dayNumber == "7") // if Sunday get 2 days before
        {
            $newDate->sub(new \DateInterval('P2D'));
        }

        return $newDate->format(self::DEFAULT_DATE_FORMAT);
//        return $newDate->format(self::DEBUG_DATE_FORMAT);
    }
}