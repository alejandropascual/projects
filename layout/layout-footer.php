<?php
// =============================================================================
// LAYOUT-FOOTER.PHP
// -----------------------------------------------------------------------------
// Handles footer with widgets.
// =============================================================================
?>
<?php

$footer = aps_has_footer();
if ($footer=='') return;

?>

<div id="footer" class="layout-footer">
	<div class="container">
		<div class="row">
			<div class="aps-col col-xs-12">
			
				<?php
				$num = aps_footer_num_of_areas();
				$cols = 12/$num;
				for($column=1; $column<=$num; $column++)
				{
					echo '<div class="widget-area aps-col col-xs-12 col-sm-'.$cols.'">';
					echo aps_get_option_footer_widget($footer, $column);
					echo '</div>';
				}
				?>
			
			</div>
		</div>
	</div>
</div>
