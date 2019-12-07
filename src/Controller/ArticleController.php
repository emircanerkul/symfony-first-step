<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
  /**
   * @Route("/article", name="article_list")
   */
  public function list()
  {
    $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
    return $this->render('article/index.html.twig', [
      'controller_name' => 'ArticleController',
      'articles' => $articles
    ]);
  }

  /**
   * @Route("/article/delete/{id}", name="article_delete")
   * Method ({"GET"})
   */
  public function delete(Request $request, $id)
  {
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($article);
    $entityManager->flush();

    return $this->redirectToRoute('article_list');
  }

  /**
   * @Route("/article/update/{id}", name="article_update")
   * Method ({"GET", "POST"})
   */
  public function update(Request $request, $id)
  {
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('body', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))->add('category', EntityType::class, [
        'class' => Category::class
      ])
      ->add('save', SubmitType::class, array(
        'label' => 'Update',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      return $this->redirectToRoute('article_list');
    }

    return $this->render('article/update.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
   * @Route("/article/create", name="article_create")
   * Method ({"GET", "POST"})
   */
  public function create(Request $request)
  {
    $article = new Article();

    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('body', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))->add('category', EntityType::class, [
        'class' => Category::class
      ])
      ->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($article);
      $entityManager->flush();
      return $this->redirectToRoute('article_list');
    }

    return $this->render('article/new.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
   * @Route("/article/{id}", name="article_show")
   * Method ({"GET"})
   */
  public function show(Request $request)
  {
    $article = $this->getDoctrine()->getRepository(Article::class)->find(['id' => $request->get("id")]);
    return $this->render('article/show.html.twig', [
      'controller_name' => 'ArticleController',
      'article' => $article
    ]);
  }
}
