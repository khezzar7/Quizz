<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\QuestionType;

use App\Form\AnswerType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class AnswerController extends AbstractController
{
    /**
     * @Route("/answer", name="answer")
     */
    public function index()
    {

        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }
    /**
     * @Route("/answer/add", name="answer_add")
     */
    public function add(Request $request)
    {
      $answer= new Answer();

      $form = $this->createForm(AnswerType::class,$answer);
      $form->handleRequest($request);
      if($form->isSubmitted()){
        $answer = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush();


      }
        return $this->render('answer/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/answer/{id}/edit", name="answer_edit")
     */
    public function edit($id, Request $request)
    {
      //Elaboration de la connexion
      $em = $this->getDoctrine()->getManager();
      //recuperation des données du pays a modifier
      $answer=$em->getRepository(Answer::class)->find($id);
      $form= $this->createForm(AnswerType::class,$answer);
      $form->handleRequest($answer);
      if($form->isSubmitted()){
        //modifie l'objet avec les données postées
        $question = $form->getData();
        $em->flush();
        return $this->redirectToRoute('answer');
      }

      return $this->render('answer/edit.html.twig',array(
        'form'=>$form->createView()

      ));

    }
    /**
     * @Route("/answer/{id}/delete", name="answer_delete")
     */
    public function delete($id)
    {
    $em = $this->getDoctrine()->getManager();
    $answer = $this->getDoctrine()
    ->getRepository(Answer::class)
    ->find($id);
    $em->remove($answer);
    $em->flush();
    return $this->redirectToRoute('answer');
  }
}
