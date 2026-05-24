<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => 'nom',   // affiche le nom de l’étudiant
                'label' => 'Étudiant',
                'placeholder' => 'Sélectionner un étudiant',
                'constraints' => [new NotBlank()]
            ])
            ->add('module', TextType::class, [
                'label' => 'Module',
                'constraints' => [new NotBlank()]
            ])
            ->add('valeur', NumberType::class, [
                'label' => 'Note',
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 0, 'max' => 20])
                ]
            ])
            ->add('groupe', TextType::class, [
                'label' => 'Groupe',
                'constraints' => [new NotBlank()]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => Note::class]);
    }
}