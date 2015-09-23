<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Employee Approval</title>
		<meta name="description" content="">
		<meta name="author" content="abpadalk">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
		<style>
			 .details_table{
				border:1px solid;
			
			}
			.details_table th{
				background:#00f;
				border:1px solid #000;
				padding:2px;
				color:#fff;
			}
			.details_table td{
				border:1px solid;
				padding:2px;
			}
		</style>
	</head>

	<body>
		<div>
			<header>
				<h1>Approve Reject Employee</h1>
			</header>
			
			<div>
				<h3>Employee Details</h3>
				<table cellpadding="0" cellspacing="0" class="details_table">
					<tr>
						<th>BU</th>
						<th>Sub BU</th>
						<th>Service Line</th>
						<th>Location</th>
						<th>Employee Id</th>
						<th>Employee Name</th>
						<th>Primary Skill</th>
						<th>Level</th>
					</tr>
					<tr>
						<td>BU</td>
						<td>Sub BU</td>
						<td>Service Line</td>
						<td>Location</td>
						<td>Employee Id</td>
						<td>Employee Name</td>
						<td>Primary Skill</td>
						<td>Level</td>
					</tr>
				</table>
				<h3>Assignment Details</h3>
				<table cellpadding="0" cellspacing="0" class="details_table">
					<tr>
						<th>Project Code</th>
						<th>Project Name</th>
						<th>SO Number</th>
						<th>Assignment Start Date</th>
						<th>Assignment End Date</th>
					</tr>
					<tr>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
					</tr>
				</table>
			</div>
			<br>
			<div>
				<table>
					<tr>
						<th>Action:</th>
						<td>
							<select>
					<option>Approve</option>
					<option>Reject</option>
				</select>
						</td>
					</tr>
					<tr>
						<th>Comments:</th>
						<td>
				<textarea></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td>
				<input type="button" value="Submit" />
</td>
					</tr>
				</table>
				
			</div>
			<div>
				
			</div>

		</div>
	</body>
</html>
