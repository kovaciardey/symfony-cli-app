<?php


namespace App\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateSalaryPaymentDates extends Command
{
    protected static $defaultName = 'calculate-salary-dates';

    private const DEFAULT_FILE_LOCATION = 'command-output\\';
    private const DEFAULT_FILE_NAME = 'salary-dates.csv';

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
        return 0;
    }
}