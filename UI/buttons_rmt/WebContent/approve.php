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
		
		
		
		<script type="text/javascript">

	function submit_form(){
			var stat1="" ;
			var comm1="" ;
			var transactn = "&trans_id="+$("#transaction_id").val();
			stat1 += "&status="+$(".selection1").val();
			comm1 += "&comments="+$(".comments1").text();

			var asc1= comm1 + stat1 ;
			$.ajax({
    		  method: "GET",
    		  url: "/rmt1/api/v1/index.php/approve_hard_lock/?"+asc1,
    		///rmt/api/v1/index.php/deployable_emp/?so_from_date="
    		success : function(odata){
    			
    			alert("Submitted");
    		}
		});

		//http://localhost/rmt1/api/v1/index.php/approve_hard_lock/?trans_id=1&comments=helloworld&status=Approve
	}	
	</script>
	</head>

	<body>
		<div>
			<header>
				<h1>Approve Reject Employee</h1>
			</header>
			
			<div>
				<h3>Employee Details</h3>
				<input type="hidden" id="transaction_id" value="<?php echo htmlspecialchars($_GET["trans_id"]);?>"/>
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
						<td><?php echo htmlspecialchars($_GET["bu"]);?></td>
						<td><?php echo htmlspecialchars($_GET["subbu"]);?></td>
						<td><?php echo htmlspecialchars($_GET["svcline"]);?></td>
						<td><?php echo htmlspecialchars($_GET["loc"]);?></td>
						<td><?php echo htmlspecialchars($_GET["emp_id"]);?></td>
						<td><?php echo htmlspecialchars($_GET["emp_name"]);?></td>
						<td><?php echo htmlspecialchars($_GET["lv_prime_skill"]);?></td>
						<td><?php echo htmlspecialchars($_GET["lvl"]);?></td>
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
						<td><?php echo htmlspecialchars($_GET["proj_code"]);?></td>
						<td><?php echo htmlspecialchars($_GET["proj_name"]);?></td>
						<td><?php echo htmlspecialchars($_GET["so_no"]);?></td>
						<td><?php echo htmlspecialchars($_GET["sdate"]);?></td>
						<td><?php echo htmlspecialchars($_GET["edate"]);?></td>
					</tr>
				</table>
			</div>
			<br>
			<div>
				<table>
					<tr>
						<th>Action:</th>
						<td>
							<select class="selection1">
					<option>Approve</option>
					<option>Reject</option>
				</select>
						</td>
					</tr>
					<tr>
						<th>Comments:</th>
						<td>
				<textarea class="comments1" required></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td>
				<input type="button" value="Submit"  onclick="submit_form()"  />
</td>
					</tr>
				</table>
				
			</div>
			<div>
				
			</div>

		</div>
	</body>
	
	
</html>
