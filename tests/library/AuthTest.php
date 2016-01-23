<?php
namespace Tests\Library;

use Wordup\Auth\Auth;
use Wordup\Auth\AuthInterface;

use Zend\Authentication\Result;

use Wordup\Model\User;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    protected $auth;

    protected $authServiceMock;

    protected $userModelMock;

    public function setUp()
    {
        // mock the zend service
        $this->authServiceMock = $this->getMockBuilder('Zend\\Authentication\\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();

        // mock user model
        $this->userModelMock = $this->getMockBuilder('Wordup\\Model\\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->auth = new Auth( $this->authServiceMock, $this->userModelMock );
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


    // clearIdentity

    public function testClearIdentityCallsServicesClearIdentity()
    {
        $this->authServiceMock
            ->expects( $this->once() )
            ->method('clearIdentity');

        $this->auth->clearIdentity();
    }


    // // getCurrentUser -- TODO learn how to mock static methods (findOne)
    //
    // public function testGetCurrentUserReturnsUserWhenServiceReturnsIdentity()
    // {
    //     $user = new User();
    //
    //     $this->authServiceMock
    //         ->method('getIdentity')
    //         ->willReturn('martyn@example.com');
    //
    //     $this->userModelMock
    //         ->expects( $this->once() ) // one time hit the db
    //         ->method('findOne')
    //         ->with( array(
    //             'email' => 'martyn@example.com',
    //         ) )
    //         ->willReturn($user);
    //
    //     $result = $this->auth->getCurrentUser();
    //
    //     $this->assertEquals($user, $result);
    //
    //     // run the getCurrentUser again now shouldn't call findOne again (or getIdentity)
    //     $result = $this->auth->getCurrentUser();
    // }
    //
    // public function testGetCurrentUserReturnsNullWhenServiceReturnsNull()
    // {
    //     $this->authServiceMock
    //         ->method('getIdentity')
    //         ->willReturn(null);
    //
    //     $this->userModelMock
    //         ->expects( $this->never() )
    //         ->method('findOne');
    //
    //     $result = $this->auth->getCurrentUser();
    //
    //     $this->assertNull($result);
    //
    //     // run the getCurrentUser again now shouldn't call findOne again (or getIdentity)
    //     $result = $this->auth->getCurrentUser();
    // }
    //
    // public function testGetCurrentUserReturnsNullWhenModelReturnsNull()
    // {
    //     $this->authServiceMock
    //         ->method('getIdentity')
    //         ->willReturn('martyn@example.com');
    //
    //     $this->userModelMock
    //         ->expects( $this->once() )
    //         ->method('findOne')
    //         ->with( array(
    //             'email' => 'martyn@example.com',
    //         ) )
    //         ->willReturn(null);
    //
    //     $result = $this->auth->getCurrentUser();
    //
    //     $this->assertNull($result);
    // }
}
