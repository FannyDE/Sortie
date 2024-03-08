<?php

namespace App\Form;

use App\DTO\SearchDTO;
use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'attr' => ['class' => 'custom-class'],
            'label' => 'Campus : ',
            'required' => false,
            'choice_label' => 'nom'
        ])
            ->add('search', TextType::class,
            ['label' => 'Le nom de la sortie contient :','attr' => ['class' => 'custom-class']])
            ->add('startDate', DateType::class,
            ['label' => 'Entre le :','attr' => ['class' => 'custom-class']])
            ->add('endDate', DateType::class,
            ['label' => 'Et le :','attr' => ['class' => 'custom-class']])
            ->add('organizer', CheckboxType::class,
            ['label' => 'Sortie dont je suis l\'organisateur/trice',
                'attr' => ['class' => 'custom-class'],
                'required' => false])
            ->add('registered', CheckboxType::class,
            ['label' => 'Sortie auxquelles je suis inscrit/e',
                'attr' => ['class' => 'custom-class'],
                'required' => false])
            ->add('notRegistered', CheckboxType::class,
            ['label' => 'Sortie auxquelles je ne suis pas inscrit/e',
                'attr' => ['class' => 'custom-class'],
                'required' => false])
            ->add('pastEvents', CheckboxType::class,
            ['label' => 'Sortie passées',
                'attr' => ['class' => 'custom-class'],
                'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Recherche','attr' => ['class' => 'custom-class']])
            ->getForm();
        ;

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchDTO::class,
            'method' =>'GET',

        ]);
    }
}
