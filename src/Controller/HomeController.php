<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Hello;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $dm = $this->getDoctrine()->getManager();

        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'messages' => $dm->getRepository(Hello::class)->findBy([], ['id'=>"DESC"], 3),
            'articles' => $dm->getRepository(Article::class)->findAll()
        ]);
    }
}
