<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Markei\PasswordGenerator\Generator as Generator;
use Markei\PasswordGenerator\Helper as Helper;

class GeneratorController
{
    /**
     * @var Helper\HtmlResponseHelper
     */
    protected $htmlResponseHelper;
    
    /**
     * @var Helper\XmlResponseHelper
     */
    protected $xmlResponseHelper;
    
    protected $generationLimit = 100;
    
    /**
     * @param Helper\HtmlResponseHelper $htmlResponseHelper
     * @param Helper\XmlResponseHelper $xmlResponseHelper
     */
    public function __construct(Helper\HtmlResponseHelper $htmlResponseHelper, Helper\XmlResponseHelper $xmlResponseHelper, $generationLimit)
    {
        $this->htmlResponseHelper = $htmlResponseHelper;
        $this->xmlResponseHelper = $xmlResponseHelper;
        $this->generationLimit = $generationLimit;
    }
    
    /**
     * Generates a password that is easy to remember for humans
     * Available options:
     * - count: number of passwords to generate (default: 1, limit: 1000)
     * - source: source text to use, available: lipsum1, lipsum2, lipsum3, lipsum (default: lipsum)
     * - minWordLength/maxWordLength: the number of letters in the password (default: 6)
     * - removeLO: remove the letters "L" and "O" in words because people confuse them with the "I" and "0"
     * - numberOfCaps: the maxium number of caps in the word
     * - numberLength: the number of digits to at to the word
     * @Route(
     *  "/human.{_format}",
     *  defaults={"_format": "html"},
     *  requirements={"_format": "html|json|txt|xml"}
     * )
     */
    public function human(Request $request, Generator\HumanGenerator $generator): Response
    {
        try {
            $numberOfPasswords = $request->query->getInt('count', 1);
            if ($numberOfPasswords > $this->generationLimit) {
                throw new \InvalidArgumentException('Sorry there is a limit of ' . $this->generationLimit . ' passwords per call');
            }
            $options = [
                'source' => $request->query->get('source', 'lipsum'),
                'minWordLength' => $request->query->getInt('minWordLength', 6),
                'maxWordLength' => $request->query->getInt('maxWordLength', 6),
                'removeLO' => $request->query->getBoolean('removeLO', true),
                'numberOfCaps' => $request->query->getInt('numberOfCaps', 1),
                'numberLength' => $request->query->getInt('numberLength', 3),
            ];
            $passwords = [];
            for ($i = 0; $i < $numberOfPasswords; $i++) {
                $passwords[] = $generator->generate($options);
            }
            return $this->handleSuccess($request, $passwords);
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }

    /**
     * Generates a password that can be savely used in configuration files, passwords are long, only lowercase and digits, no symbols
     * Available options:
     * - count: number of passwords to generate (default: 1, limit: 1000)
     * - min/max: the length of the password (default: 48)
     * @Route(
     *  "/randomsave.{_format}",
     *  defaults={"_format": "html"},
     *  requirements={"_format": "html|json|txt|xml"}
     * )
     */
    public function randomSave(Request $request, Generator\RandomGenerator $generator): Response
    {
        try {
            $numberOfPasswords = $request->query->getInt('count', 1);
            if ($numberOfPasswords > $this->generationLimit) {
                throw new \InvalidArgumentException('Sorry there is a limit of ' . $this->generationLimit . ' passwords per call');
            }
            $options = [
                'lowercase' => true,
                'uppercase' => false,
                'digits' => true,
                'symbols' => false,
                'onlyCommonSymbols' => false,
                'min' => $request->query->getInt('min', 48),
                'max' => $request->query->getInt('max', 48)
            ];
            $passwords = [];
            for ($i = 0; $i < $numberOfPasswords; $i++) {
                $passwords[] = $generator->generate($options);
            }
            return $this->handleSuccess($request, $passwords);
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }

    /**
     * Generates a password that can be savely used in configuration files, passwords are long, only lowercase, upppercase and digits, no symbols
     * Available options:
     * - count: number of passwords to generate (default: 1, limit: 1000)
     * - min/max: the length of the password (default: 48)
     * @Route(
     *  "/randomsave2.{_format}",
     *  defaults={"_format": "html"},
     *  requirements={"_format": "html|json|txt|xml"}
     * )
     */
    public function randomSave2(Request $request, Generator\RandomGenerator $generator): Response
    {
        try {
            $numberOfPasswords = $request->query->getInt('count', 1);
            if ($numberOfPasswords > $this->generationLimit) {
                throw new \InvalidArgumentException('Sorry there is a limit of ' . $this->generationLimit . ' passwords per call');
            }
            $options = [
                'lowercase' => true,
                'uppercase' => true,
                'digits' => true,
                'symbols' => false,
                'onlyCommonSymbols' => false,
                'min' => $request->query->getInt('min', 48),
                'max' => $request->query->getInt('max', 48)
            ];
            $passwords = [];
            for ($i = 0; $i < $numberOfPasswords; $i++) {
                $passwords[] = $generator->generate($options);
            }
            return $this->handleSuccess($request, $passwords);
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }
    
    /**
     * Generates a random password
     * Available options:
     * - count: number of passwords to generate (default: 1, limit: 1000)
     * - min/max: the length of the password (default: 6)
     * - lowercase: include lowercase chars (default: true)
     * - uppercase: include uppercase chars (default: true)
     * - digits: include digits chars (default: true)
     * - symbols: include symbols chars (default: true)
     * - onlyCommonSymbols: do not use symbols like quotes and accents (default: true)
     * @Route(
     *  "/random.{_format}",
     *  defaults={"_format": "html"},
     *  requirements={"_format": "html|json|txt|xml"}
     * )
     */
    public function random(Request $request, Generator\RandomGenerator $generator): Response
    {
        try {
            $numberOfPasswords = $request->query->getInt('count', 1);
            if ($numberOfPasswords > $this->generationLimit) {
                throw new \InvalidArgumentException('Sorry there is a limit of ' . $this->generationLimit . ' passwords per call');
            }
            $options = [
                'lowercase' => $request->query->getBoolean('lowercase', true),
                'uppercase' => $request->query->getBoolean('uppercase', true),
                'digits' => $request->query->getBoolean('digits', true),
                'symbols' => $request->query->getBoolean('symbols', true),
                'onlyCommonSymbols' => $request->query->getBoolean('onlyCommonSymbols', true),
                'min' => $request->query->getInt('min', 36),
                'max' => $request->query->getInt('max', 36)
            ];
            $passwords = [];
            for ($i = 0; $i < $numberOfPasswords; $i++) {
                $passwords[] = $generator->generate($options);
            }
            return $this->handleSuccess($request, $passwords);
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }
    
    /**
     * Generates a pincode (only digits)
     * Available options:
     * - count: number of pincodes to generate (default: 1, limit: 1000)
     * - min/max: the length of the pincodes (default: 6)
     * @Route(
     *  "/pincode.{_format}",
     *  defaults={"_format": "html"},
     *  requirements={"_format": "html|json|txt|xml"}
     * )
     */
    public function pincode(Request $request, Generator\PincodeGenerator $generator): Response
    {
        try {
            $numberOfPasswords = $request->query->getInt('count', 1);
            if ($numberOfPasswords > $this->generationLimit) {
                throw new \InvalidArgumentException('Sorry there is a limit of ' . $this->generationLimit . ' passwords per call');
            }
            $options = [
                'min' => $request->query->getInt('min', 4),
                'max' => $request->query->getInt('max', 4)
            ];
            $passwords = [];
            for ($i = 0; $i < $numberOfPasswords; $i++) {
                $passwords[] = $generator->generate($options);
            }
            return $this->handleSuccess($request, $passwords);
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }
    
    /**
     * @param Request $request
     * @param \Exception $e
     */
    protected function handleException(Request $request, \Exception $e): Response
    {
        $responseCode = $e instanceof \InvalidArgumentException ? Response::HTTP_BAD_REQUEST : Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($request->getRequestFormat() === 'html') {
            return new Response($this->htmlResponseHelper->buildExceptionResponse($e), $responseCode);
        } elseif ($request->getRequestFormat() === 'json') {
            return new JsonResponse(['exception' => get_class($e), 'message' => $e->getMessage()], $responseCode);
        } elseif ($request->getRequestFormat() === 'txt') {
            return new Response('exception: ' . get_class($e) . PHP_EOL . 'message: ' . $e->getMessage(), $responseCode);
        } elseif ($request->getRequestFormat() === 'xml') {
            return new Response($this->xmlResponseHelper->buildExceptionResponse($e)->saveXML());
        }
        
        throw new \LogicException('Unsupported request format');
    }
    
    /**
     * @param Request $request
     * @param array|string[] $passwords
     */
    protected function handleSuccess(Request $request, array $passwords): Response
    {
        if ($request->getRequestFormat() === 'html') {
            return new Response($this->htmlResponseHelper->buildListResponse($passwords));
        } elseif ($request->getRequestFormat() === 'json') {
            return new JsonResponse(['passwords' => $passwords]);
        } elseif ($request->getRequestFormat() === 'txt') {
            return new Response(implode(PHP_EOL, $passwords));
        } elseif ($request->getRequestFormat() === 'xml') {
            return new Response($this->xmlResponseHelper->buildListResponse($passwords)->saveXML());
        }
        
        throw new \LogicException('Unsupported request format');
    }
}