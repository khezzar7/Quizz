<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('difficulty',ChoiceType::class,array(
              'choices'=>array(
                'Facile'=>1,
                'Intermédiaire'=>2,
                'Difficile'=>3,
                'Pro'=>4
              )
            ))
            ->add('category',EntityType::class,array(
              'class'=>Category::class,
              'choice_label'=>'name'
            ))
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
