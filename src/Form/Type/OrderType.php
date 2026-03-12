<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\OrderDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Bridge\Doctrine\Form\Type as DoctrineType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', CoreType\EmailType::class, ['label' => 'Email',])
            ->add('name', CoreType\TextType::class, ['label' => 'Name or Company',])
            ->add('address', CoreType\TextType::class, ['label' => 'Where to deliver?',])
            ->add('menu', DoctrineType\EntityType::class, ['label' => 'Choose your pizza', 'class' => 'App\Entity\Menu', 'choice_label' => 'name'])
            ->add('quantity', CoreType\IntegerType::class, ['label' => 'How many pizzas?', 'attr' => ['min' => 1, 'max' => 20],])
            ->add('save', CoreType\SubmitType::class, ['label' => 'Order pizza!',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderDTO::class,
        ]);
    }
}
