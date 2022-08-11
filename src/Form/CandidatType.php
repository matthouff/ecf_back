<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        switch ($options["step"]){
            case 'update_profile':
                $this->newCandidat($builder);
                break;
            default:
                $this->newCandidat($builder);
                $this->cvCandidat($builder);
        }
    }

    private function newCandidat(FormBuilderInterface $builder){
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom*'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom*'
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Tel'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email*'
            ])
        ;
        return $builder;
    }

    private function cvCandidat(FormBuilderInterface $builder)
    {
        $builder
            ->add('cv_candidat', FileType::class, [
                'label' => 'Votre CV*',
                'mapped' => false,
                'required' => true
            ])
        ;

        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
            'step' => null
        ]);
    }
}
