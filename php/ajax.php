<?php
	if (file_exists("../functions.php")) { require_once "../functions.php"; }
	else { require_once "functions.php"; }

	function getSslPage($url, $method = "POST", $fields)
	{
		$method = strtoupper($method);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	    if ($method === "POST")
	    {
	    	curl_setopt($ch, CURLOPT_POST, true);
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    }

	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}

	$url = getGet("url");
	if ($url === "") { echo "URL not sent!"; exit(); }
	$url = urldecode($url);

	$method = strtoupper(getGet("method"));
	if ($method !== "GET" && $method !== "POST") { $method = "POST"; }

	$data = ($method === "GET") ? $_GET : $_POST;

	$fields = "";
	if ($method == "post")
	{
		foreach ($data as $key => $value)
		{
			$fields .= $key . "=" . $value . "&";
		}
	}

	echo getSslPage($url, $method, $fields);