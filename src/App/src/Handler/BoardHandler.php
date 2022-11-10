<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BoardHandler implements RequestHandlerInterface
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
        $fahrerEmail = $_SESSION['email'];
        if ($fahrerEmail === '') {
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();

        $stmt = $pdo->query("SELECT * from tPostedRides");
        $rides = $stmt->fetchAll();

        foreach ($rides as $key => $ride) {
            $fahrtStorniert = (bool)$ride['bIsStorniert'];
            if($fahrtStorniert){
                unset($rides[$key]);
            }

            $stmt = $pdo->prepare("SELECT Count(kUser) from tUserRides where kRide =:ersteller");
            $stmt->execute(['ersteller' => $ride['kID']]);
            $mitfahrerCount = (int)$stmt->fetch()[0];

            $stmt = $pdo->prepare("SELECT cEmail from tUser where kID =:ersteller");
            $stmt->execute(['ersteller' => (int)$ride['kErsteller']]);
            $email = $stmt->fetch()[0];

            $ride['email'] = $email;
            $freieSitzplaetze = (int)$ride['nSitzplaetze'] - $mitfahrerCount;
            if ($freieSitzplaetze === 0){
                unset($rides[$key]);
                continue;
            }

            $ride['freieSitzplaetze'] = $freieSitzplaetze;

            $ride['sessionEmail'] = $fahrerEmail;

            $rides[$key] = $ride;
        }

        return new HtmlResponse($this->template->render('app::board-page', ['rides' => $rides]));
    }
}
