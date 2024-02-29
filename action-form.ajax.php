<?php
include_once('config.php');

// Check if the action is to update sorted rows
if (isset($_REQUEST['action']) and $_REQUEST['action'] == "updateSortedRows") {
	// Extract the sorted order from the request
	$newOrder = explode(",", $_REQUEST['sortOrder']);
	$n = '0';
	// Update the user order in the database based on the sorted order
	foreach ($newOrder as $id) {
		$db->query('UPDATE reorderusers SET userorder="' . $n . '" WHERE id="' . $id . '" ');
		$n++;
	}
	// Send a success response back to the client
	echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record updated successfully!</div>|***|update';
}