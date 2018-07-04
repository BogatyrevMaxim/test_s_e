<?php

/*
 * Сложение и вычитание
 * Работа с большими ЦЕЛЫМИ числами в виде строки, которые могут не умещатся в целый тип
 *
 */

/**
 * Сложение целых чисел
 * @param string $a
 * @param string $b
 * @return string
 */
function sumStringInt(string $a, string $b): string
{
    // являются ли числа отрицательными
    $aIsNegative = false;
    $bIsNegative = false;

    if (mb_substr($a, 0, 1) === '-') {
        $aIsNegative = true;
    }

    if (mb_substr($b, 0, 1) === '-') {
        $bIsNegative = true;
    }


    if ($aIsNegative !== $bIsNegative) {
        return minusStringInt($a, $b);
    } else if ($aIsNegative && $bIsNegative) {
        $a = mb_substr($a, 1);
        $b = mb_substr($b, 1);
    }

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

    if ($aIsNegative && $bIsNegative) {
        $result = sprintf('-%s', $result);
    }

    return $result;
}

/**
 * Вычитание целых чисел
 * @param string $a
 * @param string $b
 * @return string
 */
function minusStringInt(string $a, string $b): string
{
    // являются ли числа отрицательными
    $aIsNegative = false;
    $bIsNegative = false;

    if (mb_substr($a, 0, 1) === '-') {
        $aIsNegative = true;
        $a = mb_substr($a, 1);
    }

    if (mb_substr($b, 0, 1) === '-') {
        $bIsNegative = true;
        $b = mb_substr($b, 1);
    }

    // фильтрация нулей в начале
    $funcFilterZeroInStart = function (string $num) {
        $zeroLastPos = null;
        $len = mb_strlen($num);
        for ($i = 0; $i < $len; $i++) {
            $a = mb_substr($num, $i, 1);
            if ((int)$a === 0) {
                $zeroLastPos = $i;
            } else {
                break;
            }
        }

        if (null !== $zeroLastPos) {
            if ($zeroLastPos + 1 === $len) {
                return 0;
            }

            $num = mb_substr($num, $zeroLastPos + 1);

        }

        return $num;
    };

    $a = $funcFilterZeroInStart($a);
    $b = $funcFilterZeroInStart($b);

    $aLength = mb_strlen($a);
    $bLength = mb_strlen($b);

    if (!$aLength || $aLength === 1 && $a === '0') {
        return $bIsNegative ? sprintf('-%s', $b) : $b;
    }

    if (!$bLength || $bLength === 1 && $b === '0') {
        return $aIsNegative ? sprintf('-%s', $a) : $a;
    }

    // оба отрицательны
    if ($aIsNegative && $bIsNegative) {
        return sprintf('-%s', sumStringInt($a, $b));
    }

    $minus = function ($big, $small) use ($funcFilterZeroInStart) {
        $bigLength = mb_strlen($big);
        $smallLength = mb_strlen($small);
        $i = $j = 1;
        $remainder = 0;
        $resStr = '';

        $bigIterateIsEnd = function ($i, $bigLength) {
            return ($bigLength - $i) < 0;
        };

        $smallIterateIsEnd = function ($j, $smallLength) {
            return ($smallLength - $j) < 0;
        };


        do {
            if ($bigIterateIsEnd($i, $bigLength)) {
                $c = 0;
            } else {
                $c = mb_substr($big, -$i, 1);
                $i++;
            }

            if ($smallIterateIsEnd($j, $smallLength)) {
                $d = 0;
            } else {
                $d = mb_substr($small, -$j, 1);
                $j++;
            }

            $isFirstSmall = $c + $remainder < $d;
            if ($isFirstSmall) {
                $c = $c + 10;
            }

            $currentVal = (int)$c - (int)$d + $remainder;
            if ($isFirstSmall) {
                $remainder = -1;
            } else {
                $remainder = 0;
            }

            $resStr = sprintf('%d%s', $currentVal, $resStr);
        } while (!($remainder === 0 && $bigIterateIsEnd($i, $bigLength) && $smallIterateIsEnd($j, $smallLength)));

        return $funcFilterZeroInStart($resStr);
    };

    $result = '';

    // определим наибольшее число по модулю, и вычтем из него меньшее
    if ($aLength > $bLength) {

        $result = $minus($a, $b);
        if ($aIsNegative) {
            $result = sprintf('-%s', $result);
        }

    } elseif ($bLength > $aLength) {

        $result = $minus($b, $a);

        if ($bIsNegative) {
            $result = sprintf('-%s', $result);
        }

    } else {

        // найти наибольшее
        $aIsBig = $bIsBig = false;

        for ($i = 0; $i < $aLength; $i++) {
            $c = (int)mb_substr($a, $i, 1);
            $d = (int)mb_substr($b, $i, 1);

            if ($c > $d) {
                $aIsBig = true;
            } elseif ($d > $c) {
                $bIsBig = true;
            }

            if ($aIsBig != $bIsBig) {
                break;
            }
        }

        if ($aIsBig === $bIsBig) {
            return '0';
        }

        if ($aIsBig) {
            $result = $minus($a, $b);
            if ($aIsNegative) {
                $result = sprintf('-%s', $result);
            }
        } else {
            $result = $minus($b, $a);
            if ($bIsNegative) {
                $result = sprintf('-%s', $result);
            }
        }

    }

    return $result;
}
