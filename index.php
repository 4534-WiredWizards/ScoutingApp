<!DOCTYPE html>
<html lang="en">
<head>
<title>Scouting App</title>
<?php include 'imports.html'; ?>
</head>
<body>
	<?php include 'navbar.php'; ?>
	<div class="container text-center">
		<div class="row">
			<div class="col-sm-3 well">
				<div class="well">
					<div style="width: 100%; height: 100%; margin: 0.5em;">
						<a href="newteampage.php">
							<button type="button" class="btn btn-default btn-sm">New Team Page</button>
						</a>
					</div>
				</div>
				<div class="well">
					<p>
						<a href="#">My Profile</a>
					</p>
					<img src="bird.jpg" class="img-circle" height="65" width="65"
						alt="Avatar">
				</div>
				<div class="well">
					<p>
						<a href="#">Interests</a>
					</p>
					<p>
						<span class="label label-default">News</span> <span
							class="label label-primary">W3Schools</span> <span
							class="label label-success">Labels</span> <span
							class="label label-info">Football</span> <span
							class="label label-warning">Gaming</span> <span
							class="label label-danger">Friends</span>
					</p>
				</div>
				<div class="alert alert-success fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					<p>
						<strong>Ey!</strong>
					</p>
					People are looking at your profile. Find out who.
				</div>
				<p>
					<a href="#">Link</a>
				</p>
				<p>
					<a href="#">Link</a>
				</p>
				<p>
					<a href="#">Link</a>
				</p>
			</div>
			<div class="col-sm-7">

				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default text-left">
							<div class="panel-body">
								Update:
								<textarea class="form-control" rows="1" id="update-text"
									style="max-width: 100%;">FRC Team 1234 seems nice!</textarea>
								<div style="text-align: right; margin-top: 0.5em;">
									<button type="button" class="btn btn-default btn-sm">Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						<div class="well">
							<p>John</p>
							<img src="bird.jpg" class="img-circle" height="55" width="55"
								alt="Avatar">
						</div>
					</div>
					<div class="col-sm-9">
						<div class="well">
							<p>Just Forgot that I had to mention something about someone to
								someone about how I forgot something, but now I forgot it. Ahh,
								forget it! Or wait. I remember.... no I don't.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="well">
							<p>John</p>
							<img src="bird.jpg" class="img-circle" height="55" width="55"
								alt="Avatar">
						</div>
					</div>
					<div class="col-sm-9">
						<div class="well">
							<p style="color: green">Updated Weaknesses of team 4534, The Wired Wizards</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="well">
							<p>John</p>
							<img src="bird.jpg" class="img-circle" height="55" width="55"
								alt="Avatar">
						</div>
					</div>
					<div class="col-sm-9">
						<div class="well">
							<p style="color: green">Updated Strengths of team 4534, The Wired Wizards</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="well">
							<p>John</p>
							<img src="bird.jpg" class="img-circle" height="55" width="55"
								alt="Avatar">
						</div>
					</div>
					<div class="col-sm-9">
						<div class="well">
							<p style="color: green">Updated Summary of team 4534, The Wired Wizards</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="well">
							<p>John</p>
							<img src="bird.jpg" class="img-circle" height="55" width="55"
								alt="Avatar">
						</div>
					</div>
					<div class="col-sm-9">
						<div class="well">
							<p>Finally found The Wired Wizards! They seem nice!</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2 well">
				<div class="thumbnail">
					<p>Upcoming Events:</p>
					<img src="paris.jpg" alt="Paris" width="400" height="300">
					<p>
						<strong>Paris</strong>
					</p>
					<p>Fri. 27 November 2015</p>
					<button class="btn btn-primary">Info</button>
				</div>
				<div class="well">
					<p>ADS</p>
				</div>
				<div class="well">
					<p>ADS</p>
				</div>
			</div>
		</div>
	</div>

	<?php include 'footer.html';?>

</body>
</html>