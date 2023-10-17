<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie* :"
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date de debut de la sortie* :',
                'widget' => 'single_text',
            ])
            ->add('dateRetour', DateTimeType::class, [
                'mapped' => false,
                'label' => 'Date de fin de la sortie* :',
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => "Date de fin d'inscription* :",
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => "Nombre de places* (minimum 2) :",
            ])
            ->add('infosSortie', TextType::class, [
                'label' => "Description et infos :",
            ])
            ->add('lieux', EntityType::class, [
                'label' => "Lieu* :",
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false,
                'by_reference' => false,
            ])
            ->add('ville', EntityType::class, [
                'label' => "Ville* :",
                'mapped' => false,
                'class' => Villes::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
