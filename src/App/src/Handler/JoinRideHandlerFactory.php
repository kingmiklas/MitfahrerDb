<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JoinRideHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new JoinRideHandler($container->get(TemplateRendererInterface::class));
    }
}
