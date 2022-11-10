<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ForgotPasswordHandler implements RequestHandlerInterface
{
    private ?TemplateRendererInterface $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        if ($method === 'POST') {
            /** @var array $credentials */
            $credentials = $request->getParsedBody();
            $email = (string)$credentials['email'];
            shell_exec('python3 '.__DIR__ . '/../../../../email_passwort_vergessen.py' . " $email");
        }

        return new HtmlResponse($this->template->render('app::password-page'));
    }
}
