<?php

namespace App\Controller\Manager;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/manager/category", name="manager_category_list")
     */
    public function list()
    {
        $dm = $this->getDoctrine()->getManager();

        return $this->render('manager/category/index.html.twig', [
            "categories" => $dm->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * @Route("/manager/category/new", name="manager_category_create")
     */
    public function create(Request $request, Slugger $slugger)
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($request->get("title") !== null) {
            $category = new Category();
            $category->setTitle($request->get("title"));
            $category->setSlug($slugger->slugify($request->get("title")));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return new JsonResponse($category->getId());
        } else if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('manager_category_list');
        }

        return $this->render('manager/category/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/category/update/{id}", name="manager_category_update")
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
            return $this->redirectToRoute('manager_category_list');
        }

        return $this->render('manager/category/update.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/category/delete/{id}", name="manager_category_delete")
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
            return $this->redirectToRoute('manager_category_list');
        }


        return $this->render('manager/category/index.html.twig', [
            "categories" => $dm->getRepository(Category::class)->findAll()
        ]);
    }
}
