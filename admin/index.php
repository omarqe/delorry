<?php require_once('header.php') ?>

<!-- <ol class="breadcrumb">
	<li class="breadcrumb-item">Bookings</li>
</ol> -->
<h4>Bookings</h4>
<?php
if ( isset($_GET['msg']) && $msg = $_GET['msg'] ){
	$messages = [
		'success' => '<i class="fa fa-info" style="margin-right:20px"></i> Successfully done that.',
		'error' => '<i class="fa fa-exclamation-triangle" style="margin-right:20px"></i> Sorry, we cannot do that at the moment.'
	];

	if ( array_key_exists($msg, $messages) )
		echo sprintf( '<div class="alert alert-%2$s">%1$s</div>', $messages[$msg], ($msg == 'success' ? 'success':'danger') );
}
?>
<div class="card mb-3">
	<div class="card-header">
		<i class="fa fa-table"></i> Bookings
	</div>

	<div class="card-body">
		<?php
		$columns = ['b.book_id', 'b.book_user_id', 'b.book_date', 'b.book_from', 'b.book_to', 'b.book_status', 'b.book_price', 'u.username', 'u.phone', 'u.email'];
		$columns = implode( ', ', $columns );

		$query  = "SELECT $columns FROM booking b ";
		$query .= "LEFT JOIN user u ON b.book_user_id = u.user_id ";
		$db->query( $query );

		$have_booking = $db->num_rows() > 0;
		
		if (!$have_booking): ?>
		<div class="alert alert-info" style="margin:0"><i class="fa fa-exclamation-triangle"></i> There are no bookings made by anyone yet. <a href="../">Book now.</a></div>
		<?php else: ?>
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<th>Contact</th>
						<th>Date</th>
						<th>From</th>
						<th>To</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Name</th>
						<th>Contact</th>
						<th>Date</th>
						<th>From</th>
						<th>To</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					while($row=$db->fetch()):
						list( $username, $email, $phone, $date, $from, $to, $status, $book_id, $price ) = get_list([
							'username', 'email', 'phone', 'book_date', 'book_from', 'book_to', 'book_status', 'book_id', 'book_price'
						], $row);

						$date = date( 'l, d F Y', date2time($date) );
					?>
					<tr>
						<td><?php echo $username; ?></td>
						<td>
							<?php
							printf( '<i class="fa fa-envelope-o" style="width:20px"></i> %s', $email );
							if( !empty($phone) )
								printf('<br/><i class="fa fa-phone" style="width:20px"></i> %s', $phone);
							?>
						</td>
						<td><?php echo $date; ?></td>
						<td><?php echo get_place_label($from); ?></td>
						<td><?php echo get_place_label($to); ?></td>
						<td><?php printf( 'RM%s', number_format($price,2) ); ?></td>
						<td>
							<?php echo get_status_badge($status); ?>
						</td>
						<td class="text-right">
							<?php echo get_action_button($status, $book_id); ?>
							<a class="btn btn-danger btn-sm confirm" data-msg="Are you sure to delete this booking? This action is permanent!" href="<?php printf('../process.php?action=delete_booking&id=%d', $book_id); ?>"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
	<div class="card-footer small text-muted">Deleted bookings are <b>irreversible</b> and not shown here.</div>
	</div>
</div>

<?php require_once('footer.php'); ?>