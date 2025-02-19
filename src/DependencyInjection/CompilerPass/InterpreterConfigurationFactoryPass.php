<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\DataImporterBundle\DependencyInjection\CompilerPass;

use Pimcore\Bundle\DataImporterBundle\DataSource\Interpreter\InterpreterFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InterpreterConfigurationFactoryPass implements CompilerPassInterface
{
    const interpreter_tag = 'pimcore.datahub.data_importer.interpreter';

    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(self::interpreter_tag);
        $interpreters = [];
        if (sizeof($taggedServices)) {
            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $interpreters[$attributes['type']] = new Reference($id);
                }
            }
        }

        $serviceLocator = $container->getDefinition(InterpreterFactory::class);
        $serviceLocator->setArgument('$interpreterBluePrints', $interpreters);
    }
}
