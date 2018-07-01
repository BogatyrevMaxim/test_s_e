<?php

function sumStringInt(string $a, string $b): string
{
    $aLength = mb_strlen($a);
    $bLength = mb_strlen($b);

    if (!$aLength) {
        return $b;
    }

    if (!$bLength) {
        return $a;
    }

    $remainder = 0;
    $i = $j = -1;
    $do = true;
    $result = '';

    do {

        if (($i + $aLength) < 0) {
            $c = 0;
        } else {
            $c = mb_substr($a, $i, 1);
            --$i;
        }

        if (($j + $bLength) < 0) {
            $d = 0;
        } else {
            $d = mb_substr($b, $j, 1);
            --$j;
        }

        $tempResult = (int)$c + (int)$d + $remainder;
        $remainder = intval($tempResult / 10);
        $currentVal = $tempResult % 10;
        $result = sprintf('%d%s', $currentVal, $result);

        if ($remainder === 0 && ($j + $bLength) < 0 && ($i + $aLength) < 0) {
            $do = false;
        }

    } while ($do);

    return $result;
}

// test
echo sumStringInt('12345', '54321') . PHP_EOL;
echo sumStringInt('99', '99') . PHP_EOL;
echo sumStringInt('1000', '999') . PHP_EOL;
echo sumStringInt('1001', '999') . PHP_EOL;
echo sumStringInt('5', '15') . PHP_EOL;
echo sumStringInt('', '15') . PHP_EOL;
echo sumStringInt('12', '') . PHP_EOL;
echo sumStringInt('12', '0') . PHP_EOL;
echo sumStringInt('1254356874561356546513212148948541564564546541321546548451216565674656457654675243721432146754657143217321734217654654',
        '676465487654213213578798421321354984654132154687987465415489854655461') . PHP_EOL;