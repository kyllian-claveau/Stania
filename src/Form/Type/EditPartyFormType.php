<?php

namespace App\Form\Type;

use App\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EditPartyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('time', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('weather')
            ->add('teamHomeOdds', NumberType::class, [
                'required' => false,
                'scale' => 2, // Spécifie 2 chiffres après la virgule
            ])
            ->add('drawOdds', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
            ->add('teamAwayOdds', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
        ->add('teamHome', EntityType::class, [
            'class' => 'App\Entity\Team',
            'choice_label' => 'name',
        ])
        ->add('teamAway', EntityType::class, [
            'class' => 'App\Entity\Team',
            'choice_label' => 'name',
        ])
        ->add('homeScore', IntegerType::class, [
            'required' => false,
        ])
        ->add('awayScore', IntegerType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
        ]);
    }
}