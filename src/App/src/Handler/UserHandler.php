<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface  $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_start();
        if ($_SESSION['email'] === ''){
            return new HtmlResponse($this->template->render('app::login-page'));
        }

        return new HtmlResponse($this->template->render('app::user-page'));
    }
}
