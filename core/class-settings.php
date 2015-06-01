<?php


/**
 * Partially copied from on WPBDP's WPBDP_Settings class.
 *
 * @since 1.5
 */
class GRE_Settings {

    const PREFIX = 'greatrealestate_';

    private $deps = array();

    public function __construct() {
        $this->groups = array();
        $this->settings = array();

        add_filter( 'gre_settings_render', array( &$this, 'after_render' ), 0, 3 );
    }

    public function add_group($slug, $name, $help_text='') {
        $group = new StdClass();
        $group->wpslug = self::PREFIX . $slug;
        $group->slug = $slug;
        $group->name = esc_attr( $name );
        $group->help_text = $help_text;
        $group->sections = array();

        $this->groups[$slug] = $group;

        return $slug;
    }

    public function add_section($group_slug, $slug, $name, $help_text='') {
        $section = new StdClass();
        $section->name = esc_attr( $name );
        $section->slug = $slug;
        $section->help_text = $help_text;
        $section->settings = array();

        $this->groups[$group_slug]->sections[$slug] = $section;

        return "$group_slug:$slug";
    }

    public function add_setting( $section_key, $name, $label, $type = 'text', $default = null, $help_text = '', $args = array(),
                                 $validator = null, $callback = null ) {

        if ( $type == 'core' )
            return $this->add_core_setting( $name, $default );

        list($group, $section) = explode(':', $section_key);
        $args = !$args ? array() : $args;

        if (!$group || !$section)
            return false;

        if ( isset($this->groups[$group]) && isset($this->groups[$group]->sections[$section]) ) {
            $_default = $default;
            if (is_null($_default)) {
                switch ($type) {
                    case 'text':
                    case 'choice':
                        $_default = '';
                        break;
                    case 'boolean':
                        $_default = false;
                        break;
                    default:
                        $_default = null;
                        break;
                }
            }

            $setting = new StdClass();
            $setting->name = esc_attr( $name );
            $setting->label = $label;
            $setting->help_text = $help_text;
            $setting->default = $_default;
            $setting->type = $type;
            $setting->args = $args;
            $setting->validator = $validator;
            $setting->callback = $callback;

            $setup_cb = '_setting_' . $setting->type . '_setup';
            if ( is_callable( array( $this, $setup_cb ) ) ) {
                call_user_func_array( array( $this, $setup_cb ), array( &$setting ) );
            }

            $this->groups[$group]->sections[$section]->settings[$name] = $setting;
        }

        if (!isset($this->settings[$name])) {
            $this->settings[$name] = $setting;
        }

        return $name;
    }

    public function add_core_setting( $name, $default=null ) {
        $setting = new StdClass();
        $setting->name = $name;
        $setting->label = '';
        $setting->help_text = '';
        $setting->default = $default;
        $setting->type = 'core';
        $setting->args = array();
        $setting->validator = '';

        if ( !isset( $this->settings[ $name ] ) ) {
            $this->settings[ $name ] = $setting;
        }

        return true;
    }

    public function get_setting( $name ) {
        if ( isset( $this->settings[ $name ] ) )
            return $this->settings[ $name ];

        return false;
    }

    public function get($name, $ifempty=null) {
        $value =  get_option(self::PREFIX . $name, null);

        if (is_null($value)) {
            $default_value = isset($this->settings[$name]) ? $this->settings[$name]->default : null;

            if (is_null($default_value))
                return $ifempty;

            return $default_value;
        }

        if (!is_null($ifempty) && empty($value))
            $value = $ifempty;

        if ( $this->settings[$name]->type == 'boolean' ) {
            return $value == 'true' ? true : (boolean) intval( $value );
        } elseif ( 'choice' == $this->settings[$name]->type && isset( $this->settings[$name]->args['multiple'] ) && $this->settings[$name]->args['multiple'] ) {
            if ( ! $value )
                return array();
        }

        return $value;
    }

    public function set($name, $value, $onlyknown=true) {
        $name = strtolower($name);

        if ($onlyknown && !isset($this->settings[$name]))
            return false;

        if (isset($this->settings[$name]) && $this->settings[$name]->type == 'boolean') {
            $value = (boolean) intval($value);
        }

        update_option(self::PREFIX . $name, $value);

        return true;
    }

    /*
     * admin
     */
    public function after_render( $html, $setting, $args = array() ) {
        $html = '<a name="' . $setting->name . '"></a>' . $html;
        return $html;
    }

    public function _setting_custom($args) {
        $setting = $args['setting'];
        $value = $this->get( $setting->name );

        $html = '';

        ob_start();
        call_user_func( $setting->callback, $setting, $value );
        $custom_content = ob_get_contents();
        ob_end_clean();

        $html .= $custom_content;

        echo apply_filters( 'gre_settings_render', $html, $setting, $args );
    }

