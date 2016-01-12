<?php

use SlimMvc\Test\PHPUnit\TestCase;

class IndexControllerTests extends TestCase
{
    public function testApp()
    {
        $this->dispatch('/articles');

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }
}
