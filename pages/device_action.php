<?php
	
	ini_set( "display_errors", 0 );
	//	var_dump( $_GET );
	$action = $_GET[ "action" ];
	$status = FALSE;
	$device = NULL;
	$msg    = NULL;
	if ( $action == "edit" ) {
		$device = $Sonoff->getDeviceById( $_GET[ "device_id" ] );
		
		$status = $Sonoff->getAllStatus( $device );
		if ( isset( $status->ERROR ) ) {
			$msg = __( "MSG_DEVICE_NOT_FOUND", "DEVICE_ACTIONS" )."<br/>";
			$msg .= $status->ERROR."<br/>";
		}
	} else if ( $action == "delete" ) {
		$device[ 0 ] = $_GET[ "device_id" ];
		$tempfile    = @tempnam( _TMPDIR_, "tmp" ); // produce a temporary file name, in the current directory
		
		if ( !$input = fopen( $filename, 'r' ) ) {
			die( __( "ERROR_CANNOT_READ_CSV_FILE", "DEVICE_ACTIONS", [ "csvFilePath" => $filename ] ) );
		}
		if ( !$output = fopen( $tempfile, 'w' ) ) {
			die( __( "ERROR_CANNOT_CREATE_TMP_FILE", "DEVICE_ACTIONS", [ "tmpFilePath" => $tempfile ] ) );
		}
		
		$idCounter = 1;
		while ( ( $data = fgetcsv( $input ) ) !== FALSE ) {
			if ( $data[ 0 ] == $device[ 0 ] ) {
				continue;
			}
			$data[ 0 ] = $idCounter;
			$idCounter++;
			fputcsv( $output, $data );
		}
		
		fclose( $input );
		fclose( $output );
		
		unlink( $filename );
		rename( $tempfile, $filename );
		
		$msg    = __( "MSG_DEVICE_DELETE_DONE", "DEVICE_ACTIONS" );
		$action = "done";
	}
	if ( isset( $_POST ) && !empty( $_POST ) ) {
		
		if ( isset( $_POST[ "search" ] ) ) {
			if ( isset( $_POST[ 'device_ip' ] ) ) {
				if ( !isset( $device ) ) {
					$device = new stdClass();
				}
				$device->ip       = $_POST[ 'device_ip' ];
				$device->username = $_POST[ 'device_username' ];
				$device->password = $_POST[ 'device_password' ];
				
				$status = $Sonoff->getAllStatus( $device );
				if ( isset( $status->ERROR ) ) {
					$msg = __( "MSG_DEVICE_NOT_FOUND", "DEVICE_ACTIONS" )."<br/>";
					$msg .= $status->ERROR."<br/>";
				}
			} else {
				$msg = __( "ERROR_PLEASE_ENTER_DEVICE_IP", "DEVICE_ACTIONS" );
			}
		} else if ( !empty( $_POST[ 'device_id' ] ) ) {//update
			$device      = [];
			$device[ 0 ] = $_POST[ "device_id" ];
			$device[ 1 ] = implode( "|", $_POST[ "device_name" ] );
			$device[ 2 ] = $_POST[ "device_ip" ];
			$device[ 3 ] = $_POST[ "device_username" ];
			$device[ 4 ] = $_POST[ "device_password" ];
			$device[ 5 ] = isset( $_POST[ "device_img" ] ) ? $_POST[ "device_img" ] : "bulb_1";
			$device[ 6 ] = $_POST[ "device_position" ];
			
			$tempfile = @tempnam( _TMPDIR_, "tmp" ); // produce a temporary file name, in the current directory
			
			
			if ( !$input = fopen( $filename, 'r' ) ) {
				die( __( "ERROR_CANNOT_READ_CSV_FILE", "DEVICE_ACTIONS", [ "csvFilePath" => $filename ] ) );
			}
			if ( !$output = fopen( $tempfile, 'w' ) ) {
				die( __( "ERROR_CANNOT_CREATE_TMP_FILE", "DEVICE_ACTIONS", [ "tmpFilePath" => $tempfile ] ) );
			}
			
			while ( ( $data = fgetcsv( $input ) ) !== FALSE ) {
				if ( $data[ 0 ] == $device[ 0 ] ) {
					$data = $device;
				}
				fputcsv( $output, $data );
			}
			
			fclose( $input );
			fclose( $output );
			
			unlink( $filename );
			rename( $tempfile, $filename );
			
			$msg    = __( "MSG_DEVICE_EDIT_DONE", "DEVICE_ACTIONS" );
			$action = "done";
			
		} else { //add
			
			$device      = [];
			$fp          = file( $filename );
			$device[ 0 ] = count( $fp ) + 1;
			$device[ 1 ] = implode( "|", isset( $_POST[ "device_name" ] ) ? $_POST[ "device_name" ] : [] );
			$device[ 2 ] = isset( $_POST[ "device_ip" ] ) ? $_POST[ "device_ip" ] : "";
			$device[ 3 ] = isset( $_POST[ "device_username" ] ) ? $_POST[ "device_username" ] : "";
			$device[ 4 ] = isset( $_POST[ "device_password" ] ) ? $_POST[ "device_password" ] : "";
			$device[ 5 ] = isset( $_POST[ "device_img" ] ) ? $_POST[ "device_img" ] : "bulb_1";
			$device[ 6 ] = isset( $_POST[ "device_position" ] ) ? $_POST[ "device_position" ] : "";
			
			
			$handle = fopen( $filename, "a" );
			fputcsv( $handle, $device );
			fclose( $handle );
			
			$msg    = __( "MSG_DEVICE_ADD_DONE", "DEVICE_ACTIONS" );
			$action = "done";
			
		}
	}

