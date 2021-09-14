<?php

namespace App\Command;

use App\Service\CalcService;
use App\Service\LocalStore;
use DateTime;
use DateTimeZone;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckDiffCommand extends Command
{
    protected static $defaultName = 'app:check-diff';

    private LocalStore $localStore;

    private CalcService $calcService;

    public function __construct(LocalStore $localStore, CalcService $calcService)
    {
        $this->localStore = $localStore;
        $this->calcService = $calcService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check diff for time interval')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $timeInterval = new \DateInterval('P14D');
        $tz = new DateTimeZone('UTC');
        $checkTime = new DateTime('now',$tz);
        $diff = $this->calcService->lastDiffForTimeInterval($checkTime, $timeInterval);



        $io->success('Check diff for time interval success.');

        return Command::SUCCESS;
    }
}
