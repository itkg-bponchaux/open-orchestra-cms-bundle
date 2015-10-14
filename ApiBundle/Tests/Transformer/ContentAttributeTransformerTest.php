<?php

namespace OpenOrchestra\ApiBundle\Tests\Transformer;

use OpenOrchestra\ApiBundle\Transformer\ContentAttributeTransformer;
use Phake;

/**
 * Class ContentAttributeTransformerTest
 */
class ContentAttributeTransformerTest extends \PHPUnit_Framework_TestCase
{
    protected $contentAttribute;
    protected $transformer;

    /**
     * set Up
     */
    public function setUp()
    {
        $this->contentAttribute = Phake::mock('OpenOrchestra\ModelInterface\Model\ContentAttributeInterface');
        $this->transformer = new ContentAttributeTransformer();
    }

    /**
     * test transform
     */
    public function testTransform()
    {
        $contentAttributeString = 'ContentAttributeString';
        Phake::when($this->contentAttribute)->getName()->thenReturn($contentAttributeString);
        Phake::when($this->contentAttribute)->getValue()->thenReturn($contentAttributeString);
        Phake::when($this->contentAttribute)->getStringValue()->thenReturn($contentAttributeString);

        $facade = $this->transformer->transform($this->contentAttribute);

        $this->assertSame($contentAttributeString, $facade->name);
        $this->assertSame($contentAttributeString, $facade->value);
        $this->assertSame($contentAttributeString, $facade->stringValue);
    }

    /**
     * Test Exception transform with wrong object a parameters
     */
    public function testExceptionTransform()
    {
        $this->setExpectedException('OpenOrchestra\ApiBundle\Exceptions\TransformerParameterTypeException');
        $this->transformer->transform(Phake::mock('stdClass'));
    }

    /**
     * test getName
     */
    public function testGetName()
    {
        $this->assertSame('content_attribute', $this->transformer->getName());
    }
}
