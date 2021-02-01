<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class FormatListener
{
    /**
     * Set the request_format
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        $formatInPath = preg_match('/\\.(.+)$/i', $request->getPathInfo()) > 0;
        
        if ($formatInPath === true) {
            // validation is done by router
            $request->setRequestFormat($request->attributes->get('_format'));
        } else {
            $formatPriorities = $this->listFormats($request->getAcceptableContentTypes());
            $format = $formatPriorities->current(); // get the first match
            if ($format === null) { // nothing found
                $format = 'html';
            }
            $request->setRequestFormat($format);
        }
    }
    
    /**
     * Finds the first matching content type
     * @param array|string[] $acceptableContentTypes
     */
    protected function listFormats(array $acceptableContentTypes): \Generator
    {
        foreach ($acceptableContentTypes as $type) {
            switch ($type) {
                case 'text/html':
                    yield 'html';
                    break;
                case 'text/plain':
                    yield 'txt';
                    break;
                case 'text/xml':
                case 'application/xml':
                    yield 'xml';
                    break;
                case 'application/json':
                    yield 'json';
            }
        }
    }
}