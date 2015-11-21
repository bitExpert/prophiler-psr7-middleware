<?php

/*
 * This file is part of the Prophiler PSR7 middleware package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Http\Middleware\Psr7\Prophiler\DataCollector;

use Fabfuel\Prophiler\DataCollectorInterface;
use Psr\Http\Message\ServerRequestInterface;

class Request implements DataCollectorInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * Creates a new {@link \bitExpert\Http\Middleware\Psr7\Prophiler\DataCollector\Request}.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return 'Request';
    }


    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return '<i class="fa fa-arrow-circle-o-down"></i>';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $headers = [];
        foreach ($this->request->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }

        $data = [
            'SERVER' => $this->request->getServerParams(),
            'QUERY' => $this->request->getQueryParams(),
            'COOKIES' => $this->request->getCookieParams(),
            'HEADERS' => $headers,
            'ATTRIBUTES' => $this->request->getAttributes()
        ];

        return $data;
    }
}
