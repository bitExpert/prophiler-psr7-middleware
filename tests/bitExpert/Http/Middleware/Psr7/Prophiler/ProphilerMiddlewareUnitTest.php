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
use Psr\Http\Message\StreamInterface;

class ProphilerMiddlewareUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Toolbar
     */
    private $toolbar;
    /**
     * @var ProphilerMiddleware
     */
    private $middleware;
    /**
     * @var StreamInterface
     */
    protected $body;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->toolbar = $this->getMock(Toolbar::class, [], [], '', false);
        $this->middleware = new ProphilerMiddleware($this->toolbar);
        $this->body = $this->getMock(StreamInterface::class);
        $this->request = $this->getMock(ServerRequestInterface::class);
        $this->response = $this->getMock(ResponseInterface::class);
        $this->response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($this->body));
    }

    /**
     * @test
     */
    public function toolbarIsNotAppendedWhenResponseIsNotWritable()
    {
        $this->body->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));
        $this->body->expects($this->never())
            ->method('write');

        $this->middleware->__invoke($this->request, $this->response);
    }

    /**
     * @test
     */
    public function toolbarIsNotAppendedWhenContentTypeIsMissing()
    {
        $this->body->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));
        $this->body->expects($this->never())
            ->method('write');
        $this->response->expects($this->once())
            ->method('getHeader')
            ->will($this->returnValue([]));

        $this->middleware->__invoke($this->request, $this->response);
    }

    /**
     * @test
     */
    public function toolbarIsNotAppendedWhenContentTypeIsNotHtml()
    {
        $this->body->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));
        $this->body->expects($this->never())
            ->method('write');
        $this->response->expects($this->once())
            ->method('getHeader')
            ->will($this->returnValue(['application/json']));

        $this->middleware->__invoke($this->request, $this->response);
    }

    /**
     * @test
     */
    public function toolbarIsAppendedWhenContentTypeIsHtml()
    {
        $this->body->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));
        $this->body->expects($this->once())
            ->method('write');
        $this->response->expects($this->once())
            ->method('getHeader')
            ->will($this->returnValue(['text/html']));

        $this->middleware->__invoke($this->request, $this->response);
    }
}
