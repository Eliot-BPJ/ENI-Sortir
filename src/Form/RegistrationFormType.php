<?php

namespace App\Form;

use App\Entity\Sites;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',null, [
                'label' => 'Pseudo* :',
            ])
            ->add('prenom',null, [
                'label' => 'Prénom* :',
            ])
            ->add('nom',null, [
                'label' => 'Nom* :',
            ])
            ->add('email',null, [
                'label' => 'Email* :',
            ])
            ->add('telephone',null, [
                'label' => 'Téléphone* :',
            ])
            ->add('idSite', EntityType::class, [
                'class' => Sites::class,
                'label' => 'Site de rattachement* :',
                "choice_label"=>'nom',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
