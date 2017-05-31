<?php
// =============================================================================
// SIDEBAR-RIGHT.PHP
// -----------------------------------------------------------------------------
// Handles sidebar content.
// Check first if the layout use it.
// =============================================================================
?>

<?php
$option = aps_has_right_sidebar();
if (false == $option) return;
?>

<div id="right-sidebar" class="<?php aps_sidebar_class('right'); ?>">
		<div class="row">
			<div class="aps-col col-xs-12">

				<?php
				//Widgets
				if ($option == 'content-2'){
					echo aps_get_option_widget( 'layout_content_widgets2_right' );
				} else if ($option == 'content-4'){
					echo aps_get_option_widget( 'layout_content_widgets4_right' );
				}
				?>
				
			</div>
		</div>
</div>
<div id="right-sidebar-open"></div>