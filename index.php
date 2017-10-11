<?php require_once('header.php'); ?>

	<div class="header">
		<div class="header-overlay"></div>
		<div class="header-content">
			<div>
				<h4>Moving to a new house?</h4>
				<h1>Book our service now.</h1>
				<div class="forms">
					<div class="input-block">
						<label for="date">Date</label>
						<select class="form-control" id="date">
							<?php
							for ( $i=1; $i<=30; $i++ ){
								printf( '<option>%d November 2017</option>', $i );
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="from">From</label>
						<select class="form-control" id="from">
							<?php
							foreach ( $states = ['Perlis', 'Kedah', 'Penang', 'Kuala Lumpur'] as $state ){
								printf( '<option value="%1$s">%2$s</option>', strtolower($state), ucfirst($state) );
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="to">To</label>
						<select class="form-control" id="to">
							<?php
							foreach ( $states as $state ){
								printf( '<option value="%1$s">%2$s</option>', strtolower($state), ucfirst($state) );
							}
							?>
						</select>
					</div>

					<div class="input-block">
						<label for="vehicle">Vehicle Type</label>
						<select class="form-control" id="vehicle">
							<?php
							foreach ( ['Pickup Truck', '4x4'] as $truck ){
								printf( '<option value="%1$s">%2$s</option>', strtolower($truck), ucfirst($truck) );
							}
							?>
						</select>
					</div>

					<div class="clearfix"></div>

					<div class="form-group" style="display:block; text-align:center; padding:10px 0 !important">
						<button class="btn btn-success btn-lg">Book Now</button>
					</div>
				</div>
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