<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * 
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

}
