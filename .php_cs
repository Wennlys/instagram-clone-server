<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'php_unit_strict' => false,
        'php_unit_test_case_static_method_calls' => false,
        'php_unit_test_annotation' => ['case' => 'snake', 'style' => 'annotation'],
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'yoda_style' => false
    ])
    ->setFinder($finder)
;
