<div class="col-md-3 col-sm-3 col-xs-12">
    <h2 class="compare-title"><?php echo esc_html($title_text); ?></h2>
    <div class="colored-separator text-left">
        <?php if (stm_is_boats()): ?>
            <div><i class="stm-boats-icon-wave stm-base-color"></i></div>
        <?php else: ?>
            <div class="first-long"></div>
            <div class="last-short"></div>
        <?php endif; ?>
    </div>
</div>