<?php

/*
 * strangelove
 */

class BsonResponseTest extends PHPUnit\Framework\TestCase
{

    public function testSerialization()
    {
        $entity = new Tests\Fixtures\Quark('u', 2 / 3);
        $sut = new \Trismegiste\Strangelove\Http\BsonResponse($entity);
        ob_start();
        $sut->sendContent();
        $dump = ob_get_clean();

        $this->assertStringContainsString('0.666', $dump);
        $this->assertStringContainsString('"u"', $dump);
    }

}
