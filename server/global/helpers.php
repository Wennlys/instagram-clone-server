<?php

use Psr\Http\Message\ServerRequestInterface;
use ReallySimpleJWT\Token;

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

function TMP_DIR(): string
{
    return getcwd() . '/public/tmp/';
}

function ASSETS_DIR(): string
{
    return getcwd() . '/public/assets/';
}

function getPayload(ServerRequestInterface $request): ?array
{
    [$header] = $request->getHeader('Authorization');
    if (!$header) {
        return null;
    }

    [, $token] = explode(' ', $header);

    return Token::getPayload($token, $_ENV['SECRET']);
}
