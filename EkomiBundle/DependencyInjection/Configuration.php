<?php

namespace Gamma\Ekomi\EkomiBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('gamma_ekomi');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		$rootNode
            ->children()
				->scalarNode('scheme')
					->defaultValue('http')
					->cannotBeEmpty()
				->end()
				->scalarNode('host')
					->defaultValue('api.ekomi.de')
					->cannotBeEmpty()
				->end()
				->scalarNode('path')
					->defaultValue('get_productfeedback.php')
					->cannotBeEmpty()
				->end()
				->scalarNode('max_rank')
					->defaultValue('5')
					->cannotBeEmpty()
				->end()
            	->scalarNode('cache')
					->defaultValue('Filesystem')
					->cannotBeEmpty()
				->end()
                ->scalarNode('cache_timeout')
					->defaultValue('43200')
					->cannotBeEmpty()
				->end()
				->scalarNode('interface_id')
					->isRequired()
				->end()
				->scalarNode('interface_pw')
					->isRequired()
				->end()
				->scalarNode('type')
					->isRequired()
				->end()             
			->end()
        ;
        
        return $treeBuilder;
    }
}
