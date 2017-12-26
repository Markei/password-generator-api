<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Generator;

interface GeneratorInterface
{
    public function validateOptions(array $options): void;
    
    public function generate(array $options): string;
}