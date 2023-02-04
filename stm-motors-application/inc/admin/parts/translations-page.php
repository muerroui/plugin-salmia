<?php
$file = file_get_contents(STM_MOTORS_APP_INC_PATH . 'translations/translations.xml');
$xml = simplexml_load_string($file);

$translations = get_option('translations', '');

$options = (function_exists('stm_listings_get_my_options_list')) ? stm_listings_get_my_options_list() : array();
$stm_options = [];
foreach ($options as $option) {
	$stm_options[$option['slug']] = $option['single_name'];
}
?>

<div class="stm-translations-page-wrap">
    <h3>Translations</h3>
    <div class="translation-form">
        <table>
			<?php

			foreach ($xml as $k => $val) :
				if (empty($val->Key)) continue;
				$key = trim((string)$val->Key);
				$key1 = preg_replace('/\_/', '-', $key);
				if (in_array($key, array_keys($stm_options)) || in_array($key1, array_keys($stm_options))) {
					unset($stm_options[$key]);
					unset($stm_options[$key1]);
				}
				if ($key = 'year') unset($stm_options['ca-year']);
				$k = trim((string)$val->Key);
				$str = (!empty($translations[$k])) ? $translations[$k] : $val->Value;

				get_stm_row($val, $str);
			endforeach;

			foreach ($stm_options as $k => $val) {
				$str = (!empty($translations[$k])) ? $translations[$k] : $val;
				$stm_value = new stdClass();
				$stm_value->Value =  $val;
				$stm_value->Key =  $k;
				get_stm_row($stm_value, $str);
			}
			?>
        </table>
    </div>
</div>

<?php function get_stm_row($val, $str)
{
	?>
    <tr>
        <td><?php echo esc_html($val->Value); ?></td>
        <td>
            <input type="text" data-key="<?php echo esc_attr($val->Key); ?>" name="stm-ma-str-placeholder"
                   value="<?php echo esc_attr($str); ?>"/>
            <input type="hidden" data-key="hidden-<?php echo esc_attr($val->Key); ?>"
                   name="strings[<?php echo esc_attr($val->Key); ?>]"
                   value="<?php echo esc_attr($str); ?>"/>
        </td>
    </tr>
<?php } ?>
