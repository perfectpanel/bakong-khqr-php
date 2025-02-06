<?php

require_once __DIR__ . '/../vendor/autoload.php';

$individualInfo = new IndividualInfo(
	bakongAccountID: 'jonhsmith@devb',
	merchantName: 'Jonh Smith',
	merchantCity: 'Phnom Penh',
);

echo BakongKHQR::generateIndividual($individualInfo);
