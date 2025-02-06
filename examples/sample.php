<?php

require_once __DIR__ . '/../src/BakongKHQR.php';
require_once __DIR__ . '/../src/IndividualInfo.php';

$individualInfo = new IndividualInfo(
	bakongAccountID: 'jonhsmith@devb',
	merchantName: 'Jonh Smith',
	merchantCity: 'Phnom Penh',
);

echo BakongKHQR::generateIndividual($individualInfo);
