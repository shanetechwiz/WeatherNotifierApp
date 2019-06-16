$(function() {


	var getCityID = function(cityName, countryCode, callback) {

		$.getJSON('data/city.list.json').done(function(cities) {

			var resultSet = $.grep(cities, function(e) { return e.country == countryCode; });

			var result = $.grep(resultSet, function(e) { return e.name == cityName; });

			callback(result[0]._id);
		})
		.fail(function() {
			var source = $("#failedToLoadData-template").html();
			var template = Handlebars.compile(source);

			var errMsg = template({ msg: 'Failed to retrieve the selected city ID.' });
			$('#today-weather').empty();
			$(errMsg).appendTo($('#today-weather'));
		});	
	}

	var willRainTomorrow = false;
	var willRainOverNext5Days = false;

	// used to get friendly names of the day from the Date object that gives the array position
	var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	// an object containing the necessary html code that displays the weather icons based on the condition received from the api
	var condition = {
		"Clear": '#clearWeatherCondition-template',
		"Clouds": '#cloudyWeatherCondition-template',
		"Rain": '#rainyWeatherCondition-template'
	}

	var getWeatherData = function(cityID) {
		var endpoint = "http://api.openweathermap.org/data/2.5/forecast?id="+cityID+"&appid=";
		var hourOfForecast = 4;

		// makes api call and gets json response data
		$.getJSON(endpoint).done(function(data) {

			var forecast = [];

			$('#today-weather').empty();

			if(data.list.length > 0) {

				// loops through the weather forecasts that were sent in the response data
				data.list.forEach(function(listItem) {
					// converts the unix time sent into a javascript Date object
					var forecastDate = new Date(listItem.dt*1000);
					var isApplicable = false;

					if(forecastDate.getHours() == hourOfForecast) { isApplicable = true; }

					// gets today's date
					 var today = new Date();
					    
					// checks to see if the today's date matches up with the date of the current forecast
					if(today.getDate() == forecastDate.getDate()) {

					    // checks if the div element is empty
					    if( $('#today-weather').is(':empty') ) {

					    	// generates the html code that is to be added to the DOM

							var source = $("#todayWeather-template").html();
							var template = Handlebars.compile(source);

							var context = {
								weekday: weekday[today.getDay()],
								weatherDesc: listItem.weather[0].description,
								weatherCondition: $(condition[listItem.weather[0].main]).html(),
								maxTemp: (listItem.main.temp_max - 273.15).toFixed(1),
								currentTemp: (listItem.main.temp - 273.15).toFixed(1),
								minTemp: (listItem.main.temp_min - 273.15).toFixed(1)
							};

							var tday = template(context);
							// adds/appends the generated html code to the specified div element
							$(tday).appendTo($('#today-weather'));
					    }
					}
					else {
					    if(isApplicable) {
						    // checks if the forecast array is empty, if it is, then it will add tomorrow's forecast to it
							if(forecast.length == 0) {
							   	if(today < forecastDate) {  forecast.push(listItem); }
							}
							else {
							   	// checks if the current forecast's date is not already included in the forecasts array, this is done to prevent conflicting data
							    if((today.getDate() + forecast.length) < forecastDate.getDate()) { forecast.push(listItem); }
							}

							// checks if it is going to rain tomorrow
							if(forecastDate.getDate() == today.getDate()+1 && listItem.weather[0].main == "Rain") { willRainTomorrow = true; }
								
							// checks if it is going to rain any day over the entire 4-5 day forecast
							if(listItem.weather[0].main == "Rain") { willRainOverNext5Days = true; }
						}
					}

				});

				$('#future-weather').empty();

				if(forecast.length > 0) {
					var counter = 1;

					// loops through the forecast array and generates the html code to be added to the DOM for the future forecasts
					forecast.forEach(function(forecastItem) {
						var forecastItemDate = new Date(forecastItem.dt * 1000);
						
						// adds/appends the generated html code to the specified div element

						var source = $("#futureWeather-template").html();
						var template = Handlebars.compile(source);

						var liClass = '';
						if(counter%2 != 0) { liClass = 'bg'; }

						var context = {
							weekday: weekday[forecastItemDate.getDay()],
							weatherCondition: $(condition[forecastItem.weather[0].main]).html(),
							maxTemp: (forecastItem.main.temp_max - 273.15).toFixed(1),
							currentTemp: (forecastItem.main.temp_min - 273.15).toFixed(1),
							liClass: liClass
						};

						var weather = template(context);

						$(weather).appendTo($('#future-weather'));
						counter++
					});
				}
				else {
					var source = $("#failedToLoadData-template").html();
					var template = Handlebars.compile(source);

					var errMsg = template({ msg: 'No other forecast data was found.' });
					$(errMsg).appendTo($('#future-weather'));
				}

				weatherConditionsCheck(cityID);
			}
			else {
				var source = $("#failedToLoadData-template").html();
				var template = Handlebars.compile(source);

				var errMsg = template({ msg: 'No weather data retrieved.' });
				$('#today-weather').empty();
				$(errMsg).appendTo($('#today-weather'));
			}
		})
		.fail(function() {
			var source = $("#failedToLoadData-template").html();
			var template = Handlebars.compile(source);

			var errMsg = template({ msg: 'Failed to retrieve weather data.' });
			$('#today-weather').empty();
			$(errMsg).appendTo($('#today-weather'));
		});
	}

	getCityID('Kingston', 'JM', function(cityID) { getWeatherData(cityID); });

	// triggers when the button is clicked
	$('#kingstonforecast').on('click', function() {

		if($('#kingstonforecast').hasClass('inactive')) { 
			$('#kingstonforecast').removeClass('inactive');
			$('#kingstonforecast').addClass('active');
			$('#mobayforecast').removeClass('active');
			$('#mobayforecast').addClass('inactive');
		}

		getCityID('Kingston', 'JM', function(cityID) { getWeatherData(cityID); });
	});

	$('#mobayforecast').on('click', function() {

		if($('#mobayforecast').hasClass('inactive')) { 
			$('#mobayforecast').removeClass('inactive');
			$('#mobayforecast').addClass('active');
			$('#kingstonforecast').removeClass('active'); 
			$('#kingstonforecast').addClass('inactive');
		}

		getCityID('Montego Bay', 'JM', function(cityID) { getWeatherData(cityID); });
	});

	var sendEmail = function (type, location) {

		// sends a post request to the php code that send the email
		$.post('send_mail.php', { type: type, location: location })
		.done(function(data) {
			alert('Emails Sent!');
		})
		.fail(function() {
			alert('Failed to send emails. Contact System Administrator.');
		});
	}

	var weatherConditionsCheck = function(location) {

		// checks if it will rain based on the cast and sends the email with a specified 'type'
		if(willRainTomorrow) { sendEmail('rain_tomorrow', location); }
		else if(willRainOverNext5Days) { sendEmail('rain_over_the_next_5_days', location); }
		else { sendEmail('no_rain', location); }
	}


});