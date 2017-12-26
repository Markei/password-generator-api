<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Generator;

class RandomGenerator implements GeneratorInterface
{
    protected $lowerchars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    protected $upperchars = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    protected $digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    protected $symbols = ['~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '`', '-', '=', '{', '}', '[', ']', ':', ';', '<', '>', ',', '.', '?', '/'];
    protected $symbolsCommon = ['!', '@', '#', '$', '%', '&', '*', '(', ')', '+', '-', '{', '}', '[', ']', '<', '>', '.', '?'];

    public function validateOptions(array $options): void
    {
        if (isset($options['lowercase']) === false) {
            throw new \InvalidArgumentException('option: lowercase must be set');
        }
        if (isset($options['uppercase']) === false) {
            throw new \InvalidArgumentException('option: uppercase must be set');
        }
        if (isset($options['digits']) === false) {
            throw new \InvalidArgumentException('option: digits must be set');
        }
        if (isset($options['symbols']) === false) {
            throw new \InvalidArgumentException('option: symbols must be set');
        }
        if (isset($options['onlyCommonSymbols']) === false) {
            throw new \InvalidArgumentException('option: onlyCommon must be set');
        }
        if (isset($options['min']) === false) {
            throw new \InvalidArgumentException('option: min must be set');
        }
        if (isset($options['max']) === false) {
            throw new \InvalidArgumentException('option: max must be set');
        }

        if (is_bool($options['lowercase']) === false) {
            throw new \InvalidArgumentException('option: lowercase must be a boolean');
        }
        if (is_bool($options['uppercase']) === false) {
            throw new \InvalidArgumentException('option: uppercase must be a boolean');
        }
        if (is_bool($options['digits']) === false) {
            throw new \InvalidArgumentException('option: digits must be a boolean');
        }
        if (is_bool($options['symbols']) === false) {
            throw new \InvalidArgumentException('option: symbols must be a boolean');
        }
        if (is_bool($options['onlyCommonSymbols']) === false) {
            throw new \InvalidArgumentException('option: onlyCommonSymbols must be a boolean');
        }

        if (is_int($options['min']) === false) {
            throw new \InvalidArgumentException('option: min must be an integer');
        }
        if (is_int($options['max']) === false) {
            throw new \InvalidArgumentException('option: max must be an integer');
        }
        
        if ($options['min'] > $options['max']) {
            throw new \InvalidArgumentException('option: min can not be larger then max');
        }
        
        if ($options['min'] === 0) {
            throw new \InvalidArgumentException('option: min can not be 0');
        }
    }
    
    public function generate(array $options): string
    {
        $this->validateOptions($options);

        $source = [];
        if ($options['lowercase'] === true) {
            $source = array_merge($source, $this->lowerchars);
        }
        if ($options['uppercase'] === true) {
            $source = array_merge($source, $this->upperchars);
        }
        if ($options['digits'] === true) {
            $source = array_merge($source, $this->digits);
        }
        if ($options['symbols'] === true && $options['onlyCommonSymbols'] === true) {
            $source = array_merge($source, $this->symbolsCommon);
        } elseif ($options['symbols'] === true && $options['onlyCommonSymbols'] === false) {
            $source = array_merge($source, $this->symbols);
        }
        
        $length = rand($options['min'], $options['max']);
        $word = '';
        for ($i = 0; $i < $length; $i ++) {
            $word .= $source[array_rand($source)];
        }
        
        return $word;
    }
}