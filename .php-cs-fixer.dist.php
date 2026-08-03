<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('node_modules')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2.0' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'strict_param' => true,
        'no_unused_imports' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
