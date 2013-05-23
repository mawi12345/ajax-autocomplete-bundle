<?php

namespace Mawi\AjaxAutocompleteBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('mawi');

        $rootNode

            ->children()
                ->arrayNode('autocomplete')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('label')
                                ->defaultValue('name')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('labelClass')
                            	->defaultNull()
                            ->end()
                            ->scalarNode('role')
                                ->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('service')

                            	->defaultValue('mawi.autocomplete.entities')

                            	->cannotBeEmpty()

                            ->end()
                            ->scalarNode('search')
                                ->defaultValue('begins_with')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('query')

	                            ->defaultValue('SELECT e FROM :class WHERE e.name LIKE :term ORDER by e.name')

	                            ->cannotBeEmpty()

                            ->end()
                            ->scalarNode('max')

	                            ->defaultValue(12)

	                            ->cannotBeEmpty()

                            ->end()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
