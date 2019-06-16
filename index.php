<!DOCTYPE html>
<html>
	<head>
		<title>Weather Notifier App</title>
	
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<link href="//fonts.googleapis.com/css?family=Josefin+Sans:400,100,100italic,300,300italic,400italic,600,600italic,700,700italic" rel="stylesheet" type="text/css">

	</head>
	<body>
		<div class="main">
			<h1>Weather Notifier</h1>

			<div class="main-info">
				<button class="active" style="padding:6px 14px;" id="kingstonforecast">Kingston</button><button class="inactive" style="padding:6px 14px;" id="mobayforecast">Montego Bay</button>
				<div class="weather-top" id="today-weather"></div>
				<div class="weather-bottom">	
					<ul id="future-weather"></ul>
					<div class="clear"></div>
				</div>

				<script id="failedToLoadData-template" type="text/x-handlebars-template">
					<p style="color:#fff;text-align:center;margin-top:20px;margin-bottom:-20px">{{msg}}</p>
				</script>

				<script id="todayWeather-template" type="text/x-handlebars-template">
					<div class="weather-grids">
						<h3 class="fri" id="today-day">{{weekday}}</h3>
						<h3 id="weather">{{weatherDesc}}</h3>
					</div>
					<div class="weather-grids weather-mdl">{{{weatherCondition}}}</div>
					<div class="weather-grids">
						<h4>Max {{maxTemp}}°C</h4>
						<h2>{{currentTemp}}°C</h2>
						<h4>Min {{minTemp}}°C</h4>
					</div>
					<div class="clear"></div>
				</script>

				<script id="futureWeather-template" type="text/x-handlebars-template">
					<li class="{{liClass}}">
						<h4>{{weekday}}</h4>
						<figure class="icons">{{{weatherCondition}}}</figure>
						<h5>{{maxTemp}}°C</h5><h6>{{currentTemp}}°C</h6>
					</li>
				</script>

				<script id="clearWeatherCondition-template" type="text/x-handlebars-template">
					<div class="icon sunny"><div class="sun"><div class="rays"></div></div></div>
				</script>

				<script id="cloudyWeatherCondition-template" type="text/x-handlebars-template">
					<div class="icon cloudy"><div class="cloud"></div><div class="cloud"></div></div>
				</script>

				<script id="rainyWeatherCondition-template" type="text/x-handlebars-template">
					<div class="icon rainy"><div style="color:#8c9079;" class="cloud"></div><div class="rain"></div></div>
				</script>

				<script src="js/jquery.min.js" type="text/javascript"></script>
				<script src="js/handlebars-v4.0.5.js" type="text/javascript"></script>
				<script src="js/app.js" type="text/javascript"></script>
			</div>
			<div class="copyright">
				<p>© 2016 Weather Notifier App. All rights reserved</p>
			</div>
		</div>
	</body>
</html>