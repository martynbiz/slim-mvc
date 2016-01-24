<?php
namespace Tests\Models;

use Wordup\Model\Tag;

class TagTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $tag = new Tag();

        $this->assertTrue($tag instanceof Tag);
    }

    public function testWhitelist()
    {
        $values = array(
            // whitelisted - e.g. name
            'name' => 'The name',
            'slug' => 'slug',
        );

        $tag = new Tag($values);

        $this->assertEquals($values['name'], $tag->name);
        $this->assertEquals($values['slug'], $tag->slug);
    }

    /**
     * @dataProvider getInvalidTagData
     */
    public function testValidateFailsWhenInvalidValuesSet($values)
    {
        $tag = new Tag($values);

        $this->assertFalse( $tag->validate() );
    }

    public function getInvalidTagData()
    {
        return array(
            array(
                array(
                    // 'name' => 'My tag',
                    'slug' => 'my-tag',
                ),
            ),
            array(
                array(
                    'name' => 'My tag',
                    // 'slug' => 'my-tag',
                ),
            ),
        );
    }
}
