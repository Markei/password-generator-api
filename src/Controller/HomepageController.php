<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('markei_passwordgenerator_generator_human');
    }
}