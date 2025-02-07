<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KHQR\BakongKHQR;
use KHQR\Models\IndividualInfo;

$individualInfo = new IndividualInfo(
    bakongAccountID: 'jonhsmith@devb',
    merchantName: 'Jonh Smith',
    merchantCity: 'Phnom Penh',
);

print_r(BakongKHQR::generateIndividual($individualInfo));
