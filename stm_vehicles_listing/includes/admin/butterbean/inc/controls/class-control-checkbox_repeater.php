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
class ButterBean_Control_Checkbox_Repeater extends ButterBean_Control {

    /**
     * The type of control.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'checkbox_repeater';

    /**
     * Array of text labels to use for the media upload frame.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $l10n = array();

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
                'add_feature'      => esc_html__( 'Add new feature',         'stm_vehicles_listing' ),
                'add'      => esc_html__( 'Add',         'stm_vehicles_listing' ),
                'all'      => esc_html__( 'Show all',         'stm_vehicles_listing' ),
                'remove'      => esc_html__( 'Delete',      'stm_vehicles_listing' ),
            )
        );
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

		$value = array_map("trim", explode(',', $this->get_value()));


		/*Generate list of terms*/
		$terms = get_terms( array(
			'taxonomy' => 'stm_additional_features',
			'hide_empty' => false,
		) );

		//todo Remove after patching database
		//$tmp = array();
		$values = array();

		if(!is_wp_error($terms) and !empty($terms)) {

			foreach($terms as $term) {

				$checked = false;

				if(in_array($term->name, $value)) {
					$checked = true;
					//todo Remove after patching database
					//$tmp[] = $term->name;
				}

				$values[] = array(
					'val' => $term->name,
					'checked' => $checked
				);
			}
		}

		/*//todo Remove after patching database
		if(!empty($value)) {
		    $value = array_filter(array_unique($value));
			foreach($value as $name) {
				if(!in_array($name, $tmp)) {
					$values[] = array(
						'val' => $name,
						'checked' => true
					);
				}
			}
		}*/

        $this->json['link'] = get_site_url() . "/wp-admin/edit-tags.php?taxonomy=stm_additional_features&post_type=listings";
        $this->json['values'] = $values;

    }
}