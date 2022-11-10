<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteRideHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_start();
        $fahrerEmail = $_SESSION['email'];
        if ($fahrerEmail === '') {
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();
        /** @var array $credentials */
        $credentials = $request->getParsedBody();
        $rideId = (int)$credentials['rideId'];

        $stmt = $pdo->prepare("DELETE FROM tPostedRides where kID = :rideId");
        $stmt->execute(['rideId' => $rideId]);

        return new RedirectResponse('/board');
    }
}
