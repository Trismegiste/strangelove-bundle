<?php

use Symfony\Component\Form\Test\TypeTestCase;
use Trismegiste\Toolbox\MongoDb\Form\MongoDateType;

class DateMongoTransformerTest extends TypeTestCase
{

    public function testObjectWithNull()
    {
        $form = $this->factory->create(MongoDateType::class, null, ['input' => 'datetime']);
        $form->submit(null);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(null, $form->getData());
    }

    public function testStringWithNull()
    {
        $form = $this->factory->create(MongoDateType::class, null, ['input' => 'string']);
        $form->submit(null);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('', $form->getData());
    }

    public function testObjectWithEmptyArray()
    {
        $form = $this->factory->create(MongoDateType::class);
        $form->submit([]);
        $this->assertTrue($form->isSynchronized(), $form->getErrors(true, true));
        $this->assertEquals(null, $form->getData());
    }

    public function testArrayWithEmptyArray()
    {
        $form = $this->factory->create(MongoDateType::class, null, ['input' => 'array']);
        $form->submit(null);
        $this->assertTrue($form->isSynchronized(), $form->getErrors(true, true));
        $this->assertEquals(['year' => '', 'month' => '', 'day' => ''], $form->getData());
    }

}
