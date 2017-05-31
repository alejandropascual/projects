<?php
// =============================================================================
// SOCKET.PHP
// -----------------------------------------------------------------------------
// Handles socket below footer.
// =============================================================================
?>
<?php
$socket = aps_has_socket();
if ($socket=='') return;

?>

<div id="socket" class="layout-socket">
	<div class="container">
		<div class="row">
			<div class="aps-col col-xs-12">
				<span class="socket-left">
				<?php aps_get_option_data('socket:left'); ?>
				</span>
				<span class="socket-right">
				<?php aps_get_option_data( 'socket:right' ); ?>
				</span>
			</div>
		</div>
	</div>
</div>