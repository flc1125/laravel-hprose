<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'array_syntax' => array('syntax' => 'long'),
        'ordered_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'yoda_style' => false,
        'phpdoc_summary' => false,
        'phpdoc_no_empty_return' => false,
        'not_operator_with_successor_space' => true,
        'binary_operator_spaces' => array(
            'align_double_arrow' => true,
            'align_equals' => false,
        ),
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('_*')
            ->exclude('vendor')
            ->exclude('storage')
            ->exclude('resources')
            ->exclude('public')
            ->in(__DIR__)
    )
;
