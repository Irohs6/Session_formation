<?php

namespace App\Form;

use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use App\Form\StagiaireType;
use App\Form\ProgrammeSessionType;
use App\Form\StagiaireSessionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
            ])
            
            ->add('stagiaires')
            // ->add('programmes', CollectionType::class, [
            //     'entry_type' => ProgrammeSessionType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
                 
            // ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
