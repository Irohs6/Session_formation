<?php

namespace App\Form;

use App\Entity\Stagiaire;
use App\Form\SessionStagiaireType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('prenom', TextType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('dateNaissance', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[ 
                    'class' => 'form-control'

                ]
            ])
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    'Autre' => 'Autre',
                ],
                'expanded' => true, // Affiche les genres comme des checkboxes
                'multiple' => false, // Permet de n'en sélectionner qu'un seul
                
            ])
           

            ->add('ville', TextType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('tel', TextType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
