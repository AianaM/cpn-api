<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 27.08.2018
 * Time: 15:11
 */

namespace App\Form;

use App\Entity\Address;
use App\Entity\MediaObject;
use App\Entity\Realty;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MediaObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'label.file',
                'required' => true,
            ])
            ->add('tags', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'prototype_data' => 'New Tag Placeholder',
                'required' => false,
            ])
            ->add('realties', EntityType::class, [
                'class' => Realty::class,
                'multiple' => true,
                'required' => false,
            ])
            ->add('addresses', EntityType::class, [
                'class' => Address::class,
                'multiple' => true,
                'required' => false,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediaObject::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}