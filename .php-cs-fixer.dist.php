<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in('src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'comment_to_phpdoc' => true,
        'no_empty_comment' => true,
        'single_line_comment_style' => true,
        'native_constant_invocation' => true,
        'native_function_invocation' => true,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_dedicate_assert_internal_type' => true,
        'no_useless_return' => true,
        'return_assignment' => true,
        'simplified_null_return' => true,
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'strict_comparison' => true,
        'blank_line_before_statement' => true,
        'types_spaces' => true,
        'compact_nullable_typehint' => true,
        'phpdoc_to_comment' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;