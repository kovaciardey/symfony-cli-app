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
        $output->writeln("The default file location is: " . $this->rootPath . "\\" . self::DEFAULT_FILE_LOCATION . self::DEFAULT_FILE_NAME);

        $fp = fopen($this->rootPath . "\\" . self::DEFAULT_FILE_LOCATION . self::DEFAULT_FILE_NAME, 'w');

        fputcsv($fp, self::COLUMN_HEADINGS);

        $testDate = date("Y-m-d", time());

        dump($testDate);
        dump(date("t", strtotime("+1 month", time())));
        dump(date("Y-m-d", strtotime("+1 month", time())));


        for($i = 0; $i < 12; $i++) {
            $date = strtotime("+$i month", time());

            fputcsv($fp, [date("M/y", $date), date("Y-m-D", $date), date("Y-m-l", $date)]);
        }

        fclose($fp);

        return 0;
    }
}