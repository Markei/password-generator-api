<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Helper;

class XmlResponseHelper
{
    public function buildListResponse(array $passwords): \DOMDocument
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $passwordsDom = $dom->createElement('passwords');
        $dom->appendChild($passwordsDom);
        foreach ($passwords as $password) {
            $passwordDom = $dom->createElement('password');
            $passwordDom->appendChild($dom->createTextNode($password));
            $passwordsDom->appendChild($passwordDom);
        }
        return $dom;
    }

    public function buildExceptionResponse(\Exception $e): \DOMDocument
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $exceptionDom = $dom->createElement('exception');
        $dom->appendChild($exceptionDom);
        $typeDom = $dom->createElement('type');
        $typeDom->appendChild($dom->createTextNode(get_class($e)));
        $exceptionDom->appendChild($typeDom);
        $messageDom = $dom->createElement('message');
        $messageDom->appendChild($dom->createTextNode($e->getMessage()));
        $exceptionDom->appendChild($messageDom);
        return $dom;
    }
}