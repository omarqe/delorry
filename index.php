<?php
require_once('includes/load.php');
require_once('process.php');
require_once('header.php');
?>

	<div class="header">
		<div class="header-overlay"></div>
		<div class="header-content">
			<div>
				<h4>Moving to a new house?</h4>
				<h1>Book our service now.</h1>
				<div class="forms">
					<?php
					if( isset($_GET['msg']) && $msg_code = $_GET['msg'] ){
						$messages = array(
							'same_place' => '<div class="alert alert-warning">You cannot book our lorry for the same place.</div>',
							'login_error' => '<div class="alert alert-warning">Please login first to book.</div>',
							'success' => '<div class="alert alert-success">Booking success.</div>',
							'error' => '<div class="alert alert-danger">We cannot place your booking right now. Please try again later.</div>'
						);

						if ( array_key_exists($msg_code, $messages) )
							echo $messages[$msg_code];
					}
					?>
					<form method="post">
					<div class="input-block">
						<label for="date">Date</label>
						<select class="form-control" id="date" name="date" required>
							<?php
							$date = time();
							$maximum_days = get_settings( 'max_booking_days' );

							for ( $i=1; $i<=$maximum_days; $i++ ){
								printf( '<option value="%1$s">%2$s</option>', date('d/m/y'), date('j F Y', $date) );
								$date += 86400;
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="from">From</label>
						<select class="form-control" id="from" name="from" required>
							<?php
							foreach ( $states = get_places() as $placeid => $label ){
								printf( '<option value="%1$s">%2$s</option>', $placeid, ucfirst($label) );
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="to">To</label>
						<select class="form-control" id="to" name="to" required>
							<?php
							foreach ( $states as $placeid => $label ){
								printf( '<option value="%1$s">%2$s</option>', $placeid, ucfirst($label) );
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="vehicle">Vehicle Type</label>
						<select class="form-control" id="vehicle" name="vehicle" required>
							<?php
							foreach ( get_settings('vehicles') as $truck ){
								printf( '<option value="%1$s">%2$s</option>', strtolower($truck), ucfirst($truck) );
							}
							?>
						</select>
					</div>

					<div class="clearfix"></div>

					<div class="form-group" style="display:block; text-align:center; padding:10px 0 !important">
						<input type="hidden" name="action" value="book">
						<button class="btn btn-success btn-lg">Book Now</button>
					</div>
					</form>
				</div> <!-- /.forms -->
			</div>
		</div>
	</div>

	<section>
		<h1>About Us</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sollicitudin mattis est, vehicula placerat nisi ullamcorper sit amet. Donec sit amet blandit diam, ac blandit erat. Suspendisse facilisis et nisl ac mattis. Aliquam eget lacus sapien. Aliquam convallis gravida odio euismod faucibus. Nam est risus, ultricies quis auctor et, elementum sed sem. Mauris sit amet dui non lacus vestibulum maximus. In eget metus id felis accumsan vestibulum.</p>
	</section>

	<section style="background:white">
		<h1>Why Choose Us</h1>
		<p>Vestibulum eros lectus, posuere ac ullamcorper quis, accumsan vel sapien. Morbi nec condimentum libero. Quisque egestas risus ut est efficitur imperdiet. Sed nibh nisi, dictum ac imperdiet eget, condimentum ac ligula.</p>

		<div class="row" style="margin:auto; margin-top:50px; width:70%">
			<div class="col-lg-6 info-block">
				<img src="images/truck-side-view.png" width="166" height="105">
				<h4 class="hh">Professional Driver</h4>
				<p class="hhp">Integer in neque tellus. Donec viverra nunc quis nisl imperdiet, at pretium mauris ullamcorper.</p>
			</div>
			<div class="col-lg-6 info-block">
				<img src="images/alarm-button.png" width="116" height="117">
				<h4 class="hh">On Time</h4>
				<p class="hhp">Integer in neque tellus. Donec viverra nunc quis nisl imperdiet, at pretium mauris ullamcorper.</p>
			</div>
			<div class="col-lg-6 info-block">
				<img src="images/award-badge-2.png" width="106" height="131">
				<h4 class="hh">Professional Driver</h4>
				<p class="hhp">Integer in neque tellus. Donec viverra nunc quis nisl imperdiet, at pretium mauris ullamcorper.</p>
			</div>
			<div class="col-lg-6 info-block">
				<img src="images/basic-calculator.png" width="99" height="127">
				<h4 class="hh">Affordable Price</h4>
				<p class="hhp">Integer in neque tellus. Donec viverra nunc quis nisl imperdiet, at pretium mauris ullamcorper.</p>
			</div>
		</div>
	</section>

	<section>
		<h1>Questions?</h1>
		<h4 style="margin:15px 0 0">Call us now at +603-4555-8800</h4>
		<small class="light">*24h customer support. Telco charges may apply.</small>
	</section>

<?php require_once('footer.php'); ?>