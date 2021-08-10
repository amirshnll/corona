<?php
	date_default_timezone_set('Asia/Tehran');
	$json_data = json_decode(file_get_contents("data.json"), true);

	$today = date('n/j/Y', strtotime("today"));

	if($json_data[count($json_data)-1][1] == $today)
		die();

	$api_data = file_get_contents("https://disease.sh/v3/covid-19/countries/iran");
	$api_data = json_decode($api_data, true);

	if(is_array($api_data) && isset($api_data['updated']) && isset($api_data['deaths']) && date("n/j/Y", round($api_data['updated'] / 1000)) == $today) {
		$total = 0;
		foreach ($json_data as $key => $value)
			$total += intval($value[0]);

		$today_deaths = $api_data['deaths'] - $total;

		if($today_deaths < 0)
			die();

		array_push($json_data, array(
			'0' => strval($today_deaths),
			'1' => $today
		));

		file_put_contents("data.json", json_encode($json_data,JSON_UNESCAPED_SLASHES));
	}

?>