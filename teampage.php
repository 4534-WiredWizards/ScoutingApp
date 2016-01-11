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
	<?php include 'navbar.php'; ?>
	<div class="container text-center">
		<div class="row">
			<div class="col-sm-3 well">
				<div class="well">
					<p>
						<a href="#">Team Picture</a>
					</p>
					<img src="bird.jpg" class="img-circle" height="65" width="65"
						alt="Avatar">
				</div>
				<div class="well">
					<p>
						<a href="#">Keywords</a>
					</p>
					<p>
						<span class="label label-default">Best</span> <span
							class="label label-primary">Fantastic</span> <span
							class="label label-success">Gonna Win</span> <span
							class="label label-info">Graciously</span> <span
							class="label label-warning">Professional</span> <span
							class="label label-danger">Friends</span>
					</p>
				</div>
			</div>
			<div class="col-sm-7">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default text-left">
							<div class="panel-body">
								<!-- This is where the main page is. Keywords: main page -->
								<h2>Team: <?php echo "4534".', '."The Wired Wizards";?></h2>
								<p>
									<b>Summary:</b> <?php echo"This is a great summary about this team, isn't it?"?>
								</p>
								<div class="row">
								<div class="col-sm-5">
									<p style="margin-bottom:0;"><b>Strengths:</b></p>
									<p style="margin-left: 1em;">
									<?php echo "<b>&middot;</b> Best Team Ever!<br>"; ?>
									</p>
								</div>
								<div class="col-sm-5">
									<p style="margin-bottom:0;"><b>Weaknesses:</b><br></p>
									<p style="margin-left: 1em;">
									<?php echo "<b>&middot;</b> No weaknesses.<br>"?>
									</p>
								</div>
								</div>
								<p style="margin-bottom: 0;"><b>Should we consider them?</b></p>
								<div class="progress">
									<?php $value=100;?>
									<div class="progress-bar progress-bar-<?php echo "success"; ?>" role="progressbar" aria-valuenow="<?php echo $value; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value; ?>%">
										<?php echo $value; ?>%
									</div>
								</div>
								<p style="margin-bottom: 0;"><b>Photos</b></p>
								<div id="myCarousel" class="carousel slide" data-ride="carousel">
								  <!-- Indicators -->
								  <ol class="carousel-indicators">
								    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								    <li data-target="#myCarousel" data-slide-to="1"></li>
								    <li data-target="#myCarousel" data-slide-to="2"></li>
								    <li data-target="#myCarousel" data-slide-to="3"></li>
								  </ol>
								
								  <!-- Wrapper for slides -->
								  <div class="carousel-inner" role="listbox">
								    <div class="item active">
								      <img src="img_chania.jpg" alt="Chania">
								    </div>
								
								    <div class="item">
								      <img src="img_chania2.jpg" alt="Chania">
								    </div>
								
								    <div class="item">
								      <img src="img_flower.jpg" alt="Flower">
								    </div>
								
								    <div class="item">
								      <img src="img_flower2.jpg" alt="Flower">
								    </div>
								  </div>
								
								  <!-- Left and right controls -->
								  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
								    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
								    <span class="sr-only">Previous</span>
								  </a>
								  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
								    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
								    <span class="sr-only">Next</span>
								  </a>
								</div>
							</div>
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