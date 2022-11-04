<?php 
//check device type
require 'db.php';
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$config['name'];?></title>
	<?php require 'links.php'; ?>
</head>
<body>
	<img src="images/photo.avif" style="width:100%" height="100%" id="img">
<div class="w3-row" style="position:fixed;width:100%;left:0;top:0">
	<div class="w3-col m4">&nbsp;</div>
	<div class="w3-col m3 w3-padding">
		<h1>&nbsp;</h1>
		<div class="w3-white w3-padding-large w3-round-large">
			<h2><?=$config['name'];?></h2>
				<h4>Sign In</h4>
				<form id="loginForm">
					<div class="text-center">
		                <h4>&nbsp;</h4>
		                <div class="input-group input-group-outline my-3">
							<label class="form-label">Email</label>
							<input type="text" name="email" class="form-control" required>
						</div>
						<div class="input-group input-group-outline is-filled my-3">
							<label class="form-label">Password</label>
							<input type="password" name="password" class="form-control" required>
						</div>
						<p>
							<button class="btn2 btn-primary">Login</button>
						</p>
		            </div>
				</form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function(event) {
		$('#img').css('height', window.innerHeight+"px");

		var form = document.getElementById('loginForm');

		/*var email = new OutlinedEditText();
		email.setHint("Email");
		email.addClasses(['mb-15']);
		email.setName("email");
		form.appendChild(email.view);

		var password = new OutlinedEditText();
		password.setHint("Password");
		password.addClasses(['mb-15']);
		password.setName('password');
		password.input.setType(InputType.PASSWORD)
		form.appendChild(password.view);

		var btn = new Button();
		btn.addClasses(['btn btn-sm bg-gradient-primary my-4 mb-2'])
		btn.setText("Login");
		form.appendChild(btn.view); */

		form.addEventListener('submit', function(event) {
			event.preventDefault();

			var formdata = $(form).serialize();

			$.post("handler.php", formdata, function(response, status) {
				try{
					var res = JSON.parse(response);
					if (res.status) {
						window.location = res.link;
					}
					else{
						Toast(res.message);
					}
				}
				catch(E){
					alert(E.toString()+response);
				}
			})
		})
	});
</script>
</html>