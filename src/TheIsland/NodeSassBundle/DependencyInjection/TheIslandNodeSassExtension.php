<?php

namespace TheIsland\NodeSassBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class TheIslandNodeSassExtension extends Extension {
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = $this->getConfiguration($configs, $container);
        $config = $processor->processConfiguration($configuration, $configs);

        $filterDef = $container->getDefinition('the_island.assetic.filter.node_scss');

        foreach($config['load_paths'] as $loadPath) {
            $filterDef->addMethodCall('addLoadPath', [$loadPath]);
        }

        unset($config['load_paths']);

        if(isset($config['apply_to']) && $config['apply_to']) {
            $worker = new DefinitionDecorator('assetic.worker.ensure_filter');
            $worker->replaceArgument(0, '/' . $config['apply_to'] . '/');
            $worker->replaceArgument(1, new Reference('the_island.assetic.filter.node_scss'));
            $worker->addTag('assetic.factory_worker');

            $container->setDefinition('the_island.assetic.filter.node_scss.worker', $worker);

            unset($config['apply_to']);
        }

        foreach ($config as $key => $value) {
            $container->setParameter('the_island.assetic.filter.node_scss.'.$key, $value);
        }
    }

    public function getAlias() {
        return 'the_island_node_sass';
    }
}
