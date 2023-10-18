<?php

namespace App\Form;

use App\Entity\Session;

use App\Form\ModuleSessionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddModulesSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('programmes', CollectionType::class, [
                'entry_type' => ModuleSessionType::class,
                'entry_options' => ['label' => false], // Masquez le label pour chaque ProgrammeType
                'allow_add' => true, // Autorisez l'ajout dynamique de formulaires ProgrammeType
                'allow_delete' => true, // Autorisez la suppression de formulaires ProgrammeType
                'by_reference' => false, // Assurez-vous que chaque élément est géré par référence
            ])

            ->add('Valider', SubmitType::class,[
                'attr' =>[ 
                    'class' => 'form-control'
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}