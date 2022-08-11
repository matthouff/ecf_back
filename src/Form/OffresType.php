<?php

namespace App\Form;

use App\Entity\Offres;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre du poste*"
            ])
            ->add('type_contrat', ChoiceType::class, [
                'label' => "Type de contrat*",
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Alternance' => 'Alternance',
                    'Stage' => 'Stage',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du poste*",
                'required' => true
            ])
            ->add('profil_desc', TextareaType::class, [
                'label' => "Description du profil recherché*",
                'required' => true
            ])
            ->add('profil_comp', TextType::class, [
                'label' => "Compétences recherchés",
                'required' => false
            ])
            ->add('poste_desc', TextareaType::class, [
                'label' => "Description du poste*",
                'required' => true
            ])
            ->add('poste_mission', TextareaType::class, [
                'label' => "Les missions",
                'required' => false
            ])
            ->add('website_offre', TextType::class, [
                'label' => "Site de l'offre",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