    public function _setting_text($args) {
        $setting = $args['setting'];
        $value = $this->get($setting->name);

        if (isset($args['use_textarea']) || strlen($value) > 100) {
            $html  = '<textarea id="' . $setting->name . '" name="' . self::PREFIX . $setting->name . '" rows="' . ( isset( $args['textarea_rows'] ) ? $args['textarea_rows'] : 4 ) . '">';
            $html .= esc_textarea($value);
            $html .= '</textarea><br />';
        } else {
            $html = '<input type="text" id="' . $setting->name . '" name="' . self::PREFIX . $setting->name . '" value="' . esc_attr( $value ) . '" size="' . (strlen($value) > 0 ? strlen($value) : 20). '" />';
        }

        $html .= '<span class="description">' . $setting->help_text . '</span>';

        echo apply_filters( 'gre_settings_render', $html, $setting, $args );
    }

    public function _setting_boolean($args) {
        $setting = $args['setting'];

        $value = (boolean) $this->get($setting->name);

        $html  = '<label for="' . $setting->name . '">';
        $html .= '<input type="checkbox" id="' .$setting->name . '" name="' . self::PREFIX . $setting->name . '" value="1" '
                  . ($value ? 'checked="checked"' : '') . '/>';
        $html .= '&nbsp;<span class="description">' . $setting->help_text . '</span>';
        $html .= '</label>';

        echo apply_filters( 'gre_settings_render', $html, $setting, $args );
    }

    public function _setting_choice($args) {
        $setting = $args['setting'];
        $choices = is_callable( $args['choices'] ) ? call_user_func( $args['choices'] ) : $args['choices'];

        $value = $this->get($setting->name);

        $multiple = isset( $args['multiple'] ) && $args['multiple'] ? true : false;
        $widget = $multiple ? ( isset( $args['use_checkboxes'] ) && $args['use_checkboxes'] ? 'checkbox' : 'multiselect' ) : 'select'; // TODO: Add support for radios.

        if ( 'multiselect' == $widget )
            $multiple = true;

        $html = '';

        if ( $widget == 'select' || $widget == 'multiselect' ) {
            $html .= '<select id="' . $setting->name . '" name="' . self::PREFIX . $setting->name . ( $multiple ? '[]' : '' ) . '" ' . ( $multiple ? 'multiple="multiple"' : '' ) . '>';

            $value = is_array( $value ) ? $value : array( $value );

            foreach ($choices as $ch) {
                $opt_label = is_array($ch) ? $ch[1] : $ch;
                $opt_value = is_array($ch) ? $ch[0] : $ch;
                $opt_class = ( is_array( $ch ) && isset( $ch[2] ) ) ? $ch[2] : '';

                $html .= '<option value="' . $opt_value . '"' . ( $value && in_array( $opt_value, $value ) ? ' selected="selected"' : '') . ' class="' . $opt_class . '">'
                          . $opt_label . '</option>';
            }

            $html .= '</select>';
        } elseif ( $widget == 'checkbox' ) {
            foreach ( $choices as $k => $v ) {
                $html .= sprintf( '<label><input type="checkbox" name="%s[]" value="%s" %s />%s</label><br />',
                                  self::PREFIX . $setting->name,
                                  $k,
                                  ( $value && in_array( $k, $value ) ) ? 'checked="checked"' : '',
                                  $v );
            }
        }

        $html .= '<span class="description">' . $setting->help_text . '</span>';

        echo apply_filters( 'gre_settings_render', $html, $setting, $args );
    }

    public function register_in_admin() {
        foreach ($this->groups as $group) {
            foreach ($group->sections as $section) {
                $callback = create_function('', 'echo "<a name=\"' . $section->slug . '\"></a>";');

                if ($section->help_text) {
                    $t = addslashes( $section->help_text );
                    $callback = create_function( '', 'echo \'<p class="description">' . $t . '</p>\';' );
                }

                add_settings_section($section->slug, $section->name, $callback, $group->wpslug);

                foreach ($section->settings as $setting) {
                    register_setting($group->wpslug, self::PREFIX . $setting->name/*, array( &$this, 'filter_x' ) */);
                    add_settings_field(self::PREFIX . $setting->name, $setting->label,
                                       array($this, '_setting_' . $setting->type),
                                       $group->wpslug,
                                       $section->slug,
                                       array_merge($setting->args, array('label_for' => $setting->name, 'setting' => $setting))
                                       );

                    if ( $setting->validator || ( $setting->type == 'choice' && isset( $setting->args['multiple'] ) && $setting->args['multiple'] ) ) {
                        add_filter('pre_update_option_' . self::PREFIX . $setting->name, create_function('$n, $o=null', 'return GRE_Settings::_validate_setting("' . $setting->name . '", $n, $o);'), 10, 2);
                    }
                }
            }
        }
    }

    public static function _validate_setting($name, $newvalue=null, $oldvalue=null) {
        $api = gre_plugin()->settings;
        $setting = $api->settings[$name];

        if ( $setting->type == 'choice' && isset( $setting->args['multiple'] ) && $setting->args['multiple'] ) {
            if ( isset( $_POST[ self::PREFIX . $name ] ) ) {
                $newvalue = $_POST[ self::PREFIX . $name ];
                $newvalue = is_array( $newvalue ) ? $newvalue : array( $newvalue );

                if ( $setting->validator )
                    $newvalue = call_user_func( $setting->validator, $setting, $newvalue, $api->get( $setting->name ) );
            }

            return $newvalue;
        }

        return call_user_func($setting->validator, $setting, $newvalue, $api->get($setting->name));
    }
}
