<?php
/**
 * Image control class.  This control allows users to set an image.  It passes the attachment
 * ID the setting, so you'll need a custom control class if you want to store anything else,
 * such as the URL or other data.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Image control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_File extends ButterBean_Control {

    /**
     * The type of control.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'file';

    /**
     * Array of text labels to use for the media upload frame.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $l10n = array();

    /**
     * Image size to display.  If the size isn't found for the image,
     * the full size of the image will be output.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $size = 'large';

    /**
     * Creates a new control object.
     *
     * @since  1.0.0
     * @access public
     * @param  object  $manager
     * @param  string  $name
     * @param  array   $args
     * @return void
     */
    public function __construct( $manager, $name, $args = array() ) {
        parent::__construct( $manager, $name, $args );

        $this->l10n = wp_parse_args(
            $this->l10n,
            array(
                'upload'      => esc_html__( 'Add pdf',         'stm_vehicles_listing' ),
                'set'         => esc_html__( 'Set as pdf',      'stm_vehicles_listing' ),
                'choose'      => esc_html__( 'Choose pdf',      'stm_vehicles_listing' ),
                'change'      => esc_html__( 'Change pdf',      'stm_vehicles_listing' ),
                'remove'      => esc_html__( 'Remove pdf',      'stm_vehicles_listing' ),
                'placeholder' => esc_html__( 'No pdf selected', 'stm_vehicles_listing' )
            )
        );
    }

    /**
     * Enqueue scripts/styles for the control.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {

        wp_enqueue_script( 'media-views' );
    }

    /**
     * Adds custom data to the json array.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function to_json() {
        parent::to_json();

        $this->json['l10n'] = $this->l10n;
        $this->json['size'] = $this->size;
        $this->json['format'] = $this->attr['data-type'];

        $value = $this->get_value();
        $file = '';

        if ( $value ) {
            $file = basename(get_attached_file($value));
        }

        $this->json['src'] = $file;
    }
}