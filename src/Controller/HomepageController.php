<?php
declare(strict_types=1);

namespace Markei\PasswordGenerator\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    #[Route(
        path: '/',
    )]
    public function indexAction(): Response
    {
        return $this->redirectToRoute('markei_passwordgenerator_generator_human');
    }
}
