<?php

namespace App\Controller;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Entity\Answer;
use App\Entity\Category;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="question")
     */
    public function index(Request $request)
    {
      //Recupération des paramêtres d'URL
      $category = $request->query->get('category');
      $difficulty = $request->query->get('difficulty');

      $questions = $this->getDoctrine()->getRepository(Question::class)->findByFilters($category,$difficulty);

      foreach ($questions as $question) {

      }
      //filtres de recherche
      $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

      $difficulty = array(
        'facile' => 1,
        'Intermédiaire' => 2,
        'Difficile'=> 3,
        'Pro'=> 4
      );



      var_dump($category);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'categories'=>$categories,
            'difficulty'=>$difficulty
        ]);
    }
    /**
     * @Route("/question/json", name="question_json")
     */
    public function index_json(Request $request)
    {
      //Recupération des paramêtres d'URL
      $category = $request->query->get('category');
      $difficulty = $request->query->get('difficulty');

      $questions = $this->getDoctrine()
      ->getRepository(Question::class)
      //->findByFilters($category,$difficulty);
      ->findByFiltersAssoc($category,$difficulty)
      ;
      //var_dump($questions);

      if(sizeof($questions)===0){
        return new JsonResponse(['result'=>null]);
      }else {
          return new JsonResponse(['result'=>$questions]);
      }


    }

    private function exists($search,$arr){
      $found = false;
      for ($i=0; $i <sizeof($arr) ; $i++) {
        if($search == $arr[$i]['id']){
          $found = true;
          break;//valeur trouvée on sort de la boucle
        }
      }
      return $found;
    }
    /**
     * @Route("/question/test", name="question_test")
     */
     public function test()
     {
       $question_id=3;
       $question_answers =[1,11];
       $success = true;
        //tableau des bonnes reponses
       $answers = $this->getDoctrine()
       ->getRepository(Answer::class)
       ->findCorrectByQuestionId($question_id);

       if(sizeof($question_answers) !== sizeof($answers)){
         $success = false;
       }else {
         //tableau de même longueur
         foreach ($question_answers as $question_answer) {

            //a chaque itération, vérifier que l'id de la reponse
            //existe dans le tableau des bonnes reponses
          if (!$this->exists($question_answer,$answers)){
            $success=false;
          }
         }
       }
       return $this->render('test.html.twig',array(
         'answers'=>$answers,
         'success'=>$success
       ));
     }

    /**
     * @Route("/question/client/check", name="question_client_check",
     *
     *)
     */
     public function client_check(Request $request)
     {
       //accèes au body de la requête Ajax en POST
       //grâce à la méthode getContent()
       $success = true;
        $request_body=json_decode($request->getContent());
         $question_id=$request_body->question_id;
        $questions_answers = $request_body->answers;
        //vérifier les réponses données par le client_check//en rapport avec la question identifiée

        $answers = $this->getDoctrine()
        ->getRepository(Answer::class)
        ->findCorrectByQuestionId($question_id);

        if(sizeof($questions_answers) !== sizeof($answers)){
          $success = false;
        }else {
          //tableau de même longueur
          foreach ($questions_answers as $question_answer) {

             //a chaque itération, vérifier que l'id de la reponse
             //existe dans le tableau des bonnes reponses
           if (!$this->exists($question_answer,$answers)){
             $success=false;
           }

          }
        }//fin if

       return new JsonResponse(
         array('success'=>$success,
         'answers'=>$answers)
       );
     }
    /**
     * @Route("/question/add", name="question_add")
     */
    public function add(Request $request)
    {
      $question = new Question();
      $form= $this->createForm(QuestionType::class,$question);
      $form->handleRequest($request);
      if($form->isSubmitted()){
        $question=$form->getData();
        $em= $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        return $this->redirectToRoute('question');
      }

        return $this->render('question/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/question/{id}/edit", name="question_edit")
     */
    public function edit($id, Request $request)
    {
      //Elaboration de la connexion
      $em = $this->getDoctrine()->getManager();
      //recuperation des données du pays a modifier
      $question=$em->getRepository(Question::class)->find($id);
      $form= $this->createForm(QuestionType::class,$question);
      $form->handleRequest($request);
      if($form->isSubmitted()){
        //modifie l'objet avec les données postées
        $question = $form->getData();
        $em->flush();
        return $this->redirectToRoute('question');
      }

      return $this->render('question/edit.html.twig',array(
        'form'=>$form->createView()

      ));

    }
    /**
     * @Route("/question/{id}/delete", name="question_delete")
     */
    public function delete($id)
    {
    $em = $this->getDoctrine()->getManager();
    $question = $this->getDoctrine()
    ->getRepository(Question::class)
    ->find($id);
    $em->remove($question);
    $em->flush();
    return $this->redirectToRoute('question');
  }
}
