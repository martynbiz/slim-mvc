<?php
namespace Tests\Controllers;

class IndexControllerTest extends \TestCase
{
    public function testIndexRoute()
    {
        $this->dispatch('');

        $this->assertController('index');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }

    public function testContactRoute()
    {
        $this->dispatch('/contact');

        $this->assertController('index');
        $this->assertAction('contact');
        $this->assertStatusCode(200);
    }
}
