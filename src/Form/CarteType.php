<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\Joueur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\CallbackTransformer;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min-length' => '2',
                    'max-length' => '50'
                ],
                'label' => 'nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
                'empty_data' => 'Choisir un nom'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la carte',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min-length' => '20',
                    'max-length' => '300'
                ],
                'label' => 'description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 20, 'max' => 300]),
                    new Assert\NotBlank(),
                ],
                'empty_data' => 'Choisir un nom'
            ])
            ->add('chiffre', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\NotBlank(),
                ],
                'empty_data' => 'Choisir un chiffre',
                
            ])
            ->add('Ajouter', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Ajouter une carte'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}
