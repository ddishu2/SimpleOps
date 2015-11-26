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
		<script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript" ></script>

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
			.comments1
			{
			width: 435px !important;
            height: 80px;
			}
			
			
		</style>
		
		
		
		<script type="text/javascript">

	function submit_form(){
            var regex = /^[0-9]+$/
			var stat1="" ;
			var comm1="" ;
			var smart1 = "";
			var fte_pass="";
			var tag_type1="";
			var transactn = "&trans_id="+$("#transaction_id").val();
			stat1 += "&status="+$(".selection1").val();
			comm1 += "&comments="+$(".comments1").val();
			smart1 += "&smart_proj_code="+$(".project_code_text1").val();
			fte_pass+= "&FTE="+$(".fte_text1").val();
			tag_type1+= "&tag_type="+$(".tag_type_value").val();
			
			
            var asc1= comm1 + stat1 + transactn+smart1+fte_pass+tag_type1;

            var input_manual1 = $(".project_code_text1").val();
			 var input_manual2 = $(".fte_text1").val();
			 
			 
	         if (!input_manual1.match(regex)) {
	        alert("Entered Smart Project Code is not valid");
	      } 
	         else if(!input_manual2.match(regex))
	        	 {
	        	 alert("Entered FTE% is not valid");
	        	 }
	         else if($(".comments1").val()=="")
	         {
	        	 alert("Please Enter the Comments");
	         }
	         else{
			
			$.ajax({
    		  method: "GET",
    		  url: "/rmt/api/ci/index.php/approve_hard_lock/?"+asc1,
    		///rmt/api/v1/index.php/deployable_emp/?so_from_date="
    		///rmt/api/ci/index.php/approve_hard_lock/?trans_id=2&comments=Abc&status=Approve&smart_proj_code=A&FTE=B&tag_type=hard
    		success : function(odata){

    		      alert("Submitted");
    		     /*  window.open('','_parent',''); 
    		      window.close(); */
    		}
		});
	         }

		
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
						<th>Smart Project Code</th>
						<th>FTE%</th>
						<th>Tagging Type</th>
						
						
					</tr>
					<tr>
						<td><?php echo htmlspecialchars($_GET["proj_code"]);?></td>
						<td><?php echo htmlspecialchars($_GET["proj_name"]);?></td>
						<td><?php echo htmlspecialchars($_GET["so_no"]);?></td>
						<td><?php echo htmlspecialchars($_GET["sdate"]);?></td>
						<td><?php echo htmlspecialchars($_GET["edate"]);?></td>
						<td><input type="text" class="project_code_text1" ></td>
						<td><input type="text"  class="fte_text1" ></td>
						<td><select class="tag_type_value">
  <option value="Expense">Expense</option>
  <option value="Effort Booking">Effort Booking</option>
</select></td>
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
				<textarea class="comments1"></textarea></td>
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
