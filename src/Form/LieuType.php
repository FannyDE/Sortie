<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Lieu : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner le nom du lieu'
                    ]),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner la rue du lieu'
                    ]),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('codePostal', TextType::class,[
                'label' => 'Code postal : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner le code postal du lieu'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit être composé de {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner la latitude du lieu'
                    ]),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner la longitude du lieu'
                    ]),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'choice_label' => 'nom'
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'save'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
