<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Generator;

class HumanGenerator implements GeneratorInterface
{
    /**
     * @var array
     */
    protected $sources = [];
    
    public function __construct()
    {
        $this->sources = [
            'lipsum1' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
            'lipsum2' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
            'lipsum3' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.'
        ];
        $this->sources['lipsum'] = $this->sources['lipsum1'] . ' ' . $this->sources['lipsum2'] . ' ' . $this->sources['lipsum3'];
    }
   
    public function validateOptions(array $options): void
    {
        if (isset($options['source']) === false) {
            throw new \InvalidArgumentException('option: source must be set');
        }
        if (isset($options['minWordLength']) === false) {
            throw new \InvalidArgumentException('option: minWordLength must be set');
        }
        if (isset($options['maxWordLength']) === false) {
            throw new \InvalidArgumentException('option: maxWordLength must be set');
        }
        if (isset($options['removeLO']) === false) {
            throw new \InvalidArgumentException('option: removeLO must be set');
        }
        if (isset($options['numberOfCaps']) === false) {
            throw new \InvalidArgumentException('option: numberOfCaps must be set');
        }
        if (isset($options['numberLength']) === false) {
            throw new \InvalidArgumentException('option: numberLength must be set');
        }

        if (is_bool($options['removeLO']) === false) {
            throw new \InvalidArgumentException('option: removeLO must be a boolean');
        }
        
        if (is_int($options['minWordLength']) === false) {
            throw new \InvalidArgumentException('option: minWordLength must be an integer');
        }
        if (is_int($options['maxWordLength']) === false) {
            throw new \InvalidArgumentException('option: maxWordLength must be an integer');
        }
        if (is_int($options['numberOfCaps']) === false) {
            throw new \InvalidArgumentException('option: numberOfCaps must be an integer');
        }
        if (is_int($options['numberLength']) === false) {
            throw new \InvalidArgumentException('option: numberLength must be an integer');
        }
        
        if (isset($this->sources[$options['source']]) === false) {
            throw new \InvalidArgumentException('The value for the source option is invalid');
        }
        
        if (is_int($options['minWordLength']) === false || $options['minWordLength'] < 0 || $options['minWordLength'] > 10) {
            throw new \InvalidArgumentException('option: minWordLength needs to be between 0 and 10');
        }
    }
    
    public function generate(array $options): string
    {
        $this->validateOptions($options);
        
        $text = $this->sources[$options['source']];
        $text = strtolower($text);
        $words = preg_split('/(([^a-zA-Z]?\s)|[^a-zA-Z])/', $text);
        
        if ($options['removeLO'] == true) {
            $words = array_map(function ($word) {
                return str_replace(['L', 'l', 'O', 'o'], '', $word);
            }, $words);
        }
        
        if ($options['minWordLength'] > 0) {
            $words = array_filter($words, function ($word) use ($options) {
                if ($options['minWordLength'] > strlen($word)) {
                    return false;
                }
                return true;
            });
        }
        
        if ($options['maxWordLength'] > 0) {
            $words = array_map(function ($word) use ($options) {
                $wordLength = strlen($word);
                if ($wordLength > $options['maxWordLength']) {
                    $word = substr($word, rand(0, $wordLength - $options['maxWordLength']), $options['maxWordLength']);
                }
                return $word;
            }, $words);
        }
        
        $theWord = $words[array_rand($words)];
        
        if ($options['numberOfCaps'] > 0) {
            $numberOfCaps = rand(1, intval($options['numberOfCaps']));
            for ($i = 0; $i < $numberOfCaps; $i++) {
                $pos = rand(0, strlen($theWord) - 1);
                $theWord = substr($theWord, 0, $pos) . strtoupper(substr($theWord, $pos, 1)) . substr($theWord, $pos + 1);
            }
        }
        
        $numbers = '';
        for ($i = 0; $i < $options['numberLength']; $i ++) {
            $numbers .= rand(0, 9);
        }
        
        $theWord = (rand(0, 1) === 0 ? $numbers . $theWord : $theWord . $numbers);
        
        return $theWord;
    }
}