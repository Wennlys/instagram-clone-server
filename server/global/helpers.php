<?php

function now(): string
{
    $date = new DateTime('now', new DateTimeZone('UTC'));

    return $date->format('Y-m-d\TH:i:sT');
}

function copyArray(array $array): array
{
    $arrObj = new ArrayObject($array);
    return $arrObj->getArrayCopy();
}