<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	//Gets the host user (the one who invited the user) by GET:
	$userHost = getGet("user_host");
	if (!is_numeric($userHost) || $userHost <= 0) { $userHost = ""; }