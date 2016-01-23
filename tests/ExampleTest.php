<?php
/*
abstract class Model
{
    //...

    public static function factory($data)
    {
        $className = get_called_class();
		$obj = new $className($data);
		return $obj;
    }
}

class User extends Model
{

}

class ExampleController
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        return $this->user->factory(array('name' => 'Jim'));
    }
}

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {
        $user = new User(array('name' => 'Jim'));

        $modelStub = $this->getMockBuilder('User')
            ->disableOriginalConstructor()
            ->getMock();

        $modelStub
            ->method('factory')
            ->with(array('name' => 'Jim'))
            ->willReturn($user);

        $example = new ExampleController($modelStub);

        $this->assertEquals($user, $example->create());
    }
}*/
