<?php

namespace App\Form\Type;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('playerFile',FileType::class)
            ->add('name')
            ->add('firstname')
            ->add('role')
            ->add('number')
        ->add('team', EntityType::class, [
        'class' => Team::class,
        'choice_label' => 'name',
        'placeholder' => 'Choisir une équipe',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}