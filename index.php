<?php
	ini_set( 'session.gc_maxlifetime', 2678400 );
	session_start();
	if ( !isset ( $_SESSION[ "login" ] ) ) {
		header( "Location: login.php" );
	}
	
	/**
	 * @property Sonoff Sonoff
	 */
	include_once( "includes/Config.php" );
	include_once( "includes/Sonoff.php" );
	
	$Config = new Config();
	
	$page = "start";
	if ( isset( $_GET ) ) {
		if ( isset( $_GET[ "page" ] ) ) {
			$page = $_GET[ "page" ];
			
			
		}
	}
	
	switch ( $page ) {
		case "upload_form":
			$title = "Upload new Firmware";
			break;
		case "device_action":
			$title = "Gerät verwalten";
			break;
		default:
			$title = ucfirst( $page );
	}
	
	
	$filename = "data/devices.csv"; //csv file name
	if ( !file_exists( $filename ) ) {
		fopen( $filename, 'w' ) or die( "Can't create file" );
	}


?>
<?php include_once( "includes/header.php" ); //always load header?>


<div id="navi" class='open desktop'>
	<ul>
		<a href='/index.php?page=start' title='Startseite'>
			<li class=''>Start</li>
		</a>
		<hr/>
		<a href='/index.php?page=devices' title='Geräte'>
			<li class=''>Geräte</li>
		</a>
		<a href='/index.php?page=upload_form' class='hide-mobile' title='Update'>
			<li class=''>Update</li>
		</a>
		
		
		<!--			<hr/>-->
		<!--			<a href='/index.php?page=chat' title='Chat'>-->
		<!--				<li class=''>Chat</li>-->
		<!--			</a>-->
		<hr/>
		<a href='/index.php?page=site_config' title='Web Einstellungen'>
			<li class=''>Einstellungen</li>
		</a>
		
		<a href='/login.php?logout' title='Ausloggen'>
			<li class=''>Logout</li>
		</a>
	
	</ul>
</div>


<div id="content-holder" class=''>
	<div id='content' data-refreshtime='<?php echo $Config->read( "refreshtime" ); ?>'>
		<?php include_once( "pages/".$page.".php" ); ?>
	</div>
</div>
<?php include_once( "includes/footer.php" ); //always load header?>
