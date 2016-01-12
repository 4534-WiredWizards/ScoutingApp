<!DOCTYPE html>
<html lang="en">
<head>
<title>Scouting App</title>
<?php include 'imports.html';?>
<link rel="stylesheet"
	href="bargraph.css">
<script
	src="lib/teampagelib.js"></script>
<style type="text/css">
	#data-table {
		display: block;
	}
	.bar span {
		background: #fefefe url(lib/info-bg.gif) 0 100% repeat-x;
	}
	.fig0 {
		background: #747474 url(lib/bar-01-bg.gif) 0 0 repeat-y;
	}
	.fig1 {
		background: #65c2e8 url(lib/bar-01-bg.gif) 0 0 repeat-y;
	}
	.fig2 {
		background: #eea151 url(lib/bar-01-bg.gif) 0 0 repeat-y;
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
								<?php 
								// Get information about the team and store them in variables
								$teamnum = 4534;
								$teamname = 'The Wired Wizards';
								$summary = 'This is a great summary about this team, isn\'t it?';
								$strengths = array('Best Team Ever!', 'Other Good Stuff!');
								$weaknesses = array('No weaknesses!', 'Other Stuff');
								$strength = 100;
								// Robot Rank Variables
								$Spy = 4;
								$Deffense = 5;
								$Assist = 3;
								$Shoot = 2;
								?>
								<h2>Team: <?php echo $teamnum.', '.$teamname;?></h2>
								<p>
									<b>Summary:</b> <?php echo $summary; ?>
								</p>
								<div class="row">
								<div class="col-sm-5">
									<p style="margin-bottom:0;"><b>Strengths:</b></p>
									<p style="margin-left: 1em;">
									<?php
									foreach ($strengths as $value) {
										echo "<b>&middot;</b> $value<br>";
									}
									?>
									</p>
								</div>
								<div class="col-sm-5">
									<p style="margin-bottom:0;"><b>Weaknesses:</b><br></p>
									<p style="margin-left: 1em;">
									<?php
									foreach ($weaknesses as $value) {
										echo "<b>&middot;</b> $value<br>";
									}
									?>
									</p>
								</div>
								</div>
								<p style="margin-bottom: 0;"><b>Should we consider them?</b></p>
								<div class="progress">
									<div class="progress-bar progress-bar-<?php echo "success"; ?>" role="progressbar" aria-valuenow="<?php echo $strength; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $strength; ?>%">
										<?php echo $strength; ?>%
									</div>
								</div>
								<div id="wrapper">
									<div class="chart">
							            <table id="data-table" border="1" cellpadding="10" cellspacing="0"
							            summary="The effects of the zombie outbreak on the populations
							            of endangered species from 2012 to 2016">
							               <caption>Robot Ranking</caption>
							               <thead>
							                  <tr>
							                     <td>&nbsp;</td>
							                     <th scope="col">Spy</th>
							                     <th scope="col">Deffense</th>
							                     <th scope="col">Assist</th>
							                     <th scope="col">Shoot</th>
							                  </tr>
							               </thead>
							               <tbody>
							                  <tr>
							                  <th scope="row">null</th>
							                     <td>0</td>
							                     <td>0</td>
							                     <td>0</td>
							                     <td>0</td>
							                  </tr>
							                  <tr>
							                     <th scope="row">Carbon Tiger</th>
							                     <td><?php echo $Spy; ?>000</td>
							                     <td><?php echo $Deffense; ?>000</td>
							                     <td><?php echo $Assist; ?>000</td>
							                     <td><?php echo $Shoot ?>000</td>
							                  </tr>
							               </tbody>
							            </table>
							         </div>
							         <script>buildBarGraph('data-table');</script>
									<p style="margin-bottom: 0;"><b>Photos</b></p>
									<div id="myCarousel" class="carousel slide" data-ride="carousel">
									  <!-- Indicators -->
									  <ol class="carousel-indicators">
									    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
									  </ol>
									
									  <!-- Wrapper for slides -->
									  <div class="carousel-inner" role="listbox">
									    <div class="item active">
									      <img src="thereisnoimage.jpg" alt="Chania">
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