<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 10.09.2019
 * Time: 21:02
 */

namespace MediaStoreNet\WpOptionsManager\Test;

use Brain\Monkey;
use Brain\Monkey\Functions;
use MediaStoreNet\WpOptionsManager\WpOptions;
use PHPUnit\Framework\TestCase;

/**
 * Class WpOptionsTest
 *
 * @package MediaStoreNet\WpOptionsManager\Test
 */
class WpOptionsTest extends TestCase
{

    /**
     * @var WpOptions
     */
    private $_options;

    /**
     * Set setUp() function
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
        $this->_options = WpOptions::getInstance();
    }

    /**
     * Set tearDown() functiion
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        Monkey\tearDown();
        unset($this->_options);
    }

    /**
     * Testing of Class is exists
     */
    public function testClassExists()
    {
        $this->assertTrue(
            class_exists('MediaStoreNet\WpOptionsManager' . '\WpOptions'),
            'Class WpOptions should be existing'
        );
    }

    /**
     * Testing correct Instance of Class
     */
    public function testClassInstance()
    {
        $this->assertInstanceOf(
            'MediaStoreNet\WpOptionsManager\WpOptions',
            $this->_options,
            'Should be an Instance of WpOptions'
        );
    }

    /**
     * Testing of existing init() method
     */
    public function testInitExists()
    {
        $this->assertTrue(
            method_exists($this->_options, 'init'),
            'This should be a existing function init()'
        );
    }

    /**
     * Call init() method
     */
    public function testInit()
    {
        Functions\stubs(
            [
                'register_setting' => true,
                'add_option'       => true
            ]
        );

        $this->assertTrue(
            $this->_options->init('a', 'b', ['a' => 'b']),
            'Should be initialised...'
        );
    }

    public function testregisterOptionsExists()
    {
        $this->assertTrue(
            method_exists($this->_options, 'registerOptions'),
            'This should be a existing function registerOptions()'
        );
    }

    public function testsetDefaultOptionsExists()
    {
        $this->assertTrue(
            method_exists($this->_options, 'setDefaultOptions'),
            'This should be a existing function setDefaultOptions()'
        );
    }

}
