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
class ButterBean_Control_Gallery extends ButterBean_Control {

    /**
     * The type of control.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'gallery';

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
                'upload' => esc_html__('Add image', 'stm_vehicles_listing'),
                'set' => esc_html__('Add images', 'stm_vehicles_listing'),
                'choose' => esc_html__('Choose images', 'stm_vehicles_listing'),
                'change' => esc_html__('Add images', 'stm_vehicles_listing'),
                'remove' => esc_html__('Remove all', 'stm_vehicles_listing'),
                'drop' => esc_html__('Drag and drop featured image here', 'stm_vehicles_listing'),
                'placeholder' => esc_html__('No images selected', 'stm_vehicles_listing')
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

        $size_thumb = 'stm-img-350-205';

        $this->json['l10n'] = $this->l10n;
        $this->json['size'] = $this->size;
        $this->json['size_thumb'] = $size_thumb;

        $post_id = $this->manager->post_id;

        //Get gallery as array of ids;
        $value = $this->get_value();


        if(empty($value)) {
            $value = array();
        }

        /*Add featured image in array of gallery ids on the first position*/
        if(has_post_thumbnail($post_id) and $this->name === 'gallery') {
			if(count($value) > 0 && $value[0] != get_post_thumbnail_id($post_id)) array_unshift($value, get_post_thumbnail_id($post_id));
			elseif( count($value) <= 1 ) $value = array(get_post_thumbnail_id($post_id));
        }

        $values = array();

        if(!empty($value)) {
            foreach ($value as $img_id) {
                $img_id = intval($img_id);
                if(!empty($img_id)) {
                    $values[] = $img_id;
                }
            }
        }

        $value_keys = implode(',', $values);

        $images = array();

        if ( $values ) {
            foreach ($values as $image) {
                $key = $image;
                $image = wp_get_attachment_image_src(absint($key), $this->size);
                $thumbnail = wp_get_attachment_image_src(absint($key), $size_thumb);
                if(!empty($image) and !empty($image[0])) {
                    $images[] = array(
                        'id' => $key,
                        'src' => $image[0],
                        'thumb' => $thumbnail[0]
                    );
                }
            }
        }

        $this->json['values'] = $images;
        $this->json['value'] = $value_keys;
    }
}