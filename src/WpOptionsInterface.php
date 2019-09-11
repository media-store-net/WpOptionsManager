<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 08.09.2019
 * Time: 19:51
 */

namespace MediaStoreNet\WpOptionsManager;


/**
 * Interface WpOptionsInterface
 *
 * @package MediaStoreNet\WpOptionsManager
 */
interface WpOptionsInterface
{

    /**
     * Initialisation includes register and set the defaultOptions Value
     * should be called at once by bootstraping the Plugin or Theme
     *
     * @param string $optionsName
     * @param string $optionsGroup
     * @param array $defaultOptions
     *
     * @return void
     */
    function init($optionsName, $optionsGroup, $defaultOptions);

    /**
     * @return mixed
     */
    function getOptions();

    /**
     * @param $names
     *
     * @return mixed
     */
    function getByName($names);

    /**
     * @param array $options
     *
     * @return bool
     */
    function saveAll($options);

    /**
     * @param $names
     * @param $option
     *
     * @return mixed
     */
    function saveByName($names, $option);

    /**
     * @return boolean
     */
    function deleteOptions();
}
