<?php

namespace App\Form;

use App\DTO\FiltersDTO;
use App\Entity\Etats;
use App\Entity\Sites;
use App\Repository\SitesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FiltersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('search', TextType::class, [
                'required' => false
            ])
            ->add('sites', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir le site',
                'required' => false,
                'data' => $user->getIdSite()
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => array_reduce(
                    Etats::cases(),
                    function($acc, $case) {
                        $acc[$case->value] = $case;
                        return $acc;
                    },
                    []
                ),
                'placeholder' => 'Choisir le status',
                'required' => false,
            ])
            ->add('dateDebut', DateType::class,
                [ 'widget' => 'single_text',
                    'required' => false,
                ])
            ->add('dateFin', DateType::class,
                [ 'widget' => 'single_text',
                    'required' => false,
                ])
            ->add('organisateurFilter', CheckboxType::class, [
                'required' => false,
            ])
            ->add('inscritFilter', CheckboxType::class, [
                'required' => false,
            ])
            ->add('pasInscritFilter', CheckboxType::class, [
                'required' => false,
            ])
            ->add('passeFilter', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltersDTO::class,
        ]);
        $resolver->setRequired('user');
    }
}