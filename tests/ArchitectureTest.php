<?php

namespace App\Tests;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\BuildStep;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;

class ArchitectureTest
{
    public function testDomainDoesNotDependOnOtherLayers(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\TaskManager\Domain'))
            ->shouldNot()
            ->dependOn()
            ->classes(
                Selector::inNamespace('App\TaskManager\Application'),
                Selector::inNamespace('App\TaskManager\Infrastructure'),
                Selector::inNamespace('App\Base')

            )
            ->because('Domain layer should not depend on Application layer')
            ;
    }

    public function testApplicationDoesNotDependOnInfrastructureAndBase(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\TaskManager\Application'))
            ->shouldNot()
            ->dependOn()
            ->classes(
                Selector::inNamespace('App\TaskManager\Infrastructure'),
                Selector::inNamespace('App\Base')
            )
            ->because('Application layer should not depend on Infrastructure layer')
            ;
    }

    public function testSharedDoesNotDependOnOtherLayers(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\TaskManager\Shared'))
            ->shouldNot()
            ->dependOn()
            ->classes(
                Selector::inNamespace('App\TaskManager\Domain'),
                Selector::inNamespace('App\TaskManager\Application'),
                Selector::inNamespace('App\TaskManager\Infrastructure'),
                Selector::inNamespace('App\Base')
            )
            ->because('Shared layer should not depend on other layers')
            ;
    }
}
