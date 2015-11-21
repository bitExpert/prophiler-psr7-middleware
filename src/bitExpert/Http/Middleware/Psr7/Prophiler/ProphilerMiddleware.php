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

use bitExpert\Http\Middleware\Psr7\Prophiler\DataCollector\Request;
use bitExpert\Slf4PsrLog\LoggerFactory;
use Fabfuel\Prophiler\Toolbar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewareInterface;

class ProphilerMiddleware implements MiddlewareInterface
{
    /**
     * @var \Psr\Log\LoggerInterface the logger instance.
     */
    protected $logger;
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
        $this->logger = LoggerFactory::getLogger(__CLASS__);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $this->toolbar->addDataCollector(new Request($request));

        if (null !== $out) {
            $response = $out($request, $response);
        }

        if (!$response->getBody()->isWritable()) {
            $this->logger->debug('Response is not writable. Skipping Prophiler toolbar generation.');
            return $response;
        }

        $headers = $response->getHeader('Content-Type');
        if (count($headers) === 0) {
            $this->logger->debug('Content-Type of response not set. Skipping Prophiler toolbar generation.');
            return $response;
        }

        if ($headers[0] === 'text/html') {
            $response->getBody()->write($this->toolbar->render());
        } else {
            $this->logger->debug('Content-Type of response is not text/html. Skipping Prophiler toolbar generation.');
        }

        return $response;
    }
}
