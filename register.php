<!DOCTYPE html>
<html lang="en">
<head>
<title>Scouting App</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<style>
/* Set black background color, white text and some padding */
footer {
	background-color: #555;
	color: white;
	padding: 15px;
}
</style>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#myNavbar">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Scout</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li><a href="./">Home</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div style="margin: 4.5em;"></div>

	<div class="container text-center">
		<div class="row">
			<div class="col-sm-7">

				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default text-left">
							<div class="panel-body">
								<form>
									<input name="teamnum" type="number" placeholder="Team Number"
										spellchecking="false" size="4" class="form-control" autofocus><br>
									<input name="username" type="text" placeholder="Username"
										spellchecking="false" class="form-control" autofocus><br>
									<input name="password" type="password" placeholder="Password"
										spellchecking="false" class="form-control"><br>
									<input name="passconf" type="password" placeholder="Confirm Password"
										spellchecking="false" class="form-control"><br>
									<div style="text-align: right; margin-top: 0.5em;">
										<button type="button" class="btn btn-default btn-sm">Sign Up</button>
									</div>
								</form>
							</div>
						</div>
						<div style="text-align: left; margin-bottom: 1.5em;">
							<a href="404.html">
								<button type="button" class="btn btn-default btn-sm">New Team</button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<footer class="container-fluid text-center">
		<p>Footer Text</p>
	</footer>

</body>