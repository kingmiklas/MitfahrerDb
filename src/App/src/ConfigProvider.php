<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PDOHandler::class => Handler\PDOHandler::class,
            ],
            'factories'  => [
                Handler\LoginHandler::class => Handler\LoginHandlerFactory::class,
                Handler\RegisterHandler::class => Handler\RegisterHandlerFactory::class,
                Handler\RegisterSubmitHandler::class => Handler\RegisterSubmitHandlerFactory::class,
                Handler\UserSubmitHandler::class => Handler\UserSubmitHandlerFactory::class,
                Handler\ForgotPasswordHandler::class => Handler\ForgotPasswordHandlerFactory::class,
                Handler\BoardHandler::class => Handler\BoardHandlerFactory::class,
                Handler\LoginSubmitHandler::class => Handler\LoginSubmitHandlerFactory::class,
                Handler\LogoutSubmitHandler::class => Handler\LogoutSubmitHandlerFactory::class,
                Handler\UserHandler::class => Handler\UserHandlerFactory::class,
                Handler\RideHandler::class => Handler\RideHandlerFactory::class,
                Handler\JoinRideHandler::class => Handler\JoinRideHandlerFactory::class,
                Handler\DeleteRideHandler::class => Handler\DeleteRideHandlerFactory::class,
                Handler\RideSubmitHandler::class => Handler\RideSubmitHandlerFactory::class,
                Handler\AGBHandler::class => Handler\AGBHandlerFactory::class,
                Handler\BookHandler::class => Handler\BookHandlerFactory::class,
                Handler\LoggedBookHandler::class => Handler\LoggedBookHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
