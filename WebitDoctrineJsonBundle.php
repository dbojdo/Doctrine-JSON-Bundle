<?php
/**
 * File WebitDoctrineJsonBundle.php
 * Created at: 2016-08-28 20-11
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\DoctrineJsonBundle;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webit\DoctrineJmsJson\DBAL\Exception\JmsJsonTypeInitializationException;
use Webit\DoctrineJmsJson\DBAL\JmsJsonType;

class WebitDoctrineJsonBundle extends Bundle
{
    public function boot()
    {
        $this->registerJmsJsonType($this->container);
    }

    private function registerJmsJsonType(Container $container)
    {
        if (!$container->hasParameter('webit_doctrine_json.jms_json.type_name')) {
            return;
        }

        try {
            Type::addType(
                $container->getParameter('webit_doctrine_json.jms_json.type_name'),
                'Webit\DoctrineJmsJson\DBAL\JmsJsonType'
            );
            JmsJsonType::initialize(
                $container->get('webit_doctrine_json.jms_json.serializer'),
                $container->get('webit_doctrine_json.jms_json.array_transformer'),
                $container->get('webit_doctrine_json.jms_json.type_resolver')
            );
        } catch (DBALException $e) {
        } catch (JmsJsonTypeInitializationException $e) {
        }
    }
}
