<?php

require_once 'sum.php';

/**
 * @param $val
 * @param $val2
 * @throws Exception
 */
$test = function ($val, $val2) {
    if ($val !== $val2) {
        echo 'FAIL' . PHP_EOL;
        throw new \Exception('Values are not equal: %s != %s', $val, $val2);
    }

    echo '.';
};


$test(sumStringInt('12345', '54321'), '66666');
$test(sumStringInt('99', '99'), '198');
$test(sumStringInt('1000', '999'), '1999');
$test(sumStringInt('1001', '999'), '2000');
$test(sumStringInt('5', '15'), '20');
$test(sumStringInt('', '15'), '15');
$test(sumStringInt('12', ''), '12');
$test(sumStringInt('12', '0'), '12');
$test(sumStringInt('1254356874561356546513212148948541564564546541321546548451216565674656457654675243721432146754657143217321734217654654',
    '676465487654213213578798421321354984654132154687987465415489854655461'), '1254356874561356546513212148948541564564546541322223013938870778888235256075996598706086278909345130682737224072310115');

$test(minusStringInt('-12', '8'), '-4');
$test(minusStringInt('8', '-12'), '-4');
$test(minusStringInt('-8', '12'), '4');
$test(minusStringInt('8', '-8'), '0');

$test(minusStringInt('22', '-12'), '10');
$test(minusStringInt('-12', '22'), '10');
$test(minusStringInt('12', '-22'), '-10');
$test(minusStringInt('-12', '-22'), '-34');
$test(minusStringInt('-22', '-12'), '-34');
$test(minusStringInt('-100', '12'), '-88');
$test(minusStringInt('102', '-222'), '-120');
$test(minusStringInt('192', '-58'), '134');
$test(minusStringInt('001008', '-8'), '1000');

echo 'OK' . PHP_EOL;