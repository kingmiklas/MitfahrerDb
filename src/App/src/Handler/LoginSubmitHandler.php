<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginSubmitHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface  $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pdoHandler = new PDOHandler();
        $pdo = $pdoHandler->create();
        /** @var array $credentials */
        $credentials = $request->getParsedBody();
        $email = (string) $credentials['email'];
        $password = (string) $credentials['password'];
        $stmt = $pdo->prepare('SELECT tUser.bIsSchueler, tPasswort.cPassword from tUser INNER JOIN tPasswort ON tUser.kID = tPasswort.kUser where tUser.cEmail = :email');
        $stmt->execute(['email' => $email]);
        $existingUserArray = $stmt->fetch();
        if (in_array($password,$existingUserArray,false)){
            session_start();
            $_SESSION['isSchueler'] = (bool)$existingUserArray['bIsSchueler'];
            $_SESSION['email'] = $email;

            return new HtmlResponse($this->template->render('app::board-page'));
        }

        return new HtmlResponse($this->template->render('app::login-page',['error' => 'User existiert nicht']));
    }
}
