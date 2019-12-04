<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function article(Request $request)
    {
        $articles = ['Article 1', 'Article 2', 'Article 3', 'Article 4'];

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/new", name="new_article")
     * Method ({"GET", "POST"})
     */
    public function new(Request $request){
        $article = new Article();

        $form = $this->createFormBuilder($article)
          ->add('title', TextType::class, array('attr' =>array('class' => 'form-control')))
          ->add('body', TextareaType::class, array('required' =>false,
            'attr' =>array('class' =>'form-control')))
          ->add('save', SubmitType::class, array(
            'label' =>'Create',
            'attr' =>array('class'=>'btn btn-primary mt-3')
          ))
          ->getForm();

        return $this->render('article/new.html.twig',array(
          'form'=>$form->createView()
        ));
      }
}
