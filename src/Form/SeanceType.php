<?php

namespace App\Form;

use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la séance',
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Length(['max' => 100])
                ]
            ])
            ->add('groupe', TextType::class, [
                'label' => 'Groupe',
                'constraints' => [new NotBlank()]
            ])
            ->add('jour', TextType::class, [
                'label' => 'Jour',
                'constraints' => [new NotBlank()]
            ])
            ->add('heureDebut', TimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
                'constraints' => [new NotBlank()]
            ])
            ->add('heureFin', TimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
                'constraints' => [new NotBlank()]
            ])
            ->add('module', TextType::class, [
                'label' => 'Module',
                'constraints' => [new NotBlank()]
            ])
            ->add('salle', TextType::class, [
                'label' => 'Salle',
                'constraints' => [new NotBlank()]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => Seance::class]);
    }
}