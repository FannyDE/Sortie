<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SortieType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager) {}
        
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription : ',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax', null, [
                'label' => 'Nombre de places : '
            ])
            ->add('duree', null, [
                'label' => 'Durée : '
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description & infos : '
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'disabled' => true,
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez une ville',
                'mapped' => false, // Ne pas mapper ce champ à l'entité Sortie
            ])
            ->add('etat', HiddenType::class)
            ->add('organisateur', HiddenType::class)
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'save'
                ]
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => [
                    'class' => 'publish'
                ]

            ])
            ->add('annuler', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'cancel'
                ]
            ]);

            $builder->get('ville')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event)
                {
                    $form = $event->getForm();
                    
                    $form->getParent()->add('lieu', EntityType::class, [
                        'label' => 'Lieu : ',
                        'class' => Lieu::class,
                        'choices' => $form->getData()->getLieux(),
                        'placeholder' => 'Sélectionnez un lieu',
                        'constraints' => new NotBlank(['message' => 'Merci de choisir un lieu.'])
                    ]);                    
                }
            );
            
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event)
                {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $lieu = $data->getLieu();
                    // $ville = $form->get('ville');
                    // dump($ville);
            
                    if ($lieu)
                    {
                        $form->get('ville')->setData($lieu->getVille());
            
                        $form->add('lieu', EntityType::class, [
                            'label' => 'Lieu : ',
                            'class' => Lieu::class,
                            'choices' => $lieu->getVille()->getLieux(),
                            'placeholder' => 'Sélectionnez un lieu',
                            'constraints' => new NotBlank(['message' => 'Merci de choisir un lieu.'])
                        ]);
                    } else {
                        $form->add('lieu', EntityType::class, [
                            'label' => 'Lieu : ',
                            'class' => Lieu::class,
                            'choices' => [],
                            'placeholder' => 'Sélectionnez un lieu',
                            'constraints' => new NotBlank(['message' => 'Merci de choisir un lieu.'])
                        ]);
                    }
                    
            
                }
            );
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

}
