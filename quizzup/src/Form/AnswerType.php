<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Question;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('correct')
              ->add('question',EntityType::class, array(
             'class'=>Question::class,
              'choice_label'=>'label'
            ))
            //->add('category',EntityType::class,array(
              //'class'=>Category::class,
              //'choice_label'=>'name'
            //))
            ->add('submit',SubmitType::class,array(
              'label'=>'Enregistrer'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