?>
<div class='row justify-content-sm-center'>
	<div class='col-12 col-md-8 col-xl-6'>
		<h2 class='text-sm-center mb-5'>
			<?php echo $title; ?>
		</h2>
		<?php if ( isset( $status->ERROR ) && $status->ERROR != "" ): ?>
			<div class="alert alert-danger alert-dismissible fade show mb-5" data-dismiss="alert" role="alert">
				<p><?php echo __( "MSG_DEVICE_NOT_FOUND", "DEVICE_ACTIONS" ); ?></p>
				<p><?php echo $status->ERROR; ?></p>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		
		<?php endif; ?>
		<?php if ( $action == "done" ): ?>
			<div class="alert alert-success fade show mb-5" role="alert">
				<div class="col-12 text-left">
					<?php echo $msg; ?>
				</div>
				<div class="col-12 text-left mt-3">
					<a class="btn btn-secondary  col-12 col-sm-auto" href='<?php echo _BASEURL_; ?>devices'>
						<?php echo __( "BTN_BACK", "DEVICE_ACTIONS" ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( $action == "add" || $action == "edit" ): ?>
			<?php if ( isset( $device->id ) ): ?>
				<h3 class='text-sm-center mb-5'>
					<?php echo __( "DEVICE", "DEVICE_CONFIG" ); ?>:
					<?php echo implode( " | ", $device->names ); ?>
					<small>( ID: <?php echo $device->id; ?> )</small>
				</h3>
			<?php endif; ?>
			
			
			<form class='form'
			      name='save_device'
			      method='post'
			      action='<?php echo _BASEURL_; ?>device_action/<?php echo $action ?><?php echo isset( $device->id )
				      ? "/".$device->id : "" ?>'>
				<input type='hidden' name='device_id' value='<?php echo isset( $device->id ) ? $device->id : ""; ?>'>
				
				
				<div class="form-row">
					<div class="form-group col-12 col-sm-9">
						<label for="device_ip">
							<?php echo __( "DEVICE_IP", "DEVICE_ACTIONS" ); ?>
						</label>
						<input type="text"
						       class="form-control"
						       id="device_ip"
						       name='device_ip'
						       placeholder="<?php echo __( "PLEASE_ENTER" ); ?>"
						       value='<?php echo( isset( $device->id ) && !isset( $_POST[ 'device_ip' ] ) ? $device->ip
							       : ( isset( $_POST[ 'device_ip' ] ) ? $_POST[ 'device_ip' ] : "" ) ); ?>'
						       required>
						<small id="device_ipHelp" class="form-text text-muted">
							<?php echo __( "DEVICE_IP_HELP", "DEVICE_ACTIONS" ); ?>
						</small>
					</div>
					<div class="form-group col-12 col-sm-3">
						<label class="d-none d-sm-block">&nbsp;</label>
						<button type='submit'
						        name='search'
						        value='search'
						        class='btn btn-primary col-12 '
						>
							<?php echo __( "BTN_SEARCH_DEVICE", "DEVICE_ACTIONS" ); ?>
						</button>
					</div>
				</div>
				<div class="form-group">
					<label for="device_username">
						<?php echo __( "DEVICE_USERNAME", "DEVICE_ACTIONS" ); ?>
					</label>
					<input type="text"
					       class="form-control"
					       id="device_username"
					       name='device_username'
					       value='<?php echo( isset( $device->id ) && !isset( $_POST[ 'device_username' ] )
						       ? $device->username : ( isset( $_POST[ 'device_username' ] )
							       ? $_POST[ 'device_username' ] : "admin" ) ); ?>'
					>
					<small id="device_usernameHelp" class="form-text text-muted">
						<?php echo __( "DEVICE_USERNAME_HELP", "DEVICE_ACTIONS" ); ?>
					</small>
				</div>
				<div class="form-group">
					<label for="device_password">
						<?php echo __( "DEVICE_PASSWORD", "DEVICE_ACTIONS" ); ?>
					</label>
					<input type="text"
					       class="form-control"
					       id="device_password"
					       name='device_password'
					       value='<?php echo( isset( $device->id ) && !isset( $_POST[ 'device_password' ] )
						       ? $device->password : ( isset( $_POST[ 'device_password' ] )
							       ? $_POST[ 'device_password' ] : "" ) ); ?>'
					>
					<small id="device_passwordHelp" class="form-text text-muted">
						<?php echo __( "DEVICE_PASSWORD_HELP", "DEVICE_ACTIONS" ); ?>
					</small>
				</div>
				
				
				<?php if ( isset( $status ) && !empty( $status ) && !isset( $status->ERROR ) ): ?>
					<?php if ( isset( $status->WARNING ) && !empty( $status->WARNING ) ): ?>
						<div class="alert alert-warning alert-dismissible fade show mb-5" data-dismiss="alert"
						     role="alert">
							<p><?php echo __( "MSG_DEVICE_FOUND", "DEVICE_ACTIONS" ); ?></p>
							<p><?php echo $status->WARNING; ?></p>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<?php else: ?>
						<div class="alert alert-success alert-dismissible fade show my-5" data-dismiss="alert"
						     role="alert">
							<?php echo __( "MSG_DEVICE_FOUND", "DEVICE_ACTIONS" ); ?>
							
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="form-group">
							<label for="device_position">
								<?php echo __( "DEVICE_POSITION", "DEVICE_ACTIONS" ); ?>
							</label>
							<input type="text"
							       class="form-control"
							       id="device_position"
							       name='device_position'
							       value='<?php echo( isset( $device->position )
							                          && !isset( $_POST[ 'device_position' ] )
								       ? $device->position : ( isset( $_POST[ 'device_position' ] )
									       ? $_POST[ 'device_position' ]
									       : "" ) ); ?>'
							>
							<small id="device_positionHelp" class="form-text text-muted">
								<?php echo __( "DEVICE_POSITION_HELP", "DEVICE_ACTIONS" ); ?>
							</small>
						</div>
						<?php if ( isset( $status->StatusSTS->POWER ) ): ?>
							<?php
							$friendlyName = is_array( $status->Status->FriendlyName ) //array since 5.12.0h
								? $status->Status->FriendlyName[ 0 ] : $status->Status->FriendlyName;
							?>
							<div class="form-row">
								<div class="form-group col-12 col-sm-9">
									<label for="device_name">
										<?php echo __( "LABEL_NAME", "DEVICE_ACTIONS" ); ?>
									</label>
									<input type="text"
									       class="form-control"
									       id="device_name"
									       name='device_name[]'
									       placeholder="<?php echo __( "PLEASE_ENTER" ); ?>"
									       value='<?php echo isset( $device->id )
										       ? $device->names[ 0 ]
										       : ( isset( $_POST[ 'device_name' ][ 1 ] ) ? $_POST[ 'device_name' ][ 1 ]
											       : $friendlyName ); ?>'
									       required>
									<small id="device_nameHelp" class="form-text text-muted d-none d-sm-block">
										&nbsp;
									</small>
								</div>
								<div class="form-group col-12 col-sm-3">
									<label class="d-none d-sm-block mb-3">&nbsp;</label>
									( <a href='#'
									     class='default-name'><?php echo $friendlyName; ?></a>
									)
									<small id="default_nameHelp" class="form-text text-muted">
										<?php echo __( "DEVICE_NAME_TOOLTIP", "DEVICE_ACTIONS" ); ?>
									</small>
								
								
								</div>
							</div>
						<?php endif; ?>
						
						
						<?php
						$i            = 1;
						$power        = "POWER".$i;
						$channelFound = FALSE;
						
						while ( isset( $status->StatusSTS->$power ) )  : ?>
							<?php $channelFound = TRUE;
							$friendlyName       = is_array( $status->Status->FriendlyName ) //array since 5.12.0h
								? $status->Status->FriendlyName[ $i - 1 ] : $status->Status->FriendlyName." ".$i
							?>
							<div class="form-row">
								<div class="form-group col-12 col-sm-9">
									<label for="device_name_<?php echo $i; ?>">
										<?php echo __( "LABEL_NAME", "DEVICE_ACTIONS" ); ?><?php echo $i; ?>
									</label>
									<input type="text"
									       class="form-control"
									       id="device_name_<?php echo $i; ?>"
									       name='device_name[<?php echo $i; ?>]'
									       placeholder="<?php echo __( "PLEASE_ENTER" ); ?>"
									       value='<?php echo isset( $device->names[ $i - 1 ] )
									                         && !empty(
									       $device->names[ $i - 1 ]
									       )
										       ? $device->names[ $i - 1 ] : ( isset( $_POST[ 'device_name' ][ $i ] )
											       ? $_POST[ 'device_name' ][ $i ] : $friendlyName ); ?>'
									       required>
									<small id="device_name_<?php echo $i; ?>Help"
									       class="form-text text-muted d-none d-sm-block">
										&nbsp;
									</small>
								</div>
								<div class="form-group col-12 col-sm-3">
									<label class="d-none d-sm-block mb-3">&nbsp;</label>
									(
									<a href='#' title='<?php echo __( "OVERTAKE", "DEVICE_ACTIONS" ); ?>'
									   class='default-name'><?php echo $friendlyName; ?>
									</a>
									)
									<small id="default_nameHelp" class="form-text text-muted">
										<?php echo __( "DEVICE_NAME_TOOLTIP", "DEVICE_ACTIONS" ); ?>
									</small>
								
								
								</div>
							</div>
							
							
							<?php
							
							$i++;
							$power = "POWER".$i;
							?>
						
						<?php endwhile; ?>
						
						<?php if ( !isset( $status->StatusSTS->POWER ) && !$channelFound ) :
							//no channel found?>
							<?php
							$friendlyName = is_array( $status->Status->FriendlyName ) //array since 5.12.0h
								? $status->Status->FriendlyName[ 0 ] : $status->Status->FriendlyName;
							?>
							<div class="form-row">
								<div class="form-group col-12 col-sm-9">
									<label for="device_name">
										<?php echo __( "LABEL_NAME", "DEVICE_ACTIONS" ); ?>
									</label>
									<input type="text"
									       class="form-control"
									       id="device_name"
									       name='device_name[]'
									       placeholder="<?php echo __( "PLEASE_ENTER" ); ?>"
									       value='<?php echo isset( $device->id )
										       ? $device->names[ 0 ]
										       : ( isset( $_POST[ 'device_name' ][ 1 ] ) ? $_POST[ 'device_name' ][ 1 ]
											       : $friendlyName ); ?>'
									       required>
									<small id="device_nameHelp" class="form-text text-muted d-none d-sm-block">
										&nbsp;
									</small>
								</div>
								<div class="form-group col-12 col-sm-3">
									<label class="d-none d-sm-block mb-3">&nbsp;</label>
									( <a href='#'
									     class='default-name'><?php echo $friendlyName; ?></a>
									)
									<small id="default_nameHelp" class="form-text text-muted">
										<?php echo __( "DEVICE_NAME_TOOLTIP", "DEVICE_ACTIONS" ); ?>
									</small>
								
								
								</div>
							</div>
						<?php endif; ?>
					
					<?php endif; ?>
				
				<?php endif; ?>
				<div class="row">
					<div class="col-12 col-sm-6 text-left">
						<a class="btn btn-secondary  col-12 col-sm-auto" href='<?php echo _BASEURL_; ?>devices'>
							<?php echo __( "BTN_BACK", "DEVICE_ACTIONS" ); ?>
						</a>
					</div>
					<div class="col-12 col-sm-6 text-right">
						<button type='submit'
						        name='submit'
						        value='<?php echo isset( $device->id ) ? "edit" : "add"; ?>'
						        class='btn btn-primary col-12 col-sm-auto'
							<?php if ( !isset( $status ) || empty( $status ) || isset( $status->ERROR ) ): ?>
								disabled
							<?php endif; ?>
						>
							<?php echo __( "BTN_SAVE", "DEVICE_ACTIONS" ); ?>
						</button>
					</div>
				</div>
				
				</table>
			</form>
		
		
		<?php endif; ?>
	
	</div>
</div>
<script>
	$( document ).on( "ready", function () {
		$( ".default-name" ).on( "click", function ( e ) {
			e.preventDefault();
			// console.log( $( this ).parent().parent().find( "input" ) );
			$( this ).parent().parent().find( "input" ).val( $( this ).html().trim() );
		} );
	} );
</script>
