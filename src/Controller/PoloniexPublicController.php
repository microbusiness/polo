<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\PublicTradeHistory;
use App\Service\PoloniexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PoloniexPublicController extends AbstractController
{
    #[Route('/poloniex/public', name: 'poloniex_public')]
    public function index(PoloniexService $polo): Response
    {
        $tz = new \DateTimeZone('UTC');
        $ticker = $polo->returnTradeHistory(
            'BTC_ETH',
            new \DateTime('2021-01-23 00:00:00',$tz),
            new \DateTime('2021-01-23 00:59:59',$tz)
        );

        return $this->render('poloniex_public/index.html.twig', [
            'controller_name' => 'PoloniexPublicController',
        ]);
    }

    #[Route('/poloniex/test', name: 'poloniex_test')]
    public function test(PoloniexService $polo): Response
    {
        $list = [
            ['id' => 1,'title' => 'New first','complited' => false],
            ['id' => 2,'title' => 'New second','complited' => true],
            ['id' => 3,'title' => 'New third','complited' => true],
        ];
        return $this->json($list);
    }

    #[Route('/poloniex/chart', name: 'poloniex_chart')]
    public function chart(): Response
    {
        $tz = new \DateTimeZone('UTC');
        $checkTime = new \DateTime('now',$tz);
        $checkTime->setTime($checkTime->format('G'),0);

        $baseCurrency = $this->getDoctrine()
            ->getRepository(Currency::class)
            ->findOneBy(['code' => 'USDT']);

        $marketCurrency = $this->getDoctrine()
            ->getRepository(Currency::class)
            ->findOneBy(['code' => 'ETH']);

        $list = $this->getDoctrine()->getRepository(PublicTradeHistory::class)->findLastHour(
            $baseCurrency->getId(),
            $marketCurrency->getId(),
            $checkTime
        );

        $listOut = [];
        foreach ($list as $item) {
            $listOut[] = ['name' => '','pv' => $item->getTradeRate()];
        }
        return $this->json($listOut);
    }
}
