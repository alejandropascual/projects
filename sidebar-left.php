<?php
// =============================================================================
// SIDEBAR-LEFT.PHP
// -----------------------------------------------------------------------------
// Handles sidebar content.
// Check first if the layout use it.
// =============================================================================
?>

<?php
$option = aps_has_left_sidebar();
if (false == $option) return;
?>

<div id="left-sidebar" class="<?php aps_sidebar_class('left'); ?>">
		<div class="row">
			<div class="aps-col col-xs-12">
			
				<?php
				//Widgets
				if ($option == 'content-2'){
					echo aps_get_option_widget( 'layout_content_widgets2_left' );
				} else if ($option == 'content-3'){
					echo aps_get_option_widget( 'layout_content_widgets3_left' );
				}
				?>
				
			</div>
		</div>
</div>
<div id="left-sidebar-open"></div>