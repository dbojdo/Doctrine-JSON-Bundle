<?php
/**
 * File WebitDoctrineJsonExtension.php
 * Created at: 2016-08-28 20-17
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\DoctrineJsonBundle\DependencyInjection;

use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class WebitDoctrineJsonExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $config = $this->processConfiguration(new Configuration(), $configs);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        if ($config['jms_json']['enabled']) {
            $this->loadJmsJson($config['jms_json'], $xmlLoader, $container);
        }
    }

    private function loadJmsJson($jmsJsonConfig, XmlFileLoader $xmlLoader, ContainerBuilder $container)
    {
        $xmlLoader->load('jms_json.xml');

        $container->setParameter('webit_doctrine_json.jms_json.type_name', $jmsJsonConfig['type_name']);

        $serializer = new Definition('JMS\\Serializer\\SerializerInterface');
        $serializer->setFactory(array(new Reference('service_container'), 'get'));
        $serializer->addArgument($jmsJsonConfig['serializer']);
        $serializer->setLazy(true);
        $serializer->setPublic(true);
        $container->setDefinition('webit_doctrine_json.jms_json.serializer', $serializer);

        $arrayTransformer = new Definition('JMS\\Serializer\\ArrayTransformerInterface');
        $arrayTransformer->setFactory(array(new Reference('service_container'), 'get'));
        $arrayTransformer->addArgument($jmsJsonConfig['serializer']);
        $arrayTransformer->setLazy(true);
        $arrayTransformer->setPublic(true);
        $container->setDefinition('webit_doctrine_json.jms_json.array_transformer', $serializer);

        $typeResolver = new Definition('Webit\\DoctrineJmsJson\\Serializer\\DefaultTypeResolver');
        $typeResolver->setFactory(array(new Reference('service_container'), 'get'));
        $typeResolver->addArgument($jmsJsonConfig['type_resolver']);
        $typeResolver->setLazy(true);
        $typeResolver->setPublic(true);
        $container->setDefinition('webit_doctrine_json.jms_json.type_resolver', $typeResolver);
    }
}
