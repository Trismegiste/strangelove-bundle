<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget for replacing DateType
 */
class BsonDateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new DateMongoTransformer(), true);
    }

    public function getParent()
    {
        return DateType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('widget', 'choice');
    }

}
