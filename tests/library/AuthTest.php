<?php

use CrSrc\Auth;
use CrSrc\Auth\AuthInterface;

use Zend\Authentication\Result;

class AuthTest extends PHPUnit_Framework_TestCase
{
    protected $auth;

    protected $authServiceMock;

    public function setUp()
    {
        // mock the zend service
        $this->authServiceMock = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->auth = new Auth( $this->authServiceMock );
    }

    public function testInstantiation()
    {
        $this->assertTrue($this->auth instanceof AuthInterface);
    }

    public function testIsAuthenticatedWhenGetIdentityReturnsIdetity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn('martyn@example.com');

        $this->assertTrue($this->auth->isAuthenticated());
    }

    public function testIsAuthenticatedWhenGetIdentityReturnsReturnsNull()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn(null);

        $this->assertFalse($this->auth->isAuthenticated());
    }

    public function testGetIdentityWhenGetIdentityReturnsIdentity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn('martyn@example.com');

        $this->assertEquals('martyn@example.com', $this->auth->getIdentity());
    }

    public function testGetIdentityWhenGetIdentityReturnsNull()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn(null);

        $this->assertEquals(null, $this->auth->getIdentity());
    }

    public function testAuthenticateWhenResultIsSuccess()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('authenticate')
            ->willReturn( new Result(
                Result::SUCCESS,
                'martyn@example.com',
                array()
            ) );

        $this->assertTrue($this->auth->authenticate('martyn', 'mypass'));
    }

    public function testAuthenticateWhenResultIsFail()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('authenticate')
            ->willReturn( new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                'martyn@example.com',
                array()
            ) );

        $this->assertFalse($this->auth->authenticate('martyn', 'mypass'));
    }

    public function testClearIdentityCallsServicesClearIdentity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('clearIdentity');

        $this->auth->clearIdentity();
    }
}
