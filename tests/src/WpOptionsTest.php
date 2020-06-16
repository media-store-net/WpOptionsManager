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
use MonkeryTestCase\BrainMonkeyWpTestCase;
use phpDocumentor\Reflection\Types\Object_;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Type\ObjectType;

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
     * Testing default getMode()
     */
    public function testGetModeDefault()
    {
        $this->assertSame(
            'serialized',
            $this->_options->getMode(),
            'The mode should be serialized by default');
    }

    /**
     * Testing getMode() as json
     */
    public function testSetModeJson()
    {
        $this->_options->setMode('json');

        $this->assertSame(
            'json',
            $this->_options->getMode(),
            'The mode should be JSON now'
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

    /**
     * Testing registerOptions() exists
     */
    public function testregisterOptionsExists()
    {
        $this->assertTrue(
            method_exists($this->_options, 'registerOptions'),
            'This should be a existing function registerOptions()'
        );
    }

    /**
     * Testing setDefaultOptions() exists
     */
    public function testsetDefaultOptionsExists()
    {
        $this->assertTrue(
            method_exists($this->_options, 'setDefaultOptions'),
            'This should be a existing function setDefaultOptions()'
        );
    }

    /**
     * Testing getOptions as default array
     */
    public function testGetOptions()
    {
        Functions\stubs(
            [
                'get_option' => function ($name) {
                    return ['a' => 'b'];
                }
            ]
        );

        $this->_options->setMode('serialized');

        $this->assertEquals(
            ['a' => 'b'],
            $this->_options->getOptions(),
            'should be an array'
        );
    }

    /**
     * Testing getOptions as json saved string
     */
    public function testGetOptionsJson() {
        Functions\stubs(
            [
                'get_option' => function ($name) {
                    return json_encode(['a' => 'b']);
                }
            ]
        );
        $this->_options->setMode('json');

        $this->assertEquals(
            ['a' => 'b'],
            $this->_options->getOptions(),
            'should be an array encoded from json'
        );
    }

    /**
     * Testing saveAll as serialized string
     */
    public function testSaveAll() {
        Functions\stubs(
            [
                'update_option' => function ($name, $val) {
                    return is_array($val) || is_string($val) || is_float($val) || is_int($val) || is_bool($val) ?
                        true :
                        false;
                }
            ]
        );

        $this->_options->setMode('serialized');

        //array
        $this->assertTrue(
            $this->_options->saveAll(['a' => 'b']),
            'Should be truely by save an array');
        //string
        $this->assertTrue(
            $this->_options->saveAll('test'),
            'Should be truely by save an string');
        //float
        $this->assertTrue(
            $this->_options->saveAll(234.25),
            'Should be truely by save an float');
        //int
        $this->assertTrue(
            $this->_options->saveAll(234),
            'Should be truely by save an int');
        //bool
        $this->assertTrue(
            $this->_options->saveAll(true),
            'Should be truely by save an boolean');
        //object
        $this->assertFalse(
            $this->_options->saveAll(new Object_(null)),
            'Should be falsy by save an object');
    }

    /**
     * Testing saveAll as Json string
     */
    public function testSaveAllJson() {
        Functions\stubs(
            [
                'update_option' => function ($name, $val) {
                    return is_string($val) ?
                        true :
                        false;
                }
            ]
        );

        $this->_options->setMode('json');

        //array
        $this->assertTrue(
            $this->_options->saveAll(['a' => 'b']),
            'Should be truely by save an array');
        //string
        $this->assertTrue(
            $this->_options->saveAll('test'),
            'Should be truely by save an string');
        //float
        $this->assertTrue(
            $this->_options->saveAll(234.25),
            'Should be truely by save an float');
        //int
        $this->assertTrue(
            $this->_options->saveAll(234),
            'Should be truely by save an int');
        //bool
        $this->assertTrue(
            $this->_options->saveAll(true),
            'Should be truely by save an boolean');
        //object
        $this->assertTrue(
            $this->_options->saveAll(new Object_(null)),
            'Should be falsy by save an object');
    }

    /**
     * Testing deleteOptions
     */
    public function testDeleteOptions() {
        Functions\stubs(
            [
                'delete_option' => function ($name) {
                    return is_string($name) ? true : false ;
                },
                'register_setting' => true,
                'add_option'       => true
            ]
        );

        $this->_options->init('test');
        $this->assertTrue(
            $this->_options->deleteOptions(),
        'should be truely by existing name'
        );

        $this->_options->init(null);
        $this->assertFalse(
            $this->_options->deleteOptions(),
            'should be falsy by unknown options_name'
        );
    }

}
