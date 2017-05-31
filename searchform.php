<?php
// =============================================================================
// SEARCHFORM.PHP
// -----------------------------------------------------------------------------
// Handles search form template.
// =============================================================================
?>
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
  	<label for="s" class="visually-hidden"><?php _e( 'Search', LANGUAGE_THEME ); ?></label>
		<input type="text" id="s" class="search-query" name="s" placeholder="<?php esc_attr_e( 'Search', LANGUAGE_THEME ); ?>" />
		<input type="submit" id="searchsubmit" class="hidden" name="submit" value="<?php esc_attr_e( 'Search', LANGUAGE_THEME ); ?>" />
  </div>
</form>