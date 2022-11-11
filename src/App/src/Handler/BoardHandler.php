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
        $sessionEmail = $_SESSION['email'];
        if ($sessionEmail === '') {
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();

        $stmt = $pdo->prepare("SELECT bIsSchueler from tUser where cEmail = :email");
        $stmt->execute(['email' => $sessionEmail]);
        $isSchueler = (int) $stmt->fetch()[0];

        $method = $request->getMethod();
        if ($method === 'POST') {
            $sql = "SELECT p.* from tPostedRides as p join tUser as u On(p.kErsteller = u.kID) where u.kid = p.kErsteller and u.bIsSchueler = :isSchueler and p.bIsStorniert = 0";
            /** @var array $credentials */
            $credentials = $request->getParsedBody();
            if ($credentials !== []) {
                /** @var string $key */
                /** @var int $filter */
                foreach ($credentials as $key => $filter) {
                    if ($key === 'bGeschlecht'){
                        $sql .= " and u." . $key . "=" . "'$filter'";
                        continue;
                    }
                    $sql .= ' and u.' . $key . '=' . $filter;
                }
            }
        } else {
            $sql = "SELECT p.* from tPostedRides as p join tUser as u On(p.kErsteller = u.kID) where u.bIsSchueler = :isSchueler and p.bIsStorniert = 0";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['isSchueler' => $isSchueler]);
        $rides = $stmt->fetchAll();

        foreach ($rides as $key => $ride) {
            $fahrtStorniert = (bool) $ride['bIsStorniert'];
            if ($fahrtStorniert) {
                unset($rides[$key]);
            }

            $stmt = $pdo->prepare("SELECT Count(kUser) from tUserRides where kRide =:ersteller");
            $stmt->execute(['ersteller' => $ride['kID']]);
            $mitfahrerCount = (int) $stmt->fetch()[0];

            $stmt = $pdo->prepare("SELECT cEmail from tUser where kID =:ersteller");
            $stmt->execute(['ersteller' => (int) $ride['kErsteller']]);
            $email = $stmt->fetch()[0];

            $ride['email'] = $email;
            $freieSitzplaetze = (int) $ride['nSitzplaetze'] - $mitfahrerCount;
            if ($freieSitzplaetze === 0) {
                unset($rides[$key]);
                continue;
            }

            $ride['freieSitzplaetze'] = $freieSitzplaetze;

            $ride['sessionEmail'] = $sessionEmail;

            $rides[$key] = $ride;
        }

        return new HtmlResponse($this->template->render('app::board-page', ['rides' => $rides]));
    }
}
