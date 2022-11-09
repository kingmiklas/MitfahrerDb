<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RideSubmitHandler implements RequestHandlerInterface
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
        $email = (string) $_SESSION['email'];
        if ($email === '') {
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();
        /** @var array $credentials */
        $credentials = $request->getParsedBody();

        $start = (string) $credentials['start'];
        $ziel = (string) $credentials['ziel'];
        $plaetze = (int) $credentials['plaetze'];
        $preis = (int) $credentials['preis'];
        $date = (string) $credentials['date'];
        $time = (string) $credentials['time'];
        $dateTime = $date . ' ' . $time . ':00';

        $stmt = $pdo->prepare("SELECT kID from tUser where cEmail = :email");
        $stmt->execute(['email' => $email]);
        $existingEmailArray = $stmt->fetch();
        $ersteller = 0;
        if (is_array($existingEmailArray)) {
            $ersteller = (int) $existingEmailArray[0];
        }
        $stmt = $pdo->prepare("INSERT INTO tPostedRides (dDatumUhrzeit, cStartOrt, cZielOrt,nSitzplaetze,kErsteller,nPreis)
        VALUES (:date,:start,:ziel,:plaetze,:ersteller,:preis)");
        $stmt->execute(['date' => $dateTime, 'start' => $start, 'ziel' => $ziel, 'plaetze' => $plaetze, 'ersteller' => $ersteller, 'preis' => $preis]);

        return new HtmlResponse($this->template->render('app::create-ride-page'));
    }
}
