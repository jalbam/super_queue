<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	$userName = "";
	$userPassword = "";
	$userGender = getGet("gender");

	//Tries to identify the user by cookies:
	$userKeyword = getCookie("user_keyword");
	if (!USE_COOKIES || $userKeyword === "") { $userKeyword = getGet("user_keyword"); }

	//If the identification was not successfull, tries to identify the user by keyword:
	if ($userKeyword !== "")
	{
		//Tries to get an user id using this keyword:
		$userId = getUserIdFromKeyword($userKeyword);
		
		//If there is someone who uses this keyword:
		if (is_numeric($userId) && $userId > 0)
		{
			//Gets the user name and password (these variables will be set in JavaScript):
			$userName = trim(getUserName($userId));
			$userPassword = trim(getUserPassword($userId));
			$userGender = trim(getUserGender($userId));
		}
	}