<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/vejret/
 * @since             1.0.0
 * @package           Vejret
 *
 * @wordpress-plugin
 * Plugin Name:       Vejret Widget
 * Plugin URI:        https://www.vejreti.com/widgets
 * Description:       Danish weather widget with top UI.
 * Version:           1.0.0
 * Author:            vejret
 * Author URI:        https://profiles.wordpress.org/vejret/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vejret
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('VEJRET_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vejret-activator.php
 */
function activate_vejret()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-vejret-activator.php';
    Vejret_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vejret-deactivator.php
 */
function deactivate_vejret()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-vejret-deactivator.php';
    Vejret_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_vejret');
register_deactivation_hook(__FILE__, 'deactivate_vejret');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-vejret.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vejret()
{

    $plugin = new Vejret();
    $plugin->run();

}

run_vejret();



class vejret_widget extends WP_Widget
{
    // Set up the widget name and description.
    public function __construct()
    {
        $widget_options = array('classname' => 'vejret_widget', 'description' => 'Danish weather forecast widget');
        parent::__construct('vejret_widget', 'Vejret Widget', $widget_options);
    }


    // Create the widget output.
    public function widget($args, $instance)
    {
        // Keep this line
        echo $args['before_widget'];

        $city = $instance['city'];
        $country = $instance['country'];
        $backgroundColor = $instance['backgroundColor'];
        $widgetWidth = $instance['widgetWidth'];
        $textColor = $instance['textColor'];
        $days = $instance['days'];
        $showSunrise = $instance['showSunrise'];
        $showWind = $instance['showWind'];
        $language = $instance['language'];
        $showCurrent = $instance['showCurrent'];

        ?>
        <div id="weather-widget-vejret"
             class="weather_widget_wrap"
             data-text-color='<?php echo $textColor ?>'
             data-background="<?php echo $backgroundColor ?>"
             data-width="<?php echo $widgetWidth ?>"
             data-days="<?php echo $days ?>"
             data-sunrise="<?php echo $showSunrise ?>"
             data-wind="<?php echo $showWind ?>"
             data-current="<?php echo $showCurrent ?>"
             data-language="<?php echo $language ?>"
             data-city="<?php echo $city ?>"
             data-country="<?php echo $country; ?>">

            <div class="weather_widget_placeholder"></div>
            <div style="font-size: 14px;text-align: center;padding-top: 6px;padding-bottom: 4px;background: rgba(0,0,0,0.03);">
                Powered by <a target="_blank" href="https://www.vejreti.com">Vejreti.com</a>
            </div>
        </div>
        <?php echo $args['after_widget'];
    }


    // Create the admin area widget settings form.
    public function form($instance)
    {
        // print_r($instance);
        $city = !empty($instance['city']) ? $instance['city'] : 'Copenhagen';
        $country = !empty($instance['country']) ? $instance['country'] : 'Denmark';
        $backgroundColor = !empty($instance['backgroundColor']) ? $instance['backgroundColor'] : '#becffb';
        $textColor = !empty($instance['textColor']) ? $instance['textColor'] : '#000000';

        if (isset($instance['widgetWidth'])) {
            $widgetWidth = $instance['widgetWidth'];
        } else {
            $widgetWidth = '100';
        }

        if (isset($instance['days'])) {
            $days = $instance['days'];
        } else {
            $days = 3;
        }

        if (isset($instance['language'])) {
            $language = $instance['language'];
        } else {
            $language = "danish";
        }


        if (isset($instance['showSunrise'])) {
            $showSunrise = $instance['showSunrise'];
        } else {
            $showSunrise = "";
        }

        if (isset($instance['showWind'])) {
            $showWind = $instance['showWind'];
        } else {
            $showWind = "";
        }

        $showCurrent = !empty($instance['showCurrent']) ? $instance['showCurrent'] : 'on';

        ?>
        <div class="weer_form">
            <div class="form-section">
                <h3>Location</h3>
                <div class="form-line">
                    <label class="text-label" for="<?php echo $this->get_field_id('city'); ?>">City:</label>
                    <input type="text" id="<?php echo $this->get_field_id('city'); ?>"
                           name="<?php echo $this->get_field_name('city'); ?>"
                           value="<?php echo esc_attr($city); ?>"/>
                </div>
                <div class="form-line">
                    <label class="text-label" for="<?php echo $this->get_field_id('country'); ?>">Country:</label>
                    <input type="text" id="<?php echo $this->get_field_id('country'); ?>"
                           name="<?php echo $this->get_field_name('country'); ?>"
                           value="<?php echo esc_attr($country); ?>"/>
                </div>
            </div>

            <div class="form-section">
                <h3>Widget Language</h3>
                <div class="form-line">
                    <select name="<?php echo $this->get_field_name('language'); ?>">
                        <option value="english" <?php if ($language == "english") {
                            echo 'selected';
                        } ?>>English
                        </option>
                        <option value="danish" <?php if ($language == "danish") {
                            echo 'selected';
                        } ?>>Danish
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3>Weather Data</h3>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showCurrent == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showCurrent'); ?>"
                           name="<?php echo $this->get_field_name('showCurrent'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showCurrent'); ?>">Show: Current weather</label>
                </div>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showWind == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showWind'); ?>"
                           name="<?php echo $this->get_field_name('showWind'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showWind'); ?>">Show: Chance for rain, Wind and
                        Humidity</label>
                </div>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showSunrise == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showSunrise'); ?>"
                           name="<?php echo $this->get_field_name('showSunrise'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showSunrise'); ?>">Show: Sunrise and sunset time</label>
                </div>
            </div>
            <div class="form-section">
                <h3>Daily Forecast</h3>
                <div class="form-line">
                    <select name="<?php echo $this->get_field_name('days'); ?>">
                        <option value="0" <?php if ($days == 0) {
                            echo 'selected';
                        } ?>>No Daily Forecast
                        </option>
                        <option value="2" <?php if ($days == 2) {
                            echo 'selected';
                        } ?>>2 Days
                        <option value="3" <?php if ($days == 3) {
                            echo 'selected';
                        } ?>>3 Days
                        </option>
                        <option value="4" <?php if ($days == 4) {
                            echo 'selected';
                        } ?>>4 Days
                        </option>
                        <option value="5" <?php if ($days == 5) {
                            echo 'selected';
                        } ?>>5 Days
                        </option>
                        <option value="6" <?php if ($days == 6) {
                            echo 'selected';
                        } ?>>6 Days
                        </option>
                    </select>
                </div>
            </div>


            <div class="form-section">
                <h3>Look & Feel</h3>
                <div class="form-line">
                    <label for="<?php echo $this->get_field_id('widgetWidth'); ?>">Widget Stretch (width):</label>
                    <input type="radio" id="<?php echo $this->get_field_id('widgetWidth'); ?>"
                        <?php if ($widgetWidth == '100') {
                            echo 'checked';
                        }; ?>
                           name="<?php echo $this->get_field_name('widgetWidth'); ?>"
                           value="100"/> 100%
                    <input type="radio" id="<?php echo $this->get_field_id('widgetWidth'); ?>"
                        <?php if ($widgetWidth == 'tight') {
                            echo 'checked';
                        }; ?>
                           name="<?php echo $this->get_field_name('widgetWidth'); ?>"
                           value="tight"/> Tight as possible
                </div>
                <div class="form-line">
                    <label for="<?php echo $this->get_field_id('backgroundColor'); ?>">Background Color
                        (optional):</label>
                    <input type="color" id="<?php echo $this->get_field_id('backgroundColor'); ?>"
                           name="<?php echo $this->get_field_name('backgroundColor'); ?>"
                           value="<?php echo esc_attr($backgroundColor); ?>"/>
                </div>
                <div class="form-line">
                    <label for="<?php echo $this->get_field_id('textColor'); ?>">Text Color (optional):</label>
                    <input type="color" id="<?php echo $this->get_field_id('textColor'); ?>"
                           name="<?php echo $this->get_field_name('textColor'); ?>"
                           value="<?php echo esc_attr($textColor); ?>"/>
                </div>
            </div>
        </div>
        <?php
    }


    // Apply settings to the widget instance.
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['city'] = strip_tags($new_instance['city']);
        $instance['country'] = strip_tags($new_instance['country']);
        $instance['backgroundColor'] = strip_tags($new_instance['backgroundColor']);
        $instance['textColor'] = strip_tags($new_instance['textColor']);
        $instance['widgetWidth'] = strip_tags($new_instance['widgetWidth']);
        $instance['showSunrise'] = $new_instance['showSunrise'];
        $instance['showWind'] = $new_instance['showWind'];
        $instance['showCurrent'] = $new_instance['showCurrent'];
        $instance['days'] = strip_tags($new_instance['days']);
        $instance['language'] = strip_tags($new_instance['language']);
        if ($new_instance['showSunrise'] != "on") {
            $instance['showSunrise'] = "false";
        }
        if ($new_instance['showWind'] != "on") {
            $instance['showWind'] = "false";
        }
        if ($new_instance['showCurrent'] != "on") {
            $instance['showCurrent'] = "false";
        }

        return $instance;
    }


}

// Register the widget.
function jpen_register_vejret_widget()
{
    register_widget('vejret_widget');
}

add_action('widgets_init', 'jpen_register_vejret_widget');
