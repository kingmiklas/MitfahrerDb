<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * laminas-router route configuration
 *
 * @see https://docs.laminas.dev/laminas-router/
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', App\Handler\LoginHandler::class, 'login.get');
    $app->post('/', App\Handler\LoginSubmitHandler::class, 'login.post');
    $app->get('/logout', App\Handler\LogoutSubmitHandler::class, 'logout');
    $app->get('/register', App\Handler\RegisterHandler::class, 'register.get');
    $app->get('/board', App\Handler\BoardHandler::class, 'board');
    $app->post('/board', App\Handler\BoardHandler::class, 'board.post');
    $app->get('/user', App\Handler\UserHandler::class, 'user.get');
    $app->post('/user', App\Handler\UserSubmitHandler::class, 'user.post');
    $app->get('/ride', App\Handler\RideHandler::class, 'ride.get');
    $app->post('/ride', App\Handler\RideSubmitHandler::class, 'ride.post');
    $app->post('/delete', App\Handler\DeleteRideHandler::class, 'delete.post');
    $app->post('/join', App\Handler\JoinRideHandler::class, 'join.post');
    $app->post('/register', App\Handler\RegisterSubmitHandler::class, 'register.post');
    $app->get('/password', App\Handler\ForgotPasswordHandler::class, 'password');
    $app->get('/agb', App\Handler\AGBHandler::class, 'agb');
    $app->post('/password', App\Handler\ForgotPasswordHandler::class, 'password.post');
};
