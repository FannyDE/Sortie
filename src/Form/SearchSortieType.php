<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus_id', TextType::class,
            ['label' => 'Campus : '])
            ->add('startDate', DateType::class,
            ['label' => 'Enter le : '])
            ->add('endDate', DateType::class,
            ['label' => 'Et le : '])
            ->add('organizer', CheckboxType::class,
            ['label' => 'Sortie dont je suis l\'organisateur/trice'])
            ->add('registered', CheckboxType::class,
            ['label' => 'Sortie auxquelles je suis inscrit/e'])
            ->add('notRegistered', CheckboxType::class,
            ['label' => 'Sortie auxquelles je ne suis pas inscrit/e'])
            ->add('pastEvents', CheckboxType::class)
            ->add('submit', SubmitType::class, ['label' => 'Recherche'])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
