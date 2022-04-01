<?php

use Symfony\Component\Form\Test\TypeTestCase;
use Trismegiste\Toolbox\MongoDb\Form\MongoDateType;
use Trismegiste\Toolbox\MongoDb\Type\MongoDateTime;

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

    public function testCreateObjectWithDate()
    {
        $form = $this->factory->create(MongoDateType::class);
        $form->submit(['year' => 2022, 'month' => 2, 'day' => 22]);
        $this->assertTrue($form->isSynchronized(), $form->getErrors(true, true));
        $this->assertInstanceOf(MongoDateTime::class, $form->getData());
        $this->assertEquals(new MongoDateTime('2022-02-22'), $form->getData());
    }

    public function testViewWithDate()
    {
        $form = $this->factory->create(MongoDateType::class, new MongoDateTime('2022-04-01'));
        $view = $form->createView();

        $this->assertEquals(2022, $view->children['year']->vars['value']);
        $this->assertEquals(4, $view->children['month']->vars['value']);
        $this->assertEquals(1, $view->children['day']->vars['value']);
    }

}
