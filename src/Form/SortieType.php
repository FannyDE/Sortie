<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SortieType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager) {}
        
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner le nom du lieu'
                    ]),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'html5' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une durée en minutes de la sortie'
                    ])
                ] 
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription : ',
                'html5' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une durée en minutes de la sortie'
                    ])
                ] 
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une limite de participants'
                    ]),
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Limite à 99999 participants'
                    ])
                ] 
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une durée en minutes de la sortie'
                    ]),
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Limite à 99999 minutes'
                    ])
                ] 
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description & infos : ',
                'attr' => [
                    'rows' => 5
                ],
                'constraints' => [
                    new Length([
                        'max' => 4096,
                        'maxMessage' => 'La limite est de {{ limit }} caractères.'
                    ])
                ]
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une ville'
                    ])
                ],
                'mapped' => false, // Ne pas mapper ce champ à l'entité Sortie,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC');
                }
            ])
            ->add('etat', HiddenType::class)
            ->add('organisateur', HiddenType::class)
            ->add('motifAnnulation', TextareaType::class, [
                'label' => 'Motif : ',
                'attr' => [
                    'rows' => 5
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un motif à l\'annulation'
                    ])
                ],
            ])
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
            ->add('supprimer', SubmitType::class, [
                'label' => 'Supprimer la sortie',
                'attr' => [
                    'class' => 'delete'
                ]
            ]);
            
            // Ajout d'un écouteur sur le formulaire pour mettre à jour la liste des lieux en fonction de la ville sélectionnée (associé à une requête AJAX)
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event)
                {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $lieu = $data->getLieu();
            
                    // Si il y a un lieu de sélectionné, c'est qu'un ville a été sélectionnée, donc la liste des lieux est mise à jour selon la ville correspond au lieu sélectionné
                    if ($lieu)
                    {
                        $form->get('ville')->setData($lieu->getVille());
            
                        $form->add('lieu', EntityType::class, [
                            'label' => 'Lieu : ',
                            'class' => Lieu::class,
                            'choices' => $lieu->getVille()->getLieux(),
                            'placeholder' => 'Sélectionnez un lieu',
                            'constraints' => new NotBlank(['message' => 'Veuillez choisir un lieu.']),
                        ]);
                    } 
                    // Sinon la liste déroulante des lieux est vide (en attendant qu'une ville soit sélectionnée)
                    else {
                        $form->add('lieu', EntityType::class, [
                            'label' => 'Lieu : ',
                            'class' => Lieu::class,
                            'choices' => [],
                            'placeholder' => 'Sélectionnez un lieu',
                            'constraints' => new NotBlank(['message' => 'Veuillez choisir un lieu.']),
                        ]);
                    }
                    
            
                }
            );
            
            // Ajout d'un écouteur sur le sous-formulaire VILLE pour mettre à jour la liste des lieux en fonction de la ville sélectionnée (associé à une requête AJAX)
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
                        'constraints' => new NotBlank(['message' => 'Veuillez choisir un lieu.']),
                    ]);                    
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
