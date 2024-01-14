<?php

if (!class_exists('MV_Slider_Setting')) {
    class MV_Slider_Setting
    {
        public static $options;

        public function __construct()
        {
            self::$options = get_option('mv_slider_options');
            add_action('admin_init', array($this, 'admin_init'));
        }

        public function admin_init()
        {
            register_setting('mv_slider_group', 'mv_slider_options', array( $this, 'mv_slider_validate' ));

            add_settings_section(
                'mv_slider_main_section',
                esc_html__('How does it work?', 'mv-slider'),
                null,
                'mv_slider_page1'
            );

            add_settings_section(
                'mv_slider_second_section',
                esc_html__('Other Plugin Options', 'mv-slider'),
                null,
                'mv_slider_page2'
            );

            add_settings_field(
                'mv_slider_shortcode',
                esc_html__('Shortcode', 'mv-slider'),
                array($this, 'mv_slider_shortcode_callback'),
                'mv_slider_page1',
                'mv_slider_main_section',
            );

            add_settings_field(
                'mv_slider_title',
                esc_html__('Slider Title', 'mv-slider'),
                array($this, 'mv_slider_title_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_title'
                )
            );

            add_settings_field(
                'mv_slider_bullets',
                esc_html__('Display Bullets', 'mv-slider'),
                array($this, 'mv_slider_bullets_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_bullets'
                )
            );

            add_settings_field(
                'mv_slider_nav',
                esc_html__('Display Nav', 'mv-slider'),
                array($this, 'mv_slider_nav_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_nav'
                )
            );

            add_settings_field(
                'mv_slider_touch',
                esc_html__('Add Touch', 'mv-slider'),
                array($this, 'mv_slider_touch_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_touch'
                )
            );

            add_settings_field(
                'mv_slider_style',
                esc_html__('Slider Style', 'mv-slider'),
                array($this, 'mv_slider_style_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2',
                    ),
                    'label_for' => 'mv_slider_style'
                )
            );

            add_settings_field(
                'mv_slider_animation',
                esc_html__('Slider Animation', 'mv-slider'),
                array($this, 'mv_slider_animation_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'items' => array(
                        'slide',
                        'fade',
                    ),
                    'label_for' => 'mv_slider_animation'
                )
            );
        }

        public function mv_slider_shortcode_callback() {
            ?>
                <span><?php _e('Use the shortcode [mv_slider] to display the slider in any page/post/widget', 'mv-slider'); ?></span>
            <?php
        }

        public function mv_slider_title_callback( $args ) {
            ?>
                <input
                    type="text"
                    name="mv_slider_options[mv_slider_title]"
                    id="mv_slider_title"
                    value="<?php echo isset( self::$options['mv_slider_title'] ) ? esc_attr( self::$options['mv_slider_title'] ) : ''; ?>"
                >
            <?php
        }

        public function mv_slider_bullets_callback( $args ) {
            ?>
                <input
                    type="checkbox"
                    name="mv_slider_options[mv_slider_bullets]"
                    id="mv_slider_bullets"
                    value="1"
                    <?php
                    if(isset(self::$options['mv_slider_bullets'])) {
                        checked( '1', self::$options['mv_slider_bullets'], true );
                    }
                    ?>
                />

                <label for="mv_slider_bullets"><?php _e('Whether to display bullets or not', 'mv-slider'); ?></label>
            <?php
        }

        public function mv_slider_nav_callback( $args ) {
            ?>
                <input
                    type="checkbox"
                    name="mv_slider_options[mv_slider_nav]"
                    id="mv_slider_nav"
                    value="1"
                    <?php
                    if(isset(self::$options['mv_slider_nav'])) {
                        checked( '1', self::$options['mv_slider_nav'], true );
                    }
                    ?>
                />

                <label for="mv_slider_nav"><?php _e('Whether to display nav or not', 'mv-slider'); ?></label>
            <?php
        }

        public function mv_slider_touch_callback( $args ) {
            ?>
                <input
                    type="checkbox"
                    name="mv_slider_options[mv_slider_touch]"
                    id="mv_slider_touch"
                    value="1"
                    <?php
                    if(isset(self::$options['mv_slider_touch'])) {
                        checked( '1', self::$options['mv_slider_touch'], true );
                    }
                    ?>
                />

                <label for="mv_slider_touch"><?php _e('Whether to add touch or not', 'mv-slider'); ?></label>
            <?php
        }

        public function mv_slider_style_callback( $args ) {
            ?>
                <select
                    id="mv_slider_style"
                    name="mv_slider_options[mv_slider_style]"
                >
                    <!-- <option value="style-1"
                            <?php isset(self::$options['mv_slider_style']) ? selected('style-1', self::$options['mv_slider_style'], true) : ''; ?>>Style 1</option>
                    <option value="style-2"
                            <?php isset(self::$options['mv_slider_style']) ? selected('style-2', self::$options['mv_slider_style'], true) : ''; ?>>Style 2</option> -->

                        <?php foreach($args['items'] as $item) : ?>
                            <option value="<?php echo esc_attr($item); ?>"
                                <?php isset(self::$options['mv_slider_style']) ? selected($item, self::$options['mv_slider_style'], true) : ''; ?>>
                                <?php echo esc_html(ucfirst($item)); ?>
                            </option>
                        <?php endforeach; ?>
                </select>


            <?php
        }

        public function mv_slider_animation_callback( $args ) {
            ?>
                <select
                    id="mv_slider_animation"
                    name="mv_slider_options[mv_slider_animation]"
                >
                        <?php foreach($args['items'] as $item) : ?>
                            <option value="<?php echo esc_attr($item); ?>"
                                <?php isset(self::$options['mv_slider_animation']) ? selected($item, self::$options['mv_slider_animation'], true) : ''; ?>>
                                <?php echo esc_html(ucfirst($item)); ?>
                            </option>
                        <?php endforeach; ?>
                </select>


            <?php
        }

        public function mv_slider_validate( $input ) {
            $new_input = get_option("mv_slider_options");
            foreach( $input as $key => $value ){
                switch($key){
                    case 'mv_slider_title':
                        if( empty($value) ) {
                            add_settings_error('mv_slider_options', 'mv_slider_message', esc_html__('The title field can\'t be left empty', 'mv-slider'), 'error');
                        }
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                    case 'mv_slider_url':
                        $new_input[$key] = esc_url_raw($value);
                        break;
                    case 'mv_slider_int':
                        $new_input[$key] = absint($value);
                        break;
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }

            }
            return $new_input;
        }
    }
}
