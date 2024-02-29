<?php include_once('config.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://kit.fontawesome.com/7d83d4c093.js" crossorigin="anonymous"></script>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Hello, world!</title>
</head>

<body>
  <!-- Table to display user data -->
  <table class="table table-bordered table-striped" id="sortable">
    <thead>
      <!-- Table header row -->
      <tr>
        <!-- Column for action (sorting handle) -->
        <th width="20">Action</th>
        <!-- Column for DateTime -->
        <th>DateTime</th>
        <!-- Column for User Name -->
        <th>User Name</th>
        <!-- Column for User Email -->
        <th>User Email</th>
        <!-- Column for User Phone# -->
        <th>User Phone#</th>
      </tr>
    </thead>
    <tbody id="tb">
      <?php
			// Fetch data from the database and display in table rows
			$result = $db->query("SELECT * FROM reorderusers WHERE 1 ORDER BY userorder ASC ");
			if ($result->num_rows > 0) {
				while ($val  =   $result->fetch_assoc()) {
			?>
      <!-- Table row for each user -->
      <tr>
        <!-- Column for action (sorting handle) -->
        <td align="center"><a href="javascript:;" class="sort"><i class="fa fa-fw fa-arrows-alt "></i></a>
        </td>
        <!-- Column for DateTime -->
        <td><?php echo $val['dt']; ?></td>
        <!-- Column for User Name -->
        <td><?php echo mb_strtoupper($val['username'], 'UTF-8'); ?></td>
        <!-- Column for User Email -->
        <td><?php echo $val['useremail']; ?></td>
        <!-- Column for User Phone# -->
        <td><?php echo $val['userphone']; ?></td>
      </tr>
      <?php
				}
			} else {
				?>
      <!-- Table row for displaying no records found message -->
      <tr>
        <!-- Cell to span across all columns -->
        <td colspan="5" class="bg-light text-center"><strong>No Record(s) Found!</strong></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- Container for displaying success message after sorting -->
  <div id="msg"></div>

  <!-- Include jQuery and jQuery UI libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
    integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>

  <!-- JavaScript code for sorting functionality -->
  <script>
  $(document).ready(function(e) {
    // Initialize jQuery UI sortable for table rows
    $('#sortable tbody').sortable({
      handle: 'a.sort', // Define the handle for dragging
      placeholder: "ui-state-highlight", // Define the placeholder style
      // Update function triggered after sorting
      update: function() {
        // Get the sorted order of rows and convert to comma-separated string
        var order = $('#sortable tbody').sortable('toArray', {
          attribute: 'data-sort-id'
        });
        console.log(order.join(','));
        sortOrder = order.join(',');
        // Send AJAX POST request to update sorted rows
        $.post(
          'action-form.ajax.php', {
            'action': 'updateSortedRows',
            'sortOrder': sortOrder
          },
          // Callback function after AJAX request completes
          function(data) {
            var a = data.split('|***|');
            if (a[1] == "update") {
              // Display success message
              $('#msg').html(a[0]);
            }
          }
        );
      }
    });
    // Disable text selection while sorting
    $("#sortable").disableSelection();
  })
  </script>
  <!-- Optional JavaScript -->
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>
</body>

</html>





<!-- Sure, let's break down the logic behind reordering the rows:

Initialization:

When the document is ready ($(document).ready()), the JavaScript code initializes the jQuery UI sortable functionality on the table body (#sortable tbody).
It specifies that the handle for dragging and sorting the rows is the anchor tag with the class sort.
It defines the placeholder style when dragging a row (ui-state-highlight).
Text selection is disabled while sorting to prevent accidental highlighting of text within cells.
Sorting Event:

The update event is triggered when the user finishes sorting the rows.
Inside the event handler, the code retrieves the sorted order of rows using $('#sortable tbody').sortable('toArray', { attribute: 'data-sort-id' }). This method returns an array of IDs of the rows in the new order.
The sorted order is then converted into a comma-separated string (sortOrder = order.join(',')).
AJAX Request:

An AJAX POST request is sent to action-form.ajax.php with the action updateSortedRows and the sorted order (sortOrder) as data.
This request updates the database with the new order of rows.
Upon successful update, the server responds with a success message (<div class="alert alert-success">) along with the text |***|update.
Response Handling:

In the AJAX callback function, the response from the server is split using data.split('|***|') to separate the message from the status indicator.
If the status indicator is update, indicating a successful update, the success message is displayed in the msg div ($('#msg').html(a[0])).
Disable Selection:

Finally, the code disables text selection within the sortable table ($("#sortable").disableSelection()). This prevents accidental text selection while dragging rows.
In summary, the logic involves using jQuery UI sortable to allow the user to drag and reorder table rows. When the sorting is finished, the new order is sent to the server via AJAX, where it is processed and updated in the database. Upon successful update, a success message is displayed to the user. -->