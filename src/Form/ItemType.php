<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('category',ChoiceType::class,[
                'choices'  => [
                    'Rot' => 'Rot',
                    'Grun' => 'Grun',
                    'Gelb' => 'Gelb',
                ],'attr' => [
                    'class' => 'form-control selectpicker'
                ]
            ])
            ->add('image',FileType::class,[
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('price',NumberType::class,[
                'attr' => [
                    'class' => "form-control"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
