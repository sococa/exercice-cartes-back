<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class JoueurCarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('joueur', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control',
                    'min-length' => '2',
                    'max-length' => '50'
                ],
                'label' => 'nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('carte', EntityType::class, [
                'class' => Carte::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control',
                    'min-length' => '2',
                    'max-length' => '50'
                ],
                'label' => 'nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('quantite', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\NotBlank(),
                ],
                'empty_data' => 'Choisir la quantité',
                
            ])

            ->add('Associer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Ajouter la carte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
