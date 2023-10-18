<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Form\ProgrammeType;
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
                    'class' => 'form-control'
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
            // ->add('formation', EntityType::class, [
            //     'class' => Formation::class,
            //     'attr' =>[ 
            //         'class' => 'form-control'
            //     ]
            // ])
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
            
            ->add('Valider', SubmitType::class,[
            'attr' =>[ 
                'class' => 'form-control'
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
