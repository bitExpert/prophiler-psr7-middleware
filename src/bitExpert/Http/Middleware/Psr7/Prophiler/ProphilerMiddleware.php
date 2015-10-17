<?php

/*
 * This file is part of the Prophiler PSR7 middleware package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Http\Middleware\Psr7\Prophiler;

use Fabfuel\Prophiler\Toolbar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewareInterface;

class ProphilerMiddleware implements MiddlewareInterface
{
    /**
     * @var Toolbar
     */
    protected $toolbar;

    /**
     * Creates a new {@link \bitExpert\Http\Middleware\Psr7\Prophiler\ProphilerMiddleware}.
     *
     * @param Toolbar $toolbar
     */
    public function __construct(Toolbar $toolbar)
    {
        $this->toolbar = $toolbar;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        if (null !== $out) {
            $response = $out($request, $response);
        }

        if(!$response->getBody()->isWritable()) {
            return $response;
        }

        $headers = $response->getHeader('Content-Type');
        if ($headers[0] === 'text/html') {
            $content = $response->getBody()->getContents();
            $content .= $this->toolbar->render();
            $response->getBody()->write($content);
        }

        return $response;
    }
}
