<?php
/**
 * File JmsJsonTypeRegistrationTest.php
 * Created at: 2016-08-29 11-04
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\DoctrineJsonBundle\Tests;

use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Webit\DoctrineJmsJson\DBAL\JmsJsonType;

class JmsJsonTypeRegistrationTest extends TestCase
{
    /**
     * @var string
     */
    private $runId;

    protected function setUp(): void
    {
        $this->runId = md5(microtime());
    }

    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * @test
     */
    public function shouldNotRegisterJmsJsonTypeByDefault()
    {
        $this->kernel = new AppKernel(
            $this->runId,
            array(
                'framework' => array(
                    'secret' => md5(microtime())
                ),
                'webit_doctrine_json' => array(
                )
            )
        );

        $this->kernel->boot();

        $this->assertFalse(Type::hasType('jms_json'));
    }

    /**
     * @test
     */
    public function shouldNotRegisterJmsJsonTypeWithDefaultValues()
    {
        $this->kernel = new AppKernel(
            $this->runId,
            array(
                'framework' => array(
                    'secret' => md5(microtime())
                ),
                'webit_doctrine_json' => array(
                    'jms_json' => null
                )
            )
        );

        $this->kernel->boot();

        $this->assertTrue(Type::hasType('jms_json'));
    }

    /**
     * @test
     */
    public function shouldOverrideDefaultTypeName()
    {
        $type = $this->typeName();
        $this->kernel = new AppKernel(
            $this->runId,
            array(
                'framework' => array(
                    'secret' => md5(microtime())
                ),
                'webit_doctrine_json' => array(
                    'jms_json' => array(
                        'type_name' => $type
                    )
                )
            )
        );

        $this->kernel->boot();

        $this->assertTrue(Type::hasType($type));
    }

    public function tearDown(): void
    {
        $refClass = new \ReflectionClass(JmsJsonType::class);
        foreach (array('serializer', 'typeResolver') as $property) {
            $refProperty = $refClass->getProperty($property);
            $refProperty->setAccessible(true);
            $refProperty->setValue(null);
        }
    }

    private function typeName()
    {
        return 'jms_json_' . $this->runId;
    }
}
