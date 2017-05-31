<?php
// =============================================================================
// SLIDER-BELOW.PHP
// -----------------------------------------------------------------------------
// Handles slider below top menu.
// =============================================================================
?>
<?php

$slider = aps_has_slider_below();

if ($slider=='image-1'){
	
	$src 		= aps_slider_image(2);
	
	?>
	<div id="layout-slider2" class="layout-image section">
		<div class="container">
			<div class="row">
		
			<div class="aps-box layout-image-wrap aps-col col-xs-12">
				<img class="layout-image-center" src="<?php echo $src; ?>">		
			</div>
		
			</div>
		</div>
	</div>
	<?php
	
} else if ($slider=='slider-1'){
	
	$html = aps_layout_slider_html(2);
	
	?>
	<div id="layout-slider2" class="layout-slider section">
		<div class="container">
			<div class="container">
				<div class="aps-box layout-slider-wrap aps-col col-xs-12">
					<?php echo $html; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	
} else {
	return ;
}



?>


