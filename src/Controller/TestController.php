<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        $nomRoute = "je suis sur la route test";
        return $this->render('test/index.html.twig', [
            'controller_name' => 'Page de TestController',
            'route' => $nomRoute
        ]);
    }

    /**
     * @Route("/route-param/{nom}", name="route_param")
     */
    public function routeParam(String $nom): Response
    {
        return $this->render('test/route-param.html.twig', [
            'nom' => $nom
        ]);
    }
}
