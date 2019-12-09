<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Currency
{
    private $currency_commission;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->currency_commission = $containerInterface->getParameter("currency_commission");
    }

    public function getEUR()
    {
        return json_decode(file_get_contents("https://api.exchangeratesapi.io/latest?symbols=TRY&base=EUR"))->rates->TRY * (1 + $this->currency_commission);
    }

    public function getUSD()
    {
        return json_decode(file_get_contents("https://api.exchangeratesapi.io/latest?symbols=TRY&base=USD"))->rates->TRY * (1 + $this->currency_commission);
    }
}
