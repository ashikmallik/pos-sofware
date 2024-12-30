<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="asset/css/reset.css" rel="stylesheet" type="text/css">
<link href="asset/css/style.css" rel="stylesheet" type="text/css">
<link href="asset/css/2col.css" rel="stylesheet" type="text/css">
<link href="asset/css/1col.css" rel="stylesheet" type="text/css">
<link href="asset/css/main.css" rel="stylesheet" type="text/css">
<link href="asset/css/menu.css" rel="stylesheet" type="text/css">
<link href="asset/css/admin.css" rel="stylesheet" type="text/css">

<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">

<script  src="https://code.jquery.com/jquery-3.3.1.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- Start DataTable ======================================== -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css"/> -->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js "></script>
<!-- End DataTable ======================================== -->
<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-datepicker.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script>
	$(document).ready(function(){
		$('#datatable').DataTable( {
			dom: 'Bfrtip',
			buttons: [
			'print'
			]
		});
	});
	</script>