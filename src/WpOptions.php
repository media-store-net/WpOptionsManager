<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 08.09.2019
 * Time: 20:18
 * PHP version 5
 */

namespace MediaStoreNet\WpOptionsManager;

/**
 * Class WpOptions
 *
 * @author  Artur Voll <kontakt@media-store.net>
 * @package MediaStoreNet\WpOptionsManager
 */
class WpOptions implements WpOptionsInterface
{

    /**
     * Instance of WpOptions Class
     *
     * @var WpOptions
     */
    private static $_instance;

    /**
     * Name of the option to set
     *
     * @var string
     */
    protected $options_name;

    /**
     * Name of Options Group
     *
     * @var string
     */
    protected $options_group;

    /**
     * Default Options Value as array
     *
     * @var array
     */
    protected $default_options;

    /**
     * Static call of WpOptions Instance
     * if $_instance is null, will create a new Instance of Class
     *
     * @return WpOptions
     */
    public static function getInstance()
    {
        // Initialize the service if it's not already set.
        if (self::$_instance === null) {
            self::$_instance = new WpOptions();
        }

        // Return the instance.
        return self::$_instance;
    }

    /**
     * WpOptions constructor.
     */
    public function __construct()
    {
    }

    /**
     * Initialisation includes register and set the defaultOptions Value
     * should be called at once by bootstraping the Plugin or Theme
     *
     * @param string $optionsName    //
     * @param string $optionsGroup   //
     * @param array  $defaultOptions //
     *
     * @return bool|\BadFunctionCallException
     */
    public function init($optionsName, $optionsGroup, $defaultOptions)
    {
        $this->options_name    = $optionsName;
        $this->options_group   = $optionsGroup;
        $this->default_options = $defaultOptions;
        try {
            // Register Options
            $this->registerOptions($this->options_group, $this->options_name);
            // Add Options by default
            $this->setDefaultOptions();

            return true;
        } catch ( \BadFunctionCallException $exception ) {
            throw new \BadFunctionCallException(
                __('Konnte nicht initialisiert werden...')
            );
        }
    }

    /**
     * Register a group of options
     *
     * @param string $optionsName  //
     * @param string $optionsGroup //
     *
     * @return bool|\BadFunctionCallException
     * @see    https://developer.wordpress.org/reference/functions/register_setting/
     */
    protected function registerOptions($optionsName, $optionsGroup)
    {
        if (function_exists('register_setting')) :
            register_setting($optionsGroup, $optionsName);

            return true;
        endif;

        return false;
    }

    /**
     * Add options by default
     *
     * @return bool
     * @see    https://developer.wordpress.org/reference/functions/add_option/
     */
    protected function setDefaultOptions()
    {
        if (function_exists('add_option')) :
            if (add_option($this->options_name, $this->default_options)) {
                return true;
            } else {
                return false;
            }
        endif;

        return false;
    }

    /**
     * Get all the options with the given optionsName
     *
     * @return array|mixed
     * @see    https://developer.wordpress.org/reference/functions/get_option/
     */
    public function getOptions()
    {
        return (array) get_option($this->options_name);
    }

    /**
     * Get one or more option values from the array
     *
     * @param string|array $names //
     *
     * @return array|mixed
     */
    function getByName($names)
    {
        $options = $this->getOptions();

        if (is_array($names)) :
            $output = array();

            foreach ( $names as $key => $value ) {
                $output[$value] = $options[$key][$value];
            }

            return $output;
        else:
            return $options[$names];
        endif;
    }

    /**
     * Save all options array
     *
     * @param array $options //
     *
     * @return bool|mixed
     */
    function saveAll($options)
    {
        if (update_option($this->options_name, $options)) :
            print '<script>window.location.reload();</script>';

            return true;
        else:
            return false;
        endif;
    }

    /**
     * Save one or more options
     *
     * @param string|array $names  //
     * @param string|array $option //
     *
     * @return mixed|void
     */
    function saveByName($names, $option)
    {
        $options = $this->getOptions();

        if (is_array($names)) :
            foreach ( $names as $name ) {
                $options[$name] = $option[$name];
            }
        else:
            $options[$names] = $option;
        endif;

        $this->saveAll($options);
    }

    /**
     * Delete the optionsGroup from the wp_options table
     *
     * @return bool|void
     * @see    https://developer.wordpress.org/reference/functions/delete_option/
     */
    function deleteOptions()
    {
        return delete_option($this->options_name);
    }
}
