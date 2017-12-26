<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Generator;

class PincodeGenerator implements GeneratorInterface
{
    public function validateOptions(array $options): void
    {
        if (isset($options['min']) === false) {
            throw new \InvalidArgumentException('option: min must be set');
        }
        if (isset($options['max']) === false) {
            throw new \InvalidArgumentException('option: max must be set');
        }
        
        if (is_int($options['min']) === false) {
            throw new \InvalidArgumentException('option: min is not an integer');
        }
        if (is_int($options['max']) === false) {
            throw new \InvalidArgumentException('option: min is not an integer');
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
       
        $length = $options['min'];
        if ($options['min'] !== $options['max']) {
            $length = rand($options['min'], $options['max']);
        }
        
        $pincode = '';
        for ($i = 0; $i < $length; $i ++) {
            $pincode .= '' . rand(0, 9);
        }
        
        return $pincode;
    }
}