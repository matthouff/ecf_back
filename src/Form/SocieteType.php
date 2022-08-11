<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        switch ($options["step"]){
            case 'update_profile':
                $this->logoUpdate($builder);
                $this->updateProfile($builder);
                break;
            case 'update_password':
                $this->updatePassword($builder);
                break;
            default:
                $this->newSociete($builder);
        }
    }

    private function newSociete(FormBuilderInterface $builder)
    {
        $builder
            ->add('logo_color', ColorType::class, [
                'label' => 'Couleur de fond du logo',
                'required' => false
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo*',
                'mapped' => false,
                'required' => true
            ]);
        $this->updateProfile($builder);
        $this->updatePassword($builder);

        return $builder;
    }

    private function logoUpdate(FormBuilderInterface  $builder){
        $builder
            ->add('logo_color', ColorType::class, [
                'label' => 'Couleur de fond du logo',
                'required' => false
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'mapped' => false,
                'required' => false
            ]);
    }
    private function updateProfile(FormBuilderInterface $builder)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom*',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville*'
            ])
            ->add('website', TextType::class, [
                'label' => 'Site de la société',
                'required' => false
            ])
            ->add('lastname_contact', TextType::class, [
                'label' => 'Nom du contact',
                'required' => false
            ])
            ->add('firstname_contact', TextType::class, [
                'label' => 'Prénom du contact',
                'required' => false
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Mail du contact',
                'required' => false
            ])
            ->add('mobile_contact', TextType::class, [
                'label' => 'Téléphone du contact',
                'required' => false
            ])
            ->add('login', TextType::class, [
                'label' => 'Login*'
            ])
        ;

        return $builder;
    }

    private function updatePassword(FormBuilderInterface $builder)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Votre mot de passe et sa confirmation ne sont pas identique',
                'first_options' => [
                    'label' => 'Mot de passe*'
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe*'
                ]
            ])
        ;

        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
            'step' => null
        ]);
    }
}
