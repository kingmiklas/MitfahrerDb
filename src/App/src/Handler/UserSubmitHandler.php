<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserSubmitHandler implements RequestHandlerInterface
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
        $raucher = $this->checkForEmptyString(array_key_exists('raucher', $credentials) ? (string) $credentials['raucher'] : '');
        if ($raucher !== null) {
            $raucher = (int) $this->convertStringToBool($raucher);
        }
        $tierhaare = $this->checkForEmptyString(array_key_exists('tierhaare', $credentials) ? (string) $credentials['tierhaare'] : '');
        if ($tierhaare !== null) {
            $tierhaare = (int) $this->convertStringToBool($tierhaare);
        }
        $maskenpflicht = $this->checkForEmptyString(array_key_exists('maskenpflicht', $credentials) ? (string) $credentials['maskenpflicht'] : '');
        if ($maskenpflicht !== null) {
            $maskenpflicht = (int) $this->convertStringToBool($maskenpflicht);
        }
        $geschlecht = $this->checkForEmptyString(array_key_exists('geschlecht', $credentials) ? (string) $credentials['geschlecht'] : '');
        $info = $this->checkForEmptyString((string) $credentials['info']);
        $treffpunkt = $this->checkForEmptyString((string) $credentials['treffpunkt']);

        $stmt = $pdo->prepare("UPDATE tUser SET bRaucher=?,bTierhaare=?,bMaskenpflicht=?,
                 bGeschlecht=?,cFreiInfo=?,cHeimatsTreffpunkt=? where cEmail =?");
        $stmt->execute([$raucher, $tierhaare, $maskenpflicht, $geschlecht, $info, $treffpunkt, $email]);

        return new HtmlResponse($this->template->render('app::user-page'));
    }

    private function convertStringToBool(string $string): bool
    {
        return $string !== 'nein';
    }

    private function checkForEmptyString(string $string): ?string
    {
        if ($string === '') {
            return null;
        }

        return $string;
    }
}
