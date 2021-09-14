<?php

namespace App\Command;

use App\Service\CalcService;
use App\Service\LocalStore;
use App\Service\PoloniexService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SaveMarketsCommand extends Command
{
    protected static $defaultName = 'app:save-markets';

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
            ->setDescription('Save markets')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $remoteList = $this->poloniexService->getRemoteMarkets();
        $this->localStore->saveCurrency($remoteList);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
