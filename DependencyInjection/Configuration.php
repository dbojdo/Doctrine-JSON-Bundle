<?php
/**
 * File Configuration.php
 * Created at: 2016-08-28 20-13
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\DoctrineJsonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

final class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        list($builder, $root) = $this->createTreeBuilder('webit_doctrine_json');

        $root
            ->children()
                ->arrayNode('jms_json')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type_name')
                            ->defaultValue('jms_json')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('serializer')
                            ->defaultValue('jms_serializer')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('type_resolver')
                            ->defaultValue('webit_doctrine_json.jms_json.default_type_resolver')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }

    private function createTreeBuilder($rootNodeName)
    {
        if (version_compare(Kernel::VERSION, '5.0.0') == -1) {
            return [$builder = new TreeBuilder(), $builder->root($rootNodeName)];
        }

        return [$builder = new TreeBuilder($rootNodeName), $builder->getRootNode()];
    }
}
