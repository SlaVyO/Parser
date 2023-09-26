<?php

		//function countImagesWeight(string $link = 'https://twitter.com/tproger'){
function countImagesWeight(string $link ){

	//$url = 'https://habr.com/ru/post/184302/';
	$ch = curl_init ();

	curl_setopt ($ch , CURLOPT_URL , $link);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
	//curl_setopt ($ch , CURLOPT_USERAGENT , " Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0");
	curl_setopt ($ch , CURLOPT_HEADER , 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch , CURLOPT_RETURNTRANSFER , 1 );


	$content = curl_exec($ch);
	if (!curl_errno($ch)) {
	  if ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
		 	curl_close($ch);
			preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $content, $images, PREG_SET_ORDER);
	 
	 		$imageArray = [];
			foreach ($images as $image) {
				$imageArray[] = $image[1];
			}
			
			$result = array_unique($imageArray, SORT_STRING);
			foreach ($result as $value) {
				
				$ch = curl_init($value);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_TIMEOUT,10);
				curl_setopt($ch , CURLOPT_NOBODY, true);
				$output = curl_exec($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				curl_close($ch);
				$allImgArray [$value] = $httpcode;
			}
			$resultArray = [
		    	'link' => $link,
		    	'images' => [
		        	'count' => count($allImgArray),
		       		'size' => array_sum($allImgArray),
		       		//'list' => []
		       	]
	       ];

			foreach ($allImgArray as $key => $value) {
				$resultArray['images']['list'][] = [
					'link' => $key,
	                'size' => $value,
	                'imagename' => basename($key),
				];
			}

		return $resultArray;	
	  	}
	}

}

if (isset($_POST['dopost'])){
	if ($_POST['link'] != ''){
  		$result = countImagesWeight($_POST['link']);
  		echo "<pre>";
  		print_r($result);
  		echo "</pre>";
	}
} 


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>image parser</title>
</head>
<body>
<form method="POST">
  <p><input type="text" name="link" placeholder="input url link">
  <p><button type="submit" name="dopost">GO</button></p>
 </form>
</body>
</html>




