<?php
/**
 * Unit test for \Magento\Framework\Validator\Factory
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Validator\Test\Unit;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    /**
     * @var \Magento\Framework\Module\Dir\Reader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $readerMock;

    /**
     * @var \Magento\Framework\Validator\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validatorConfigMock;

    /**
     * @var \Magento\Framework\Cache\FrontendInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheMock;

    /**
     * @var \Magento\Framework\Json\JsonInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jsonMock;

    /**
     * @var \Magento\Framework\Translate\AdapterInterface
     */
    private $defaultTranslator;

    /**
     * @var \Magento\Framework\Validator\Factory
     */
    private $factory;

    /**
     * @var string
     */
    private $jsonString = '["\/tmp\/moduleOne\/etc\/validation.xml"]';

    /**
     * @var array
     */
    private $data = ['/tmp/moduleOne/etc/validation.xml'];

    protected function setUp()
    {
        $this->defaultTranslator = \Magento\Framework\Validator\AbstractValidator::getDefaultTranslator();

        $this->objectManagerMock = $this->getMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->validatorConfigMock = $this->getMock(
            \Magento\Framework\Validator\Config::class,
            ['createValidatorBuilder', 'createValidator'],
            [],
            '',
            false
        );
        $translateAdapterMock = $this->getMock(\Magento\Framework\Translate\Adapter::class, [], [], '', false);
        $this->objectManagerMock->expects($this->at(0))
            ->method('create')
            ->with(\Magento\Framework\Translate\Adapter::class)
            ->willReturn($translateAdapterMock);
        $this->objectManagerMock->expects($this->at(1))
            ->method('create')
            ->with(
                \Magento\Framework\Validator\Config::class,
                ['configFiles' => $this->data]
            )
            ->willReturn($this->validatorConfigMock);
        $this->readerMock = $this->getMock(
            \Magento\Framework\Module\Dir\Reader::class,
            ['getConfigurationFiles'],
            [],
            '',
            false
        );
        $this->cacheMock = $this->getMock(\Magento\Framework\Cache\FrontendInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->factory = $objectManager->getObject(
            \Magento\Framework\Validator\Factory::class,
            [
                'objectManager' => $this->objectManagerMock,
                'moduleReader' => $this->readerMock,
                'cache' => $this->cacheMock
            ]
        );

        $this->jsonMock = $this->getMock(\Magento\Framework\Json\JsonInterface::class);
        $objectManager->setBackwardCompatibleProperty($this->factory, 'json', $this->jsonMock);
    }

    /**
     * Restore default translator
     */
    protected function tearDown()
    {
        \Magento\Framework\Validator\AbstractValidator::setDefaultTranslator($this->defaultTranslator);
        unset($this->defaultTranslator);
    }

    public function testGetValidatorConfig()
    {
        $this->readerMock->method('getConfigurationFiles')
            ->with('validation.xml')
            ->willReturn($this->data);
        $actualConfig = $this->factory->getValidatorConfig();
        $this->assertInstanceOf(
            \Magento\Framework\Validator\Config::class,
            $actualConfig,
            'Object of incorrect type was created'
        );
        $this->assertInstanceOf(
            \Magento\Framework\Translate\Adapter::class,
            \Magento\Framework\Validator\AbstractValidator::getDefaultTranslator(),
            'Default validator translate adapter was not set correctly'
        );
    }

    public function testGetValidatorConfigCacheNotExist()
    {
        $this->cacheMock->expects($this->once())
            ->method('load')
            ->willReturn(false);
        $this->readerMock->expects($this->once())
            ->method('getConfigurationFiles')
            ->willReturn($this->data);
        $this->cacheMock->expects($this->once())
            ->method('save')
            ->with($this->jsonString);
        $this->jsonMock->expects($this->once())
            ->method('encode')
            ->with($this->data)
            ->willReturn($this->jsonString);
        $this->factory->getValidatorConfig();
        $this->factory->getValidatorConfig();
    }

    public function testGetValidatorConfigCacheExist()
    {
        $this->cacheMock->expects($this->once())
            ->method('load')
            ->willReturn($this->jsonString); // why json mock???
        $this->readerMock->expects($this->never())
            ->method('getConfigurationFiles');
        $this->cacheMock->expects($this->never())
            ->method('save');
        $this->jsonMock->expects($this->once())
            ->method('decode')
            ->with($this->jsonString)
            ->willReturn($this->data);
        $this->factory->getValidatorConfig();
        $this->factory->getValidatorConfig();
    }

    public function testCreateValidatorBuilder()
    {
        $this->readerMock->method('getConfigurationFiles')
            ->with('validation.xml')
            ->willReturn($this->data);
        $builderMock = $this->getMock(\Magento\Framework\Validator\Builder::class, [], [], '', false);
        $this->validatorConfigMock->expects($this->once())
            ->method('createValidatorBuilder')
            ->with('test', 'class', [])
            ->willReturn($builderMock);
        $this->assertInstanceOf(
            \Magento\Framework\Validator\Builder::class,
            $this->factory->createValidatorBuilder('test', 'class', [])
        );
    }

    public function testCreateValidator()
    {
        $this->readerMock->method('getConfigurationFiles')
            ->with('validation.xml')
            ->willReturn($this->data);
        $validatorMock = $this->getMock(\Magento\Framework\Validator::class, [], [], '', false);
        $this->validatorConfigMock->expects($this->once())
            ->method('createValidator')
            ->with('test', 'class', [])
            ->willReturn($validatorMock);
        $this->assertInstanceOf(
            \Magento\Framework\Validator::class,
            $this->factory->createValidator('test', 'class', [])
        );
    }
}
