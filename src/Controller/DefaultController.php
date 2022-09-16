<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/{reactRouting}", name="index", defaults={"reactRouting": null})
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/user/{reactRouting}", name="index_user", defaults={"reactRouting": null})
     * @Route("/admin/{reactRouting}", name="index_admin", defaults={"reactRouting": null})
     * @Route("/dev/{reactRouting}", name="index_dev", defaults={"reactRouting": null})
     */
    public function index2(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
