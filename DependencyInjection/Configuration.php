<?php

namespace AppVentus\Awesome\SpoolMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('spool_mailer_bundle');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		$rootNode


        ->children()

            ->arrayNode('contact_addresses')
            ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('admin')
                        ->children()
                            ->scalarNode('address')->end()
                            ->scalarNode('name')->end()->end()->end()
                    ->arrayNode('noreply')
                        ->children()
                            ->scalarNode('address')->end()
                            ->scalarNode('name')->end()->end()->end()
                ->end()
            ->end();
				
        return $treeBuilder;
    }
}
