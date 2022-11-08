<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RegisterSubmitHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();

        /** @var array $credentials */
        $credentials = $request->getParsedBody();
        $password = (string) $credentials['password'];

        if ($password !== $credentials['passwordRepeat']) {
            return new HtmlResponse($this->template->render('app::register-page', ['error' => 'Passwort und Passwort wiederholen stimmt nicht überein']));
        }

        $email = (string) $credentials['email'];
        $vorname = (string) $credentials['vorname'];
        $nachname = (string) $credentials['nachname'];
        $isSchueler = true;

        $existingEmail = $pdo->query("SELECT cEmail from tUser where cEmail = $email")->fetch();

        if ($existingEmail === $email){
            return new HtmlResponse($this->template->render('app::register-page', ['error' => 'Account Existiert bereits']));
        }

        $pdo->query("INSERT INTO tUser (cVorname, cNachname, cEmail,bIsSchueler)
        VALUES ($vorname,$nachname,$email,$isSchueler)")->execute();

        $userId = (int)$pdo->query("SELECT kID FROM tUser where cEmail = $email")->fetch();

        $pdo->query("INSERT INTO tPasswort (cPassword, kUser)
        VALUES ($password,$userId)")->execute();

        return new HtmlResponse($this->template->render('app::login-page'));
    }
}
