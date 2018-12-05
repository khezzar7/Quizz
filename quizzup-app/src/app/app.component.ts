import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';

interface Category{
  name:string;
}
interface ResponseQuizz{
  result:any;
}
interface ResponseToClientChoice{
  success:boolean;
  answers:any[];

}
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'Quizzup-App';
  answer= null;
  imgUrl= null;
  url='';
  questions:any= null;
  categories:Category[]=[];
  category=0;
  difficulty=0;
  isQuizzupRunning: boolean = false;
  questionIndex: number = 0;
  clientChoice = {
    question_id:null,
    answers:[]
  };
  isClientChoiceSent:boolean = false;
  feedback: string ='';
  isServerResponseReceived:boolean = false;
  appear:string='block';

  noResult:boolean=false;
  score: number=0;
  feedbackColor: string;
  goodAnswers = [];
  resultat=0;

  constructor(private http: HttpClient){
  //this.url='https://yesno.wtf/api';
  this.url='http://localhost:8000/category/json'
    this.http.get(this.url)
    .subscribe((categories:Category[]) => {
      this.categories = categories;
    });

  }
  runQuizzup(){
    //charger une collection de question/réponses
    //on doit interroger une route(endpoint) en Ajax founissant des données
    //console.log(this.category);
    //console.log(this.difficulty);
    let url: string='http://localhost:8000/question/json';
    //ne pas oublier le this
    url +=`?category=${this.category}&difficulty?=${this.difficulty}`;

    this.http.get(url).subscribe((res:ResponseQuizz)=>{


      //ittération sur les clefs de l'objet


        if(!res.result){
          //Aucune réponse treouvées par le server en rapport avec les filtres
          this.noResult=true;
        }else{
          this.noResult= false;
          let questions =[];
          for( let k in res.result){
        let question = {
          'id': k,
          'label': res.result[k].questions.label,
          'answers' : res.result[k].answers
        };
        questions.push(question);
      }//fin de for
      this.appear='none';
      this.questions = questions;
      this.isQuizzupRunning = true;
      this.clientChoice.question_id= this.questions[this.questionIndex].id;
    });
  }

  validQuestion(){
    this.isClientChoiceSent= true;
    //requete au serveur pour verification du choix client
    let url='http://localhost:8000/question/client/check';

    this.http.post(url, this.clientChoice).subscribe((res:ResponseToClientChoice)=>{
      console.log(res);
      if(res.success){

        //le client a fourni la/les bonne(s) réponse(s)
        this.feedback = 'Bien joué! ';
        this.feedbackColor = 'green';
        this.score++;

      }else{
        //le client a échoué
        this.feedback ='Raté! ';
          this.feedbackColor = 'red';

      }
      this.isServerResponseReceived=true;
        this.goodAnswers = res.answers;

    })
  }

  checkAnswer(question_id:number, answer_id:number){
      let index=this.clientChoice.answers.indexOf(answer_id)
    if(index === -1){

      //id de la réponse absente/non présente dans le tableau  clientChoice.answers alors on le push
      this.clientChoice.answers.push(answer_id);
    }else{
      // id de la reponse déjà présente dans le tableau clientChoice.answsers alors on le splice
      this.clientChoice.answers.splice(index, 1);

    }
    console.log(this.clientChoice);

  }
  nextQuestion(){
    this.questionIndex++;//passage a la question suivante
    this.clientChoice.question_id=
    this.questions[this.questionIndex].id;
    this.clientChoice.answers=[];

    this.feedback = "";
    this.isClientChoiceSent=false;
    this.isServerResponseReceived=false;


  }

  restart(){
    this.isQuizzupRunning=false;
    this.category=0;
    this.difficulty=0;
    this.score=0;
    this.appear='block';
    this.feedback='';
    this.questionIndex=0;
    this.feedbackColor= '#000';
    this.isClientChoiceSent=false;
    this.isServerResponseReceived=false;
    this.clientChoice.answers=[];
    this.goodAnswers=[];
  }

  isCorrect(id:number):boolean{
    if(this.goodAnswers){
      //parcours des bonnes réponses
      for(let i = 0; i< this.goodAnswers.length; i++){
        if(id == this.goodAnswers[i].id){
          //l'id de la reponse est trouvé dans le tableau des reponses
          //isCorrect renvoi true immédiatemment
          return true;
        }
      }
    }else{
     return false;
    }

  }

}
