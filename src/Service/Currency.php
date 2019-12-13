<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Currency
{
    private $currency_commission;
    private $cache;
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->currency_commission = $containerInterface->getParameter("currency_commission");
        $this->cache = new FilesystemAdapter();
    }

    public function getEUR()
    {
        return $this->cache->get("currency_eur", function (ItemInterface $item) {
            $item->expiresAfter(60);

            return json_decode(file_get_contents("https://api.exchangeratesapi.io/latest?symbols=TRY&base=EUR"))->rates->TRY * (1 + $this->currency_commission);
        });
    }

    public function getUSD()
    {
        return $this->cache->get("currency_usd", function (ItemInterface $item) {
            $item->expiresAfter(60);

            return json_decode(file_get_contents("https://api.exchangeratesapi.io/latest?symbols=TRY&base=USD"))->rates->TRY * (1 + $this->currency_commission);
        });
    }
}
