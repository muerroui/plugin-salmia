<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="listing-list-loop stm-isotope-listing-item">

	<?php stm_listings_load_template('loop/list/image'); ?>

	<div class="content">
		<div class="meta-top">
			<!--Price-->
			<?php stm_listings_load_template('loop/list/price'); ?>
			<!--Title-->
			<?php stm_listings_load_template('loop/list/title'); ?>
		</div>

		<!--Item parameters-->
		<div class="meta-middle">
			<?php stm_listings_load_template('loop/list/options'); ?>
		</div>

		<!--Item options-->
		<div class="meta-bottom">
			<?php stm_listings_load_template('loop/list/features'); ?>
		</div>
	</div>
</div>
