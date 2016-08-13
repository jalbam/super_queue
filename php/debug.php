<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	//Exists if we are not in debug mode:
	if (!DEBUG_MODE) { exit(); }


	//Function that inserts a guest for a given user (host):
	function DEBUG_getAGuest($userId)
	{
		$response = "";

		//Create a fake user with the user as the host:
		if (!insertFakeUser("male", 0, $userId)) //Gender and world are not important but user host is.
		{
			$response = "Guest user couldn't be inserted!";
		}

		return $response;
	}


	//Function that deletes a guest for a given user:
	function DEBUG_loseAGuest($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "DELETE FROM invitations WHERE host_user_id = " . $userId . " LIMIT 1;";
		$results = $dataBase->query($query);

		if (!$results) { $response = "Unable to delete a guest for this host!"; }

		return $response;
	}


	//Function that makes a user go to the lottery room:
	function DEBUG_goToLotteryRoom($userId)
	{
		/*
		$response = "";

		//Deletes the user from winners (just in case):
		$response = trim(DEBUG_deleteUserFromWinnersOrLosers($userId));

		//If the user has been deleted from winners (or it was never there):
		if ($response === "")
		{
			//Inserts user as finalist (if the user is already finalist, it will not do anything):
			$response = insertUserFinalist($userId);

			//If the user was inserted as a finalist, provides him/her a partner:
			if ($response === "")
			{
				//giveUserAPartner($userId);
			}
		}

		return $response;
		*/
		return goToLotteryRoom($userId);
	}


	//Function that makes a user go to the queue:
	function DEBUG_goToQueue($userId)
	{
		$response = "";

		//Deletes the user from winners (just in case):
		$response = trim(DEBUG_deleteUserFromWinnersOrLosers($userId));

		//If the user has been deleted from winners (or was never there):
		if ($response === "")
		{
			//Deletes the user from finalists (just in case):
			$response = trim(deleteUserFinalist($userId));

			//If the user has been deleted from finalits (or was never there):
			if ($response === "")
			{
				//Deletes the partner of the user (if any):
				DEBUG_losePartner($userId);

				//Deletes the user from the queue (just in case):
				deleteUserQueue($userId);

				//Inserts the user to the queue:
				insertUserWorld($userId);
			}
		}

		return $response;
	}


	//Function that makes a user win a prize:
	function DEBUG_becomeWinner($userId, $prizeType = NULL)
	{
		$response = "";

		//Makes the user go to the lottery room (in the case is not there):
		DEBUG_goToLotteryRoom($userId);

		//Gives the user a partner:
		DEBUG_getAPartner($userId, true);

		//Gets the id of the partner:
		$partnerId = getUserPartner($userId);
		
		//Gives the same lottery result to both of them:
		setLotteryResult($userId, 1); //Lottery result is not important.
		setLotteryResult($partnerId, 1); //Lottery result is not important.

		//Set them as winners:
		if ($prizeType === NULL) { $prizeType = getAnyPrizeType(true, 2); }
		$response = insertUserWinnerLoser($userId, $prizeType);
		insertUserWinnerLoser($partnerId, $prizeType);

		return $response;
	}


	//Function that makes a user lose the game:
	function DEBUG_becomeLoser($userId)
	{
		$response = "";

		//Makes the user go to the lottery room (in the case is not there):
		DEBUG_goToLotteryRoom($userId);

		//Gives the user a partner:
		DEBUG_getAPartner($userId, true);

		//Gets the id of the partner:
		$partnerId = getUserPartner($userId);
		
		//Gives a different lottery result to each of them:
		setLotteryResult($userId, 1); //Lottery result is not important as long as they are different.
		setLotteryResult($partnerId, 2); //Lottery result is not important as long as they are different.

		//Set them as losers:
		$prizeType = getAnyPrizeType(false, 2);
		$response = insertUserWinnerLoser($userId, $prizeType, false);
		insertUserWinnerLoser($partnerId, $prizeType, false);

		return $response;
	}



	//Function that gives a partner to a user (creates a fake partner if a real one is not found):
	function DEBUG_getAPartner($userId, $partnerWithoutResult = FALSE)
	{
		/*
		$response = "";

		//Makes the user go to the lottery room (just in case the user is not there):
		DEBUG_goToLotteryRoom($userId);

		//If the user has already a partner, exists:
		if (getUserPartner($userId) !== 0) { return "The user $userId has already a partner!"; }

		//Tries to give the user a partner:
		$response = giveUserAPartner($userId, $partnerWithoutResult);

		//If there wasn't a single partner, we create a fake user:
		if ($response !== "")
		{
			$response = "";

			//Creates a fake user:
			$gender = strtolower(getUserGender($userId));
			$genderOpposite = ($gender === "male") ? "female" : "male";
			$fakeUserId = insertFakeUser($genderOpposite, 0, $userId);

			//If the fake user has been created:
			if ($fakeUserId !== 0)
			{
				//Makes the fake user become finalist:
				$response = DEBUG_goToLotteryRoom($fakeUserId);

				//If the fake user has become a finalist:
				if ($response === "")
				{
					//Makes the fake user become the partner of the user:
					$response = insertUserCouple($userId, $fakeUserId);
				}
			} else { return "Fake user couldn't be created!"; }
		}
		
		return $response;
		*/
		return giveUserAPartner($userId, $partnerWithoutResult, true);
	}


	//Function that gives a partner to a user:
	function DEBUG_losePartner($userId)
	{
		$response = "";

		$partnerId = getUserPartner($userId);

		if ($partnerId === 0) { return "The user doesn't have a partner!"; }

		//Deletes the couple:
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }
		$userId = trim(escapeStringMySQL($userId)); //Prevents mySQL injection.
		$query = "DELETE FROM couples WHERE user1_id = " . $userId . " OR user2_id = " . $userId . ";";
		$results = $dataBase->query($query);
		if (!$results) { $response = "Unable to delete the partner for user $userId"; }
		else
		{
			//If the couple has been deleted, deletes the lottery result of the partner:
			setLotteryResult($partnerId, NULL);
		}

		return $response;
	}


	
	//Function that inserts a newcomer to the queue:
	function DEBUG_insertNewUserQueue($userId, $gender)
	{
		$response = "";

		//Gets the world of the user:
		$world = getUserWorld($userId);

		if ($world !== -1)
		{
			//If there are less than the allowed people in the queue, adds one more:
			if (sizeof(getUsersWorld($world, $gender)) < MAXIMUM_PEOPLE_PER_QUEUE)
			{
				//Inserts a fake user on the same world as the user with the desired gender:
				if (!insertFakeUser($gender, $world)) { $response = "Unable to insert a new fake $gender user!"; }
			} else { $response = "Only " . MAXIMUM_PEOPLE_PER_QUEUE . " people per queue are allowed!"; }
		} else { $response = "Unable to find the world for user $userId"; }

		return $response;
	}


	//Function that gives the partner a lottery result:
	function DEBUG_givePartnerLotteryResult($userId)
	{
		$response = "";

		//Gets the user's partner id:
		$partnerId = getUserPartner($userId);

		//If the user doesn't have partner, exits:
		if ($partnerId === 0) { return "The user $userId doesn't have any partner!"; }

		//Makes the partner play the lottery:
		$lotteryResult = trim(playLottery($partnerId));

		$response = (is_numeric($lotteryResult) && $lotteryResult > 0) ? "" : $lotteryResult;

		return $response;
	}

	
	//Function that deletes a given user from the winners:
	function DEBUG_deleteUserFromWinnersOrLosers($userId)
	{
		/*
		//Deletes the user from winners (just in case):
		$response = "";
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }
		$userId = trim(escapeStringMySQL($userId)); //Prevents mySQL injection.
		$query = "DELETE FROM winners_or_losers WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);
		if (!$results) { $response = "Unable to delete the user $userId from winners or losers!"; }
		return $response;
		*/
		return deleteUserFromWinnersOrLosers($userId);
	}


	//Function that gets a list of the existing worlds (will be parsed as JavaScript):
	function DEBUG_getListWorlds() //The string return should have the worlds separated by a comma.
	{
		$worldsList = "";

		$worlds = getWorlds();

		foreach ($worlds as $world)
		{
			//Only if there are more than one user (who is not finalist or winner), lists it:
			if (DEBUG_getOneUserWorld($world) !== "")
			{
				$worldsList .= $world . ", ";
			}
		}

		//Strips the last comma:
		$worldsList = substr($worldsList, 0, strlen($worldsList) - 2);

		return $worldsList;
	}


	//Function that returns any user id that is inside a given world:
	function DEBUG_getOneUserWorld($world)
	{
		//Gets a list of the users in a world:
		$users = getUsersWorld($world);

		//Loops through the users:
		foreach ($users as $userId)
		{
			//If the user is not finalist and is not a winner, returns this user:
			if (!isUserFinalist($userId) && !isUserWinner($userId))
			{
				return $userId;
			}
		}

		return "";
	}


	//Function that gives the selected user a given prize type:
	function DEBUG_winPrize($userId, $prizeType)
	{
		return DEBUG_becomeWinner($userId, $prizeType);
	}


	//Function that gives partners to everyone who is waiting for a partner in the lottery room (creates fake user if needed):
	function DEBUG_givePartnerToEveryone()
	{
		$response = "";

		//Gets all single partners waiting in lottery room:
		$singleUsers = getSingleUsers();
		
		if (sizeof($singleUsers) <= 0) { $response = "No single users waiting in lottery room were found!"; return; }

		foreach ($singleUsers as $userId)
		{
			$response .= trim(giveUserAPartner($userId, FALSE, TRUE));
		}

		return $response;
	}


	//Function that finds all single partners:
	function getSingleUsers()
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array(); //If all goes well, it will return the user partner.

		//Sends the query:
		$query = "SELECT user_id FROM finalists WHERE user_id AND user_id NOT IN (SELECT user1_id FROM couples) AND user_id NOT IN (SELECT user2_id FROM couples) AND user_id NOT IN (SELECT user_id FROM winners_or_losers) ORDER BY time;";
		
		$results = $dataBase->query($query);

		//If the query went well, gets the ids:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$response[] = $row["user_id"];
			}
		}

		return $response;
	}
