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

        $stmt = $pdo->prepare("SELECT cEmail from tUser where cEmail = :email");
        $stmt->execute(['email' => $email]);
        /** @var array $existingEmailArray */
        $existingEmailArray = $stmt->fetch();
        $existingEmail = (string)$existingEmailArray[0];

        if ($existingEmail === $email) {
            return new HtmlResponse($this->template->render('app::register-page', ['error' => 'Account Existiert bereits']));
        }

        $stmt = $pdo->prepare("INSERT INTO tUser (cVorname, cNachname, cEmail,bIsSchueler)
        VALUES (:vorname,:nachname,:email,:isSchueler)");
        $stmt->execute(['vorname' => $vorname, 'nachname' => $nachname, 'email' => $email, 'isSchueler' => $isSchueler]);
        $stmt = $pdo->prepare("SELECT kID FROM tUser where cEmail = :email");
        $stmt->execute(['email' => $email]);

        /** @var array $userIdArray */
        $userIdArray = $stmt->fetch();
        $userId = (int)$userIdArray[0];

        $stmt = $pdo->prepare("INSERT INTO tPasswort (cPassword, kUser)
        VALUES (:password,:userId)");
        $stmt->execute(['password' => $password, 'userId' => $userId]);

        return new HtmlResponse($this->template->render('app::login-page'));
    }
}
