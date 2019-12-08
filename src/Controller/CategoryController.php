<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_list")
     */
    public function list()
    {
        $dm = $this->getDoctrine()->getManager();

        return $this->render('category/index.html.twig', [
            "categories" => $dm->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * @Route("/category/new", name="category_create")
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/category/update/{id}", name="category_update")
     * Method ({"GET", "POST"})
     */
    public function update(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     * Method: {"GET"}
     */
    public function delete($id)
    {
        $dm = $this->getDoctrine()->getManager();
        $category = $dm->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Category not found!");
        } else {
            $dm->remove($category);
            $dm->flush();
            return $this->redirectToRoute('category_list');
        }


        return $this->render('category/index.html.twig', [
            "categories" => $dm->getRepository(Category::class)->findAll()
        ]);
    }
}