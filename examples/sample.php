<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

$individualInfo = new IndividualInfo(
    bakongAccountID: 'sopheaktaruney_kien@wing',
    merchantName: 'Sayyasweetspot by S.KIEN',
    merchantCity: 'Phnom Penh',
    currency: KHQRData::CURRENCY_KHR,
    amount: 500
);

print_r(BakongKHQR::generateIndividual($individualInfo));
