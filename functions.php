<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	//Function that returns a string in the language desired:
	function localize($index, $language = "", $trim = TRUE)
	{
		global $languageCurrent, $lang;

		if (trim($language) === "") { $language = $languageCurrent; }
		if (trim($language) === "") { $language = LANGUAGE_DEFAULT; }
		if (trim($language) === "") { $language = "en"; }

		$index = strtoupper($index);

		$str = "";

		if (isset($lang[$index][$language])) { $str = $lang[$index][$language]; }
		else if (isset($lang[$index][$languageCurrent])) { $str = $lang[$index][$languageCurrent]; }
		else if (isset($lang[$index][LANGUAGE_DEFAULT])) { $str = $lang[$index][LANGUAGE_DEFAULT]; }
		else if (isset($lang[$index]["en"])) { $str = $lang[$index]["en"]; }

		if ($trim) { $str = trim($str); }

		return $str;
	}


	//Function that uses htmlentities function with the default encoding:
	function myhtmlentities($string)
	{
		global $encodingDefault;
		if (!isset($encodingDefault) || trim($encodingDefault) == "") { $encodingDefault = "UTF-8"; }
		//return htmlentities($string, ENT_QUOTES, $encodingDefault);
		return htmlentities($string, ENT_COMPAT, $encodingDefault);
	}


	//Returns a COOKIE:
	function getCookie($index, $trim = TRUE)
	{
		global $HTTP_COOKIE_VARS;

		if (isset($HTTP_COOKIE_VARS[$index])) { $value = $HTTP_COOKIE_VARS[$index]; }
		else if (isset($_COOKIE[$index])) { $value = $_COOKIE[$index]; }
		else { $value = ""; }
		
        //If it is an array, treats each value one by one:
        if (is_array($value))
        {
            foreach ($value as $index => $valueReal)
            {
                //Trims it:
				if ($trim) { $valueReal = trim($valueReal); }
				$value[$index] = trim($valueReal);
            }
        }
        //...otherwise, it is a normal variable, we trim it if we want to:
        else if ($trim) { $value = trim($value); }
	
        //Returns the value:
        return $value;
	}


	//Returns a variable (GET or POST):
	function getGetOrPost($getOrPost, $index, $trim = TRUE)
	{
        global $HTTP_GET_VARS, $HTTP_POST_VARS;

        //If we wanted to and able, gets the value by GET:
        $getOrPost = strtoupper($getOrPost);
		if ($getOrPost == "GET")
		{
			if (isset($HTTP_GET_VARS[$index])) { $value = $HTTP_GET_VARS[$index]; }
			else if (isset($_GET[$index])) { $value = $_GET[$index]; }
			else { $value = ""; }
		}
		//...otherwise, if we wanted to and able, gets the value by POST:
		else if ($getOrPost == "POST")
		{
			if (isset($HTTP_POST_VARS[$index])) { $value = $HTTP_POST_VARS[$index]; }
			else if (isset($_POST[$index])) { $value = $_POST[$index]; }
			else { $value = ""; }
		}
		else { $value = ""; }
		
        //If it is an array, treats each value one by one:
        if (is_array($value))
        {
            foreach ($value as $index => $valueReal)
            {
                //Decodes and trims it:
				if ($getOrPost == "GET") { $valueReal = urldecode($valueReal); }
				if ($trim) { $valueReal = trim($valueReal); }
				$value[$index] = trim($valueReal);
        
                //If necessary (magic_quotes_gpc is enabled), removes the dashes:
                if (get_magic_quotes_gpc()) { $value[$index] = stripslashes($valueReal); }
            }
        }
        //...otherwise, it is a normal variable:
        else
        {
			//Decodes and trims it:
			if ($getOrPost == "GET") { $value = urldecode($value); }
			if ($trim) { $value = trim($value); }

			//If necessary (magic_quotes_gpc is enabled), removes the dashes:
			if (get_magic_quotes_gpc()) { $value = stripslashes($value); }

			//$value = str_replace("%27", "", $value);
			//$value = str_replace("'", "", $value);
				
			//$value = @mysql_real_escape_string($value);
		}
	
        //Returns the value:
        return $value;
	}

	
	//Returns an URL variable (GET):
	function getGet($index, $trim = TRUE)
	{
        //Returns the value:
        return getGetOrPost("GET", $index, $trim);
	}

	
	//Returns an URL variable (POST):
	function getPost($index, $trim = TRUE)
	{
        //Returns the value:
        return getGetOrPost("POST", $index, $trim);
	}


	//Function that gets the data from post and tries to get by get if debug mode is active:
	function getVar($index, $useTwoModes = FALSE, $trim = TRUE)
	{
		$var = getPost($index, $trim);
		if (($useTwoModes || DEBUG_MODE) && $var === "") { $var = getGet($index, $trim); }
		return $var;
	}


	//Function that returns an array of the files with a desired extension from a given extension:
	function getFilesExtensionDirectory($directory, $extensions)
	{
		if (!is_array($extensions)) { return Array(); }

		foreach ($extensions as $key => $extension) { $extensions[$key] = strtolower($extension); }

		$filesFound = Array();

		if (!file_exists($directory)) { return Array(); }

		$files = getDir($directory);

		if (sizeof($files) <= 0) { return Array(); }

		foreach ($files as $file)
		{

			$extension = strtolower(substr(strrchr($file, "."), 1));
			if (in_array($extension, $extensions)) { $filesFound[] = $directory . $file; }
		}

		return $filesFound;
	}

	//Returns an array with the images container in a folder:
	function getImagesDirectory($directory)
	{
		return getFilesExtensionDirectory($directory, Array("gif", "jpg", "jpeg", "png"));
	}


	//Returns an array with the files contained in a directory:
	function getDir($directory)
	{
		$files = Array();
		$directoryPointer = opendir($directory);
		while (false !== ($filename = readdir($directoryPointer)))
		{
    		$files[] = basename($filename);
		}
		return $files;
	}


	//Function that calculates preferred languages:
	//* Source: Noel Whitemore based on Xeoncross code at http://stackoverflow.com/questions/3770513/detect-browser-language-in-php
	function getPreferredLanguage($supported_languages, $default_language_code)
	{
		$supported_languages = array_flip($supported_languages);

		$http_accept_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"]; // es,nl;q=0.8,en-us;q=0.5,en;q=0.3

		preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);

		$available_languages = array();

		foreach ($matches as $match)
		{
		    list($language_code,$language_region) = explode('-', $match[1]) + array('', '');

		    $priority = isset($match[2]) ? (float) $match[2] : 1.0;

		    $available_languages[][$language_code] = $priority;
		}

		$default_priority = (float) 0;

		foreach ($available_languages as $key => $value)
		{
		    $language_code = key($value);
		    $priority = $value[$language_code];

		    if ($priority > $default_priority && array_key_exists($language_code,$supported_languages))
		    {
		        $default_priority = $priority;
		        $default_language_code = $language_code;
		    }
		}

		return $default_language_code;
	}