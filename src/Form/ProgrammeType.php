<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Programme;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('module', EntityType::class, [
            'class' => Module::class,
            'query_builder' => function (EntityRepository $er): QueryBuilder {
                return $er->createQueryBuilder('m')
                    ->orderBy('m.libele', 'ASC');   
            },
            'label' => 'Module',
            'choice_label' => 'libele',
            'attr' =>[ 
                'class' => 'form-control'
            ]
        ])
                        
        ->add('nbJours', IntegerType::class,[
            'label' => 'Durée en jours',
            'attr' =>[ 
                'min' => 1, 'max' => 100,
                'class' => 'form-control'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
