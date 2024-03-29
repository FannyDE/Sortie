<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, ['label' => 'Photo de profil', 'mapped' =>false, 'required' => false])
            ->add('email', EmailType::class, ['attr' => ['class' => 'custom-class']])
            ->add('pseudo', TextType::class, ['required' => false, 'attr' => ['class' => 'custom-class']])
            ->add('nom', TextType::class, ['attr' => ['class' => 'custom-class']])
            ->add('prenom', TextType::class, ['attr' => ['class' => 'custom-class']])
            ->add('tel', TextType::class, ['attr' => ['class' => 'custom-class']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }


}
