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


class UtilisateurType extends AbstractType
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
            ->add('telephone',null, [
                'label' => 'Téléphone* :',
            ])
            ->add('email',null, [
                'label' => 'Email* :',
            ])
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Les mots de passe doivent correspondre.',
//                'required' => true,
//                'first_options'  => ['label' => 'Mot de passe* :'],
//                'second_options' => ['label' => 'Confirmation* :'],
//            ])
            ->add('idSite', EntityType::class,[
                'class' => Sites::class,
                'choice_label' => 'nom',
                'label' => 'Ville de rattachement* :'
            ])
            ->add('imageProfil', FileType::class, [
                'label' => 'Photo (PNG, JPG, BMP)* :)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/bmp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un PNG ou un JPG',
                    ])
                ],
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
