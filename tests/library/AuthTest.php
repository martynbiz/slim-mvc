<?php

use App\Auth\Auth;
use App\Auth\AuthInterface;

use Zend\Authentication\Result;

use App\Model\User;

class AuthTest extends PHPUnit_Framework_TestCase
{
    protected $auth;

    protected $authServiceMock;

    public function setUp()
    {
        // mock the zend service
        $this->authServiceMock = $this->getMockBuilder('Zend\\Authentication\\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();
        $this->auth = new Auth( $this->authServiceMock );

        // mock user model
        $userMock = $this->getMockBuilder('App\\Model\\User')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container['model.user'] = $userMock;
    }

    public function testInstantiation()
    {
        $this->assertTrue($this->auth instanceof AuthInterface);
    }

    public function testIsAuthenticatedReturnsTrueWhenServiceReturnsIdetity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn('martyn@example.com');

        $this->assertTrue($this->auth->isAuthenticated());
    }

    public function testIsAuthenticatedReturnsFalseWhenServiceReturnsReturnsNull()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn(null);

        $this->assertFalse($this->auth->isAuthenticated());
    }

    public function testGetIdentityReturnsIdentityWhenServiceReturnsIdentity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn('martyn@example.com');

        $this->assertEquals('martyn@example.com', $this->auth->getIdentity());
    }

    public function testGetIdentityReturnsNullWhenServiceReturnsNull()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('getIdentity')
            ->willReturn(null);

        $this->assertEquals(null, $this->auth->getIdentity());
    }

    public function testAuthenticateReturnsTrueWhenResultIsSuccess()
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

    public function testAuthenticateReturnsFalseWhenResultIsFail()
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
