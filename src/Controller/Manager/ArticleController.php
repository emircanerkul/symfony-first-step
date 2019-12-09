<?php

namespace App\Controller\Manager;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\VarDumper\VarDumper;

class ArticleController extends AbstractController
{
  /**
   * @Route("/manager/article", name="manager_article_list")
   */
  public function list()
  {
    $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
    return $this->render('manager/article/index.html.twig', [
      'controller_name' => 'ArticleController',
      'articles' => $articles
    ]);
  }

  /**
   * @Route("/manager/article/delete/{id}", name="manager_article_delete")
   * Method ({"GET"})
   */
  public function delete(Request $request, $id)
  {
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($article);
    $entityManager->flush();

    return $this->redirectToRoute('manager_article_list');
  }

  /**
   * @Route("/manager/article/update/{id}", name="manager_article_update")
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
      ])->add('main_image', FileType::class, [
        'label' => 'Main Image (png or jpg)',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'image/png',
              'image/jpeg',
            ],
            'mimeTypesMessage' => 'Please upload a valid image',
          ])
        ],
      ])->add('save', SubmitType::class, array(
        'label' => 'Update',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();

      $main_image = $form['main_image']->getData();

      if ($main_image) {
          $originalFilename = pathinfo($main_image->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
          $newFilename = $safeFilename.'-'.uniqid().'.'.$main_image->guessExtension();

          try {
              $main_image->move(
                  $this->getParameter('upload_dir'),
                  $newFilename
              );
          } catch (FileException $e) {
            throw $e;
          }

          $article->setMainImage($newFilename);
      }

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      return $this->redirectToRoute('manager_article_list');
    }

    return $this->render('manager/article/update.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
   * @Route("/manager/article/create", name="manager_article_create")
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
      ])->add('main_image', FileType::class, [
        'label' => 'Main Image (png or jpg)',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'image/png',
              'image/jpeg',
            ],
            'mimeTypesMessage' => 'Please upload a valid image',
          ])
        ],
      ])->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();

      $main_image = $form['main_image']->getData();

      if ($main_image) {
          $originalFilename = pathinfo($main_image->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
          $newFilename = $safeFilename.'-'.uniqid().'.'.$main_image->guessExtension();

          try {
              $main_image->move(
                  $this->getParameter('upload_dir'),
                  $newFilename
              );
          } catch (FileException $e) {
            throw $e;
          }

          $article->setMainImage($newFilename);
      }

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($article);
      $entityManager->flush();
      return $this->redirectToRoute('manager_article_list');
    }

    return $this->render('manager/article/create.html.twig', array(
      'form' => $form->createView()
    ));
  }
}
