<?php

namespace App\Controller;

use App\Form\HelloType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/say_hi", name="hello")
     * Method: {"GET","POST"}
     */
    public function index(Request $request)
    {
        $form = $this->createForm(HelloType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm = $this->getDoctrine()->getManager();
            $dm->persist($form->getData());
            $dm->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
            "form" => $form->createView()
        ]);
    }
}
