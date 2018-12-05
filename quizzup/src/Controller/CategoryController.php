<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
      $categories = $this->getDoctrine()->getRepository(Category::class)->find([],['name'=>'ASC']);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/json", name="category_json")
     */
    public function index_json()
    {
      $categories = $this->getDoctrine()
      ->getRepository(Category::class)
      //->findAll()
      ->findAllAssoc();
      return new Response(json_encode($categories));
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {
      $category = new Category();
      $form = $this->createForm(CategoryType::class,$category);
      $form->handleRequest($request);
      if($form->isSubmitted()){
        $category=$form->getData();
        //on va ajouter cet objet en bas de donnée

        $em= $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
      }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/category/{id}/edit", name="category_edit")
     */
    public function edit($id, Request $request)
    {
      //Elaboration de la connexion
      $em = $this->getDoctrine()->getManager();
      //recuperation des données du pays a modifier
      $category=$em->getRepository(Category::class)->find($id);
      $form= $this->createForm(CategoryType::class,$category);
      $form->handleRequest($request);
      if($form->isSubmitted()){
        //modifie l'objet avec les données postées
        $question = $form->getData();
        $em->flush();
        return $this->redirectToRoute('category');
      }

      return $this->render('category/edit.html.twig',array(
        'form'=>$form->createView()

      ));

    }

    /**
     * @Route("/categoy/{id}/delete", name="category_delete")
     */
    public function delete($id)
    {
    $em = $this->getDoctrine()->getManager();
    $category = $this->getDoctrine()
    ->getRepository(Category::class)
    ->find($id);
    $em->remove($category);
    $em->flush();
    return $this->redirectToRoute('category');
  }
}
