<?php
declare(strict_types=1);

namespace EsoftSk\DoctrineExtensionsBundle;

use EsoftSk\DoctrineExtensionsBundle\DependencyInjection\DoctrineExtensionsBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EsoftSkDoctrineExtensionsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new DoctrineExtensionsBundleExtension();
    }
}
