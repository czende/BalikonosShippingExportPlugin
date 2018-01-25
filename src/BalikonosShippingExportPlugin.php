<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
final class BalikonosShippingExportPlugin extends Bundle
{
    use SyliusPluginTrait;
}