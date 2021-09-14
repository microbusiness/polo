<?php

namespace App\Command;

use App\Service\CalcService;
use App\Service\LocalStore;
use App\Service\PoloniexService;
use DateInterval;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SaveHistoryCommand extends Command
{
    protected static $defaultName = 'app:save-history';

    private $poloniexService;

    private $localStore;

    private $calcService;

    public function __construct(PoloniexService $poloniexService, LocalStore $localStore, CalcService $calcService)
    {
        $this->poloniexService = $poloniexService;
        $this->localStore = $localStore;
        $this->calcService = $calcService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Save history pair')
            ->addArgument('pair', InputArgument::OPTIONAL, 'Name pair')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('pair');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $firstHour = $this->calcService->getFirstHour();

        $lastHour = clone $firstHour;
        $lastHour->add(new DateInterval('PT1H'));
        $lastHour->sub(new DateInterval('PT1S'));
        $publicTradeHistoryListArray = $this->poloniexService->returnTradeHistory(
            $arg1,
            $firstHour,
            $lastHour
        );

        $this->localStore->setPublicHistoryList($publicTradeHistoryListArray,$arg1);

        $io->success('Pair saved.');

        if (false === $this->calcService->currentTimeIsFirst($firstHour)) {

            $firstHour->sub(new DateInterval('PT1H'));
            $lastHour = clone $firstHour;
            $lastHour->add(new DateInterval('PT1H'));
            $lastHour->sub(new DateInterval('PT1S'));
            $publicTradeHistoryListArray = $this->poloniexService->returnTradeHistory(
                $arg1,
                $firstHour,
                $lastHour
            );

            $this->localStore->setPublicHistoryList($publicTradeHistoryListArray,$arg1);
        }

        $io->success('Check current hour and update previous hour.');


        return Command::SUCCESS;
    }
}
