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

use Psr\Http\Message\ServerRequestInterface;

class RequestUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var Request
     */
    protected $dataCollector;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMock(ServerRequestInterface::class);
        $this->request->expects($this->any())
            ->method('getHeaders')
            ->will($this->returnValue([]));
        $this->request->expects($this->any())
            ->method('getServerParams')
            ->will($this->returnValue([]));
        $this->request->expects($this->any())
            ->method('getQueryParams')
            ->will($this->returnValue([]));
        $this->request->expects($this->any())
            ->method('getCookieParams')
            ->will($this->returnValue([]));
        $this->request->expects($this->any())
            ->method('getAttributes')
            ->will($this->returnValue([]));

        $this->dataCollector = new Request($this->request);
    }

    /**
     * @test
     */
    public function datacollectorReturnsArray()
    {
        $data = $this->dataCollector->getData();

        $this->assertTrue(is_array($data));
        $this->assertArrayHasKey('SERVER', $data);
        $this->assertArrayHasKey('QUERY', $data);
        $this->assertArrayHasKey('COOKIES', $data);
        $this->assertArrayHasKey('HEADERS', $data);
        $this->assertArrayHasKey('ATTRIBUTES', $data);
    }
}
