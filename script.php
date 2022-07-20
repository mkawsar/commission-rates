<?php

use Transaction\CommissionFee\Services\CommissionService;
use Transaction\CommissionFee\Services\CurrencyService;
use Transaction\CommissionFee\TransactionCollection;

require_once __DIR__ . '/vendor/autoload.php';

$url = "https://developers.paysera.com/tasks/api/currency-exchange-rates";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = ["Accept: */*",];
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);

$response = json_decode($resp, true);

$currencies = [
    [
        'symbol' => 'EUR',
        'rate' => 1,
        'precision' => 2,
    ],
    [
        'symbol' => 'USD',
        'rate' => $response['rates']['USD'],
        'precision' => 2,
    ],
    [
        'symbol' => 'JPY',
        'rate' => $response['rates']['JPY'],
        'precision' => 0,
    ]
];

$currencyServices = new CurrencyService();
$currencyServices->collectCurrenciesFromArray($currencies);

$collection = new TransactionCollection();
$collection->parseFromCSV($argv[1]);

$commissionService = new CommissionService($currencyServices);

foreach ($commissionService->calculateFeesFromCollection($collection) as $fee) {
    echo $fee . PHP_EOL;
}
