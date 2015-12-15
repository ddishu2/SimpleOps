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
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

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
			.hide_div
			{
			display: none
			}
			#tne_table_to_display
			{
			margin-top : 50px !important
			}
			#button_to_select_tne
			{
			
			margin-top : 20px !important
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
			var hard_sdate="";
			var hard_edate ="";
			var teid_send= "";
			var tename_send= "";
			stat1 += "&status="+$(".selection1").val();
			comm1 += "&comments="+$(".comments1").val();
			smart1 += "&smart_proj_code="+$(".project_code_text1").val();
			fte_pass+= "&FTE="+$(".fte_text1").val();
			tag_type1+= "&tag_type="+$(".tag_type_value").val();
			hard_sdate+="&hlock_sdate="+$("#start_date").val();
			hard_edate+="&hlock_edate="+$("#end_date").val();
			teid_send+="&tne_id="+$(".teidval").val();
			tename_send+="&tne_name="+$(".tenameval").val();
			
			
            var asc1= comm1 + stat1 + transactn+smart1+fte_pass+tag_type1+hard_sdate+hard_edate+teid_send+tename_send;

            var input_manual1 = $(".project_code_text1").val();
			 var input_manual2 = $(".fte_text1").val();
			 
			
			if(!input_manual2.match(regex))
	        	 {
	        	 alert("Entered FTE% is not valid");
	        	 }
	         else if($(".comments1").val()=="")
	         {
	        	 alert("Please Enter the Comments");
	         }
	         else if($("#start_date").val()=="")
	         {
	        	 alert("Please Enter the Assignment Start Date");
	         }
	         else if($("#end_date").val() == "")
	         {
	        	 alert("Please Enter the Assignment End Date");
	         }
	         else if($(".teidval").val()==""  &&  $(".selection1").val()=="Approve")
	         {
	        	 alert("Please Enter the T & E Approver ID");
	         }
	         else if($(".tenameval").val()=="" &&  $(".selection1").val()=="Approve")
	         {
	        	 alert("Please Enter the T & E Approver Name");
	         }
	        
	         else{
			console.log("/rmt/api/ci/index.php/approve_hard_lock/?"+asc1);
			$.ajax({
    		  method: "GET",
    		  url: "/rmt/api/ci/index.php/approve_hard_lock/?"+asc1,
    		
    		success : function(odata){
        		console.log(odata)

    		      alert(odata);
    		      window.open('','_parent',''); 
    		      window.close(); 
    		}
		});
	         }

		
	}	

	function search_tne(){
		if($(".teidval").val()==""  )
		{
			alert("Please Enter T & E Approver ID")

		}
           
	else{
		$(".tenameval").val("");
        $("#tne_table_to_display").removeClass("hide_div")
        $("#button_to_select_tne").removeClass("hide_div")  ;//button_to_select_tne
         var tne_emp_id_to_send =$(".teidval").val();
     	$.ajax({
       		  method: "GET",
       		  url: "/rmt/api/ci/index.php/get_ValidTNEs/?emp_id="+tne_emp_id_to_send+"&domain_id=",
       		  success : function(odata){
      			
      			  var res_tne= odata;
//       			  var table_row;
      			for (var i = 0; i < res_tne.length; i++) {
$("#tne_table_to_display").append("<tr><td>" + res_tne[i].emp_id + "</td><td class='te_name_to_send'>" + res_tne[i].emp_name + "</td></tr>");
}
			 
       		}
	  
      	
       	}); 
	}
}
	function tename_populate()
	{
		$("#button_to_select_tne").addClass("hide_div")
		 $("#tne_table_to_display").addClass("hide_div")  ;
		 var name_assign =$(".te_name_to_send").text();
		 $(".tenameval").val(name_assign);
		 $("#tne_table_to_display tr").remove();
         
	}
	
	</script>

	


	</script>
	
	</head>

	<body>
		<div>
			<header>
				<h1>Approve Reject Employee</h1>
			</header>
			
			<div>
				<h3>Employee Details</h3>
				<input type="hidden" id="transaction_id" class="get_trans_id" value="<?php echo htmlspecialchars($_GET["trans_id"]);?>"/>
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
						<th>T & E Approver ID</th>
						<th></th>
						<th>T & E Approver Name </th>
						
						
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
						<td><input type="text" class="teidval" ></td>
						<td><input type="button" class="search_button" value="SEARCH" onclick="search_tne()"/>
						<td><input type="text" class="tenameval"  readonly="readonly"  ></td>
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

						<td><input type="text" id="start_date"  value="<?php echo htmlspecialchars($_GET["sdate"]);?>"/></td>
                		<td><input type="text" id="end_date" value="<?php echo htmlspecialchars($_GET["edate"]);?>"/></td>
						<td><input type="text" class="project_code_text1" ></td>
						<td><input type="text"  value="100" class="fte_text1" ></td>
						<td><select class="tag_type_value">
  <option value="Effort Booking">Effort Booking</option>
  <option value="Expense">Expense</option>
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
				<table id="tne_table_to_display" class="hide_div"  border="1" >
						<caption>T & E Approver List
						</caption>
						<colgroup width="10%" />
						<colgroup id="colgroup" class="colgroup" align="center" valign="middle" title="title" width="1*" span="2"  />
						<thead>
							<tr>
								<th scope="col">Approver ID</th>
								<th scope="col">Approver Name</th>
								<th>
								
							</tr>
						</thead>
					
						</tbody>
					</table>
			<input type="button" value="Select" id ="button_to_select_tne" class= "hide_div"  onclick="tename_populate()"  />
			
			
			
				
			</div>
			<div>
				
			</div>

		</div>
		
	</body>
	
	
</html>
