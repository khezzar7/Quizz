<?php

namespace App\Controller;
use App\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ResultController extends AbstractController
{



    /**
     * @Route("/result", name="result_new",methods={"POST"})
     */
    public function new(Request $request)
    {
        $request_body = json_decode($request->getContent());
        $name= $request_body->name;
        $score = $request_body->score;
        $em = $this->getDoctrine()->getManager();
        //$date = date('Y-m-d   H:i:s');
        //la date est calculé dans le constructeur de result.php
        $result = new Result($name,$score);
        $em->persist($result);
        $em->flush();
        return new JsonResponse($result->getId());
       }

       /**
        * @Route("/result", name="result_list",methods={"GET"})
        */
       public function list()
       {
         $results = $this->getDoctrine()
         ->getRepository(Result::class)
         ->findBy([],['score'=>'DESC']);
          return$this->render('result/index.html.twig',[
            'results'=>$results

          ]);
       }
}
