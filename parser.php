<?php 

$url = "https://www.imdb.com/chart/top";
$url_local = "imdb.html";
$json_file = "result.json";
$xml_file = "result.xml";
$csv_file = "result.csv";

if (is_file($url_local)){
	$url = $url_local;
}
else {
	$html = file_get_contents($url);	
	if ($html != ""){
		if (file_put_contents($url_local, $html)) {
			$url = $url_local;	
		}
	}
}

$html = file_get_contents($url);
$pos_start = 1;
$result_arrey = [];

while ($pos_start = strpos($html, '<td class="titleColumn">', $pos_start)){
	
	//get film name and position
	$pos_span = strpos($html, 'secondaryInfo', $pos_start);
	$pos_end = $pos_span - $pos_start;
	$html_pars = trim(strip_tags(substr($html, $pos_start, $pos_end)));
	
	$film_name = stristr($html_pars, '.');
   	$film_position = trim(stristr($html_pars, '.', true));
	
	if ($film_name[0] == '.') $film_name = trim( substr($film_name, 1));
	
	
	//get date
	$pos_start = $pos_span;
	$pos_end = ( strpos($html, ')</span>', $pos_start)) - $pos_start;
	$html_pars = trim(strip_tags(substr($html, $pos_start, $pos_end)));
	$film_year = stristr($html_pars, '(');
	$film_year = substr($film_year, 1);


	//get rating
	$pos_start = strpos($html, '<td class="ratingColumn imdbRating">', $pos_start);
	$pos_strong = (strpos($html, '</strong>', $pos_start)) - $pos_start;
	$rating = trim(strip_tags(substr($html, $pos_start, $pos_strong)));
	
	$result_arrey [] = array(
	'position' => $film_position,
	'name' => $film_name,
	'rating' => $rating,
	'year' => $film_year,
	 );
	
}

file_put_contents($json_file, json_encode($result_arrey));

$file = fopen($csv_file, 'w');
foreach ($result_arrey as $value) {
    fputcsv($file, $value);
}
fclose($file);


//print_r($result_arrey);

?>

<!DOCTYPE html>
<html>
<head>
	<title>i'm thinking</title>
</head>
<body>
</br>
</br>
</br>
<div style='text-align: center;'>
	<strong style=' width: 100%; display:block;'>Please wait a minute i'm thinking</strong>
	<img src='https://thomifelgen.ru/upload/preloader/preloader.gif' alt='pls wait'>
</div>
</body>
</html>


