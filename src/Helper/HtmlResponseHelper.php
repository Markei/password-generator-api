<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Helper;

class HtmlResponseHelper
{
    public function buildBaseResponse(string $content): string
    {
        $html  = '<!doctype html>' . PHP_EOL;
        $html .= '<html>' . PHP_EOL;
        $html .= '    <head>' . PHP_EOL;
        $html .= '        <meta charset="utf-8">' . PHP_EOL;
        $html .= '    </head>' . PHP_EOL;
        $html .= '    <body>' . PHP_EOL;
        $html .= $content . PHP_EOL;
        $html .= '    </body>' . PHP_EOL;
        $html .= '</html>' . PHP_EOL;
        return $html;
    }

    public function buildListResponse(array $passwords): string
    {
        $html  = '        <ul>' . PHP_EOL;
        foreach ($passwords as $password) {
            $html .= '            <li>' . htmlentities($password, \ENT_COMPAT | \ENT_HTML5, 'UTF-8') . '</li>' . PHP_EOL;
        }
        $html .= '        </ul>' . PHP_EOL;
        return $this->buildBaseResponse($html);
    }

    public function buildExceptionResponse(\Exception $e): string
    {
        $html = '<strong>' . htmlentities(get_class($e), \ENT_COMPAT | \ENT_HTML5, 'UTF-8') . '</strong> ' ;
        $html .= htmlentities($e->getMessage(), \ENT_COMPAT | \ENT_HTML5, 'UTF-8');
        return $this->buildBaseResponse($html);
    }
}