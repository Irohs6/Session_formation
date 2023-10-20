<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SessionAddStagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                },
                'label' => 'Stagiaire',
                'choice_label' => 'nom',
                'attr' =>[ 
                    'class' => 'form-control'
                ]
                ]);
    }
        

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StagiaireRepository::class,
        ]);
    }
}
