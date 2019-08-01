<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Generator;

class PairGenerator implements GeneratorInterface
{
    /**
     * @var array
     */
    protected $sources = [];

    public function __construct()
    {
        $this->sources['digits'] = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $this->sources['chars'] = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $this->sources['hex'] = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
    }

    public function validateOptions(array $options): void
    {
        if ($options['numberOfPairs'] > 10) {
            throw new \InvalidArgumentException('numberOfPairs max is 8');
        }
        if ($options['numberOfPairs'] < 2) {
            throw new \InvalidArgumentException('numberOfPairs min is 2');
        }
        if ($options['pairLength'] > 8) {
            throw new \InvalidArgumentException('pairLength max is 8');
        }
        if ($options['pairLength'] < 1) {
            throw new \InvalidArgumentException('pairLength min is 1');
        }
        if (strlen($options['separator']) !== 1) {
            throw new \InvalidArgumentException('separator must be exact one char');
        }
        if (in_array($options['set'], ['digits', 'chars', 'mix', 'all', 'hex']) === false) {
            throw new \InvalidArgumentException('unknown set');
        }
        if (is_bool($options['lowercase']) === false) {
            throw new \InvalidArgumentException('lowercase is not a boolean');
        }
    }

    public function generate(array $options): string
    {
        $this->validateOptions($options);

        $pairs = [];
        for ($i = 0; $i < $options['numberOfPairs']; $i ++) {
            $source = [];
            if ($options['set'] === 'digits') {
                $source = $this->sources['digits'];
            } elseif ($options['set'] === 'chars') {
                $source = $this->sources['chars'];
            } elseif ($options['set'] === 'hex') {
                $source = $this->sources['hex'];
            } elseif ($options['set'] === 'mix') {
                $source = ((rand(1,2) === 1) ? $this->sources['digits'] : $this->sources['chars']);
            } elseif ($options['set'] === 'all') {
                $source = array_merge($source, $this->sources['digits']);
                $source = array_merge($source, $this->sources['chars']);
            }

            $pair = '';
            for ($j = 0; $j < $options['pairLength']; $j ++) {
                $pair .= $source[array_rand($source, 1)];
            }
            $pairs[] = $pair;
        }

        $password = implode($options['separator'], $pairs);
        if ($options['lowercase'] === true) {
            $password = strtolower($password);
        }
        return $password;
    }
}