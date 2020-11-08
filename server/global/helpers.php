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

function deleteFileFromFolder(string $filepath): void
{
    array_map( 'unlink', array_filter((array) glob(getcwd() . $filepath)));
}

function TMP_DIR()
{
    return getcwd() . '/public/tmp/';
}

function ASSETS_DIR()
{
    return getcwd() . '/public/assets/';
}
