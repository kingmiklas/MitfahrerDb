<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JoinRideHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_start();
        $sessionEmail = $_SESSION['email'];
        if ($sessionEmail === '') {
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();
        /** @var array $credentials */
        $credentials = $request->getParsedBody();
        $rideId = (int) $credentials['rideId'];

        $stmt = $pdo->prepare("SELECT kID from tUser where cEmail = :email");
        $stmt->execute(['email' => $sessionEmail]);
        $userId = (int) $stmt->fetch()[0];

        $stmt = $pdo->prepare("INSERT INTO tUserRides (kRide, kUser)
        VALUES (:rideId,:userId)");
        $stmt->execute(['rideId' => $rideId, 'userId' => $userId]);

        return new RedirectResponse('/board');
    }
}
