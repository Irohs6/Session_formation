<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPlaceTotal', IntegerType::class,[
                'attr' =>[ 
                    'class' => 'form-control',
                    'min' => 1, 'max' => 100,                   
                ]
            ])
            ->add('dateDebut', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
            ->add('intitule', TextType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
            ])
          
            ->add('programmes', CollectionType::class,[
                //la collection attend l'élément qu'elle entrera dans le form mais pas forcément un formulaire
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                //autoriser l'ajout de nouveau élément qui seront persiter grace au cascade persit sur l'élément Programme
                //ca va va activer un data prototype qui sera un attribut html qu'on pourra manipuler en js
                'allow_add' => true, //autorise l'ajout 
                'allow_delete' => true, //autorise la suppression
                'by_reference' => false,// il est obligatoire car Session n'a pas de setProgramme mais c'est Programme qui contient setSession
                //Programme est propriétaire de la relations. Pour éviter un mapping => false on est obligé de rajouter un by_reference => false
            ])
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'multiple' => true, // Changez ici à false pour n'autoriser qu'une seule session à la fois
                'expanded' => true, // Changez ici à false
                'attr'=>[
                    'class' => 'form-check form-check-inline',
                ]
            ])
            
            ->add('Valider', SubmitType::class,[
            'attr' =>[ 
                'class' => 'button'
            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
