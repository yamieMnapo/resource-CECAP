<?php 
session_start();
require 'db.php';
//check device type
if (!isset($_SESSION['data'])) {
	header("location: logout.php");
}
file_put_contents('user_agent', $_SERVER['HTTP_USER_AGENT']);

if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "android") != false) {
	header("location: mobile.php");
}

$type = $_SESSION['data']['type'];
$type_name = $db->query("SELECT * FROM job_types WHERE id = '$type' ")->fetch_assoc()['name'];
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HR | <?=$_SESSION['data']['fullname'];?></title>
	<?php require 'links.php'; ?>
	<style type="text/css">
		.alert-s{
			color: #41464b;
			  background-color: #e2e3e5;
			  border-color: #d3d6d8;
		}
	</style>
</head>
<body>
<div id="body" class="w3-row">
	<div class="w3-col m2 w3-light-grey" id="leftNav" style="height:100%; overflow-y: auto;">
		<center>
			<img src="images/c.png" width="40%">
			<br>
			<font><?=$_SESSION['data']['fullname'];?></font><br>
			[<font><?=$type_name;?></font>]
		</center>
	</div>
	<div class="w3-col m10" id="rightNav" style="height:100%; overflow-y: auto;">
		<div class="w3-padding w3-border-bottom clearfix">
			<font class="tlbtn">Admin Profile - <?php echo $_SESSION['data']['name'];?></font>

			<font class="float-right text-danger pointer tlbtn" onclick="window.location = 'logout.php'"><i class="fa fa-power-off text-danger pointer"></i> Logout</font>
			<font class="float-right pointer mr-10 tlbtn"><i class="fa fa-user pointer"></i> Profile</font>
			<font class="float-right pointer mr-10 tlbtn" onclick="toggleNotifications(this);"><i class="fa fa-bell pointer"></i> Notifications</font>
		</div>
		<div id="container">
			<font>DASHBOARD</font>
		</div>
	</div>
</div>
</body>
<script type="text/babel">
	<?php require 'manager.js'?>
</script>
<?php include 'actions.php';?>
<script type="text/javascript" src="date2.js"></script>
<script type="text/javascript">
	<?php require 'server_table.js'; ?>
	<?php require 'mat.js'; ?>

	var config = JSON.parse('<?php echo json_encode($config);?>');

	$(document).ready(function(event) {
		$('#leftNav').css('height', window.innerHeight+"px");
		$('#rightNav').css('height', window.innerHeight+"px");

		$.get("handler.php?getHomeMenu", function(response, status){
			//alert(response);
			try{
				var res = JSON.parse(response);
				var div = new Div('leftNav');

				for (var row of res) {
					if (row.type != undefined) {
						//print dropdown
						var cont = new Div();
						cont.addClasses(['w3-padding', 'menu-toggle1', 'w3-hover-grey']);
						var icon = new Icon();
						icon.addClasses([row.icon, 'mr-15']);
						cont.addView(icon);

						cont.view.innerHTML += row.title;
						cont.setAttribute('id', row.id);
						div.addView(cont);
						var id = Math.floor(Math.random()*100000);
						cont.setAttribute('data', id);

						var menuCont = new Div();
						menuCont.setAttribute('id', id);
						menuCont.css('display', 'none');
						cont.addView(menuCont);

						for (var mn of row.menus) {
							var btn = new Div();
							btn.addClasses(['alert-s', 'w3-padding', 'pointer', 'menuT']);

							var icon = new Icon();
							icon.addClasses([mn.icon, 'mr-15']);
							btn.addView(icon);

							btn.view.innerHTML += mn.title;
							btn.setAttribute('id', mn.id);
							menuCont.addView(btn);
						}
					}
					else{
						var btn = new Div();
						btn.addClasses(['w3-hover-grey', 'w3-padding', 'pointer', 'menuT']);
						var icon = new Icon();
						icon.addClasses([row.icon, 'mr-15']);
						btn.addView(icon);

						btn.view.innerHTML += row.title;
						btn.setAttribute('id', row.id);
						div.addView(btn);
					}
				}
			}
			catch(E){
				alert(E.toString()+response);
			}
		})
	});

	$(document).on('click', '.menu-toggle1', function(event) {
		var id = $(this).attr('data');
		$('#'+id).slideToggle();
		$(this).toggleClass('alert-s');
	});

	$(document).on('click', '.menuT', function(event) {
		event.stopPropagation();
		$('.menuT').removeClass('bg-secondary');
		$(this).addClass('bg-secondary');

		menuClicked($(this).attr('id'));
	});

	function menuClicked(id) {
		switch(id){
			case "logout":
				logout();
				break;

			case "1":
				var div = new Div('container');
				div.removeAllViews();
				printUsers(div,1);
				break;

			case "2":
				var div = new Div('container');
				div.removeAllViews();
				printUsers(div,2);
				break;

			case "3":
				var div = new Div('container');
				div.removeAllViews();
				manage_providers(div);
				break;

			case "5":
				var div = new Div('container');
				div.removeAllViews();
				providers_report(div);
				break;

			case "9":
				var div = new Div('container');
				div.removeAllViews();
				manage_facilities(div);
				break;

			case "6":
				var div = new Div('container');
				div.removeAllViews();
				facilities_report(div);
				break;

			case "8":
				var div = new Div('container');
				div.removeAllViews();
				job_types(div);
				break;

			case "profile":
				var div = new Div('container');
				div.removeAllViews();
				manage_profile(div);
				break;

			default:
				Toast(id);
				break;
		}
	}

	function logout() {
		Swal.fire({
            title:'Logout?',
            text: 'Are you sure? ',
            icon: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
            	window.location = 'logout.php';
            }
        });
	}


	function job_types(div) {
		var button_cont = new Div();
		button_cont.addClasses(['w3-padding']);
		div.addView(button_cont);

		var div1 = new Div();
		div1.setAttribute('id', 'div123');
		div1.setPadding(10, 10, 10, 10);
		div.addView(div1);

		var btn = new MaterialButton();
		btn.setText("Add Type");
		btn.setVariant(Variant.OUTLINED);
		btn.addClasses(['dark']);
		button_cont.addView(btn);

		btn.onClick(function(event) {
			var modal = new BootstrapModal();
			modal.setTitle("Add User Type");
			modal.show();

			var content = new Div();
			modal.addView(content);

			$(content.view).load("views/add_job_type.php", function() {
				var form = document.getElementById('add_job_type');

				form.addEventListener('submit', function(event) {
					event.preventDefault();

					var formdata = $(form).serialize();

					$.post("handler.php", formdata, function(response, status) {
						try{
							var obj = JSON.parse(response);
							if (obj.status) {
								modal.close();
								div.removeAllViews();
								job_types(div);
							}
							else{
								Toast(obj.message);
							}
						}
						catch(E){
							alert(E.toString()+response);
						}
					})
				})
			})
		})

		$(div1.view).load('menu/job_types.json', function() {
			serverTable('div123');
		})
	}

	$(document).on('submit', '#return2', function(event) {
		event.preventDefault();
	})
</script>
</html>