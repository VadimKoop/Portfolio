<?php
namespace HelpieFaq;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * HelpieFaq autoloader.
 *
 * HelpieFaq autoloader handler class is responsible for loading the different
 * classes needed to run the plugin.
 *
 * @since 1.6.0
 */
class Autoloader
{
    /**
     * Classes map.
     *
     * Maps HelpieFaq classes to file names.
     *
     * @since 1.6.0
     * @access private
     * @static
     *
     * @var array Classes used by HelpieFaq.
     */
    private static $classes_map = [
        // 'Rest_Interface' =>  'server/rest-interface.php',
    ];
    /**
     * Classes aliases.
     *
     * Maps HelpieFaq classes to aliases.
     *
     * @since 1.6.0
     * @access private
     * @static
     *
     * @var array Classes aliases.
     */
    private static $classes_aliases = [
        'Rest_Interface' => 'Server\Rest_Interface',
        // 'PageSettings\Manager' => 'Core\Settings\Page\Manager',
        // 'Revisions_Manager' => 'Modules\History\Revisions_Manager',
    ];
    /**
     * Run autoloader.
     *
     * Register a function as `__autoload()` implementation.
     *
     * @since 1.6.0
     * @access public
     * @static
     */
    public static function run()
    {
        spl_autoload_register([ __CLASS__, 'autoload' ]);
    }
    /**
     * Get classes aliases.
     *
     * Retrieve the classes aliases names.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @return array Classes aliases.
     */
    public static function get_classes_aliases()
    {
        return self::$classes_aliases;
    }
    /**
     * Load class.
     *
     * For a given class name, require the class file.
     *
     * @since 1.6.0
     * @access private
     * @static
     *
     * @param string $relative_class_name Class name.
     */
    private static function load_class($relative_class_name)
    {
        // error_log('load_class: ' . $relative_class_name);
        if (isset(self::$classes_map[ $relative_class_name ])) {
            $filename = HELPIE_FAQ_PATH . '/' . self::$classes_map[ $relative_class_name ];
        } else {
            $filename = strtolower(
                preg_replace(
                    [ '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                    [ '$1-$2', '-', DIRECTORY_SEPARATOR ],
                    $relative_class_name
                )
            );
            $filename = HELPIE_FAQ_PATH . '/' . $filename . '.php';
        }
        // error_log('filename: ' . $filename);
        if (is_readable($filename)) {
            require $filename;
        }
    }
    /**
     * Autoload.
     *
     * For a given class, check if it exist and load it.
     *
     * @since 1.6.0
     * @access private
     * @static
     *
     * @param string $class Class name.
     */
    private static function autoload($class)
    {

        if (0 !== strpos($class, __NAMESPACE__ . '\\')) {
            return;
        }


        $relative_class_name = preg_replace('/^' . __NAMESPACE__ . '\\\/', '', $class);
        $has_class_alias = isset(self::$classes_aliases[ $relative_class_name ]);
        // Backward Compatibility: Save old class name for set an alias after the new class is loaded
        if ($has_class_alias) {
            $relative_class_name = self::$classes_aliases[ $relative_class_name ];
        }


        $final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

       
        if (! class_exists($final_class_name)) {
            self::load_class($relative_class_name);
        }
        if ($has_class_alias) {
            class_alias($final_class_name, $class);
        }
    }
}
