<?
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	// ! => implemented

	//USERS:
	//! userExists(userName, userPassword)
	//! userIdExists(userId)
	//! loginRegisterUser(userName, userPassword, userGender, userKeyword = "")
	//! getUserId(userName, userPassword)
	//! getUserGender(userId)
	//! insertUser(userName, userPassword, userGender, userKeyword = "");
	//! insertFakeUser(gender, world);
	////getUserKeyword(userId)
	////getUserName(userId) //It will be used to log the user with Wechat id.
	////getUserPassword(userId) //It will be used to log the user with Wechat id.
	//! getUserIdFromKeyword(userKeyword) //It will be used to log the user with Wechat id.

	//QUEUES:
	//! insertUserQueue(userId, world)
 	//! insertUserWorld(userId)
	//! getPeoplePerGenderWorld(world, gender)
	//! getUsersWorld(world, [gender])
	//! getWorlds();
	//! getUserWorld(userId)
	//! getUserInitialPosition(userId)
	//! deleteUserQueue(userId)

	//INVITATIONS:
	//! insertUserHost(hostUserId, guestUserId)
	//! getUserHost(guestUserId)
	//! getUserGuests(hostUserId)
	//! getHosts(world)

	//FINALISTS:
	//! isUserFinalist(userId)
	//! insertUserFinalist(userId)i
	//! deleteUserQueue(userId)
	//! insertUserFinalistIfAble(userId)
	//! insertUsersFinalistIfAble(world)
	//! setLotteryResult(userId, lotteryResult) //Don't forget to search in the two colums!
	//! getLotteryResult(userId) //Don't forget to search in the two colums!

	//PRIZES:
	//! isUserWinnerOrLoser(userId);
	//! isUserLoser(userId);
	//! isUserWinner(userId)
	//! insertUserWinnerLoser(userId, prizeType, winner, deleteFromFinalists)
	//! getUserPrizeType(userId)
	//! getWinnersOrLosersByTime([prizeType=all])
	//! getTotalPrizeTypeWon(prizeType)
	//! getUserPrizePosition(userId, prizeType)

	//COUPLES:
	//! getUserPartner(userId)
	//! insertUserCouple(user1Id, user2Id)
	//! giveUserAPartner(userId, [partnerWithoutResult=FALSE], [createFakeIfNotFound=TRUE])
	//! getSinglePartner(gender)
	
	//GENERAL:
	//! getRanking(world, [gender]) //Calculated with initial_position and guests (invitations)
	//! createFinalistCouple(user1Id, user2Id) //calls insertUserFinalist and insertUserCouple (used when enter a room).
	//! isGameFinished()
	//! getData(userId)
	//! playLottery(userId)
	//! calculateLotteryResult($userId)
	//! anyPrizeCanBeWon()
	//! prizeTypeCanBeWon(prizeType, quantity)
	//! getAnyPrizeType(winner, quantity)
	//! getPrizeInformation(userId)
	////gameCycle(userId)


	//Function that escapes a string for mySQL:
	function escapeStringMySQL($string)
	{
		global $dataBase;
		if (isset($dataBase) && $dataBase->conexion) { return mysqli_real_escape_string($dataBase->conexion, $string); }
		else { return mysql_real_escape_string($string); }
	}



	//Function that checks whether a user exists or not (using name and password):
	function userExists($userName, $userPassword)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//Prevents mySQL injection:
		$userName = trim(escapeStringMySQL($userName));
		$userPassword = trim(escapeStringMySQL($userPassword));

		//Sends the query:
		$query = "SELECT user_id FROM users WHERE user_name = '" . $userName . "' AND user_password = '" . $userPassword . "';";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}


	//Function that checks whether a user exists or not (using id):
	function userIdExists($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM users WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}


	//Function that returns the user id:
	function getUserId($userName, $userPassword)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = 0; //If all goes well, it will return the user ID.

		//Prevents mySQL injection:
		$userName = trim(escapeStringMySQL($userName));
		$userPassword = trim(escapeStringMySQL($userPassword));

		//Sends the query:
		$query = "SELECT user_id FROM users WHERE user_name = '" . $userName . "' AND user_password = '" . $userPassword . "';";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_id"];
		}

		return $response;
	}


	//Function that returns the user id from an user keyword:
	function getUserIdFromKeyword($userKeyword)
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0; //If all goes well, it will return the user ID.

		//Prevents mySQL injection:
		$userKeyword = trim(escapeStringMySQL($userKeyword));

		if ($userKeyword === "") { return 0; }

		//Sends the query:
		$query = "SELECT user_id FROM users WHERE user_keyword = '" . $userKeyword . "';";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_id"];
		}

		return $response;
	}


	//Function that returns the name of an user id given:
	function getUserName($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the user name.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT user_name FROM users WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_name"];
		}

		return $response;
	}


	//Function that returns the password of an user id given:
	function getUserPassword($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the user password.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT user_password FROM users WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_password"];
		}

		return $response;
	}


	//Function that returns the keyword of an user id given:
	function getUserKeyword($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = 0; //If all goes well, it will return the user password.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT user_keyword FROM users WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_keyword"];
		}

		return $response;
	}


	//Function that returns a random string of a given length of characters:
	function getRandomString($length, $characters = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f")) //Because PHP's rand() doesn't work with big numbers!
	{
		if ($length <= 0) { return ""; }

		$string = "";

		while (strlen($string) < $length)
		{
			$randomPosition = rand(0, sizeof($characters) - 1);
			$string .= $characters[$randomPosition][0]; //Only inserts firts character in the case the array value has more than one.
		}

		return $string;
	}


	//Function that inserts a fake user:
	function insertFakeUser($gender, $world, $userHost = 0)
	{
		$inserted = FALSE;

		$maximumIntends = 1000;

		//Only lets male or female (will use male as default):
		if ($gender !== "male" && $gender !== "female") { $gender = "male"; }

		//Tries to register a fake user until it is successful:
		do
		{
			//Calculates a random name and password:
			//$prefix = "fake_" . $gender[0] . "_";
			$prefix = ($gender === "male") ? FAKE_USERS_PREFIX_MALE : FAKE_USERS_PREFIX_FEMALE;
			$randomString = getRandomString(USER_NAME_CHARACTERS_MAXIMUM - strlen($prefix));
			$userName = $prefix . $randomString;
			$randomString = getRandomString(PASSWORD_CHARACTERS_MAXIMUM - strlen($prefix));
			$userPassword = $prefix . $randomString;
			
			//Inserts the user (will not if the user already exists):
			$response = insertUser($userName, $userPassword, $gender);

			//If the user has been successfully inserted:
			if ($response === "")
			{
				//Gets the user id:
				$userId = getUserId($userName, $userPassword);

				//If they have given us a host, it will be the host of this fake user:
				if (isset($userHost) && is_numeric($userHost) && $userHost > 0)
				{
					insertUserHost($userHost, $userId);
				}

				//Inserts the user in the world given:
				$response = insertUserQueue($userId, $world);

				if ($response === "") { $inserted = TRUE; }
			}
		} while (!$inserted && --$maximumIntends > 0);

		return ($inserted) ? $userId : 0;
	}


	//Function that logins a user (resistering the user if doesn't exist):
	function loginRegisterUser($userName, $userPassword, $userGender, $userKeyword = "", $userHost = "")
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = ""; //If all goes well, it will return an empty string.

		//Prevents mySQL injection:
		$userName = trim(escapeStringMySQL($userName));
		$userPassword = trim(escapeStringMySQL($userPassword));
		$userGender = trim(strtolower(escapeStringMySQL($userGender)));
		$userKeyword = trim(escapeStringMySQL($userKeyword));
		$userHost = trim(escapeStringMySQL($userHost));

		//Only lets male or female (will use male as default):
		if ($userGender !== "male" && $userGender !== "female") { $userGender = "male"; }

		//Checks whether the user exists already or not:
		$existingUser = userExists($userName, $userPassword);

		//If there is no keyword provided, creates one with name and password:
		if ($userKeyword === "") { $userKeyword = "name_" . $userName . ", pass_" . $userPassword; }

		//If the user doesn't exists, tries to check whether there is an user with this keyword:
		if (!$existingUser)
		{
			$userIdByKeyword = getUserIdFromKeyword($userKeyword);
			$existingUser = (is_numeric($userIdByKeyword) && $userIdByKeyword > 0);
			//If the user exists, gets the user name and password:
			if ($existingUser)
			{
				//Gets the user name and password (these variables will be set in JavaScript):
				$userNameTemp = trim(getUserName($userIdByKeyword));
				$userPasswordTemp = trim(getUserPassword($userIdByKeyword));

				if ($userNameTemp !== "" && $userPasswordTemp !== "")
				{
					//$userId = $userIdByKeyword;
					$userName = $userNameTemp;
					$userPassword = $userPasswordTemp;
				}
			}
		}

		//If the user doesn't exists, creates it:
		if (!$existingUser)
		{
			//If there is no keyword provided, creates one with name and password:
			//if ($userKeyword === "") { $userKeyword = "name_" . $userName . ", pass_" . $userPassword; }

			//Inserts the user:
			$response = insertUser($userName, $userPassword, $userGender, $userKeyword);

			//If the user has been successfully created:
			if ($response === "")
			{
				//If the user has a host (has been invited by someone) and it's a valid one:
				if ($userHost !== "" && is_numeric($userHost) && userIdExists($userHost))
				{
					//Gets the user id:
					$userId = getUserId($userName, $userPassword);
					
					//Inserts the host for the user:
					$response = insertUserHost($userHost, $userId);
				}
			}
		}

		//If all went well, tries to place the user in a world (if the user is not already in one):
		if ($response === "")
		{
			$userId = getUserId($userName, $userPassword);

			//If the user is valid:
			if (is_numeric($userId) && $userId > 0)
			{
				//If the user is not in a world and is neither a winner or loser nor a finalist, places the user in one world:
				if (!isUserFinalist($userId) && !isUserWinnerOrLoser($userId) && getUserWorld($userId) === -1)
				{
					$response = insertUserWorld($userId);

					//If the world was not inserted, it is an error:
					if ($response !== "") { $response = "Unable to set a world for the user $userId!"; }
				}
			} else { $response = "User created but impossible to get the user id!"; }
		}

		//If we want to use cookies, sets the cookie to remember the user next time:
		if (USE_COOKIES)
		{
			setCookie("user_keyword", $userKeyword, mktime().time() + 60 * 60 * 24 * 30, "/");
		}

		//If all went well, it will return an empty string:
		return $response;
	}


	//Function that inserts a user:
	function insertUser($userName, $userPassword, $userGender, $userKeyword = "")
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user already exists, exits:
		if (userExists($userName, $userPassword)) { return "Can't insert an existing user."; }

		$response = "";

		//Prevents mySQL injection:
		$userName = trim(escapeStringMySQL($userName));
		$userPassword = trim(escapeStringMySQL($userPassword));
		$userGender = trim(strtolower(escapeStringMySQL($userGender)));
		$userKeyword = trim(escapeStringMySQL($userKeyword));

		//Sends the query:
		$query = "INSERT INTO users (user_name, user_password, user_gender, user_keyword) VALUES ('" . $userName . "', '" . $userPassword . "', '" . $userGender . "', " . (($userKeyword === "") ? "NULL" : "'" . $userKeyword . "'") . ");";
		$results = $dataBase->query($query);

		if (!$results) { $response = "Unable to insert the user!"; }

		return $response;
	}



	//Function that inserts a host for an invited user:
	function insertUserHost($hostUserId, $guestUserId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = ""; //If all goes well, it will return an empty string.

		//Prevents mySQL injection:
		$hostUserId = trim(escapeStringMySQL($hostUserId));
		$guestUserId = trim(escapeStringMySQL($guestUserId));

		//Checks whether both users exist already or not:
		if (!userIdExists($hostUserId) || !userIdExists($guestUserId))
		{
			$response = "Can't insert an invalid guest or host in invitations.";
		}
		else
		{
			//If the guest was not already invited before, tries to insert it:
			$guestInvited = getUserHost($guestUserId);
			if (!$guestInvited)
			{
				//Calculates the position in the ranking before jumping:
				$world = getUserWorld($hostUserId);
				$gender = getUserGender($hostUserId);
				$ranking = getRanking($world, $gender);
				$rankingPositionBeforeInvitation = array_search($hostUserId, $ranking);

				//Sends the query:
				$query = "INSERT INTO invitations (host_user_id, guest_user_id) VALUES (" . $hostUserId . ", " . $guestUserId . ");";
				$results = $dataBase->query($query);

				if (!$results) { $response = "Unable to insert the invitation"; }
				else
				{
					//If able, inserts the user as finalist:
					insertUserFinalistIfAble($hostUserId, $rankingPositionBeforeInvitation);
				}
			}
		}

		//If all went well, it will return an empty string:
		return $response;
	}


	//Function that gets the host for an invited user (if any):
	function getUserHost($guestUserId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0; //If all goes well, it will return the user ID.

		//Prevents mySQL injection:
		$guestUserId = trim(escapeStringMySQL($guestUserId));

		//Sends the query:
		$query = "SELECT host_user_id FROM invitations WHERE guest_user_id = " . $guestUserId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["host_user_id"];
		}

		return $response;
	}


	//Function that returns the guests invited by a host user:
	function getUserGuests($userHost)
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array();

		//Prevents mySQL injection:
		$userHost = trim(escapeStringMySQL($userHost));

		//Sends the query:
		$query = "SELECT guest_user_id FROM invitations WHERE host_user_id = " . $userHost . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$response[] = $row["guest_user_id"];
			}
		}

		return $response;
	}


	//Function that returns an array with the hosts (just from one world if it is set) ordered by time:
	function getHosts($world = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array();

		//Prevents mySQL injection:
		$world = strtolower(trim(escapeStringMySQL($world)));

		//Sends the query:
		$query = "SELECT host_user_id FROM invitations";
		//If we don't want the the users from all world, we select just the ones from the world desired:
		if (isset($world) && $world != NULL && $world !== "all" && $world !== "")
		{
 			$query .= "  WHERE host_user_id IN (SELECT user_id FROM queues WHERE world = " . $world . ")";
		}
 		$query .= " ORDER BY time;";

		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$response[] = $row["host_user_id"];
			}
		}

		return $response;
	}



	//Function that returns the initial position of a user id given:
	function getUserInitialPosition($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = -1; //If all goes well, it will return the initial position.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT initial_position FROM queues WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["initial_position"];
		}

		return $response;
	}


	//Function that inserts a user into a world:
	function insertUserQueue($userId, $world)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't insert an invalid user in queues."; }

		//If the user is already in a world, exits the function:
		if (getUserWorld($userId) !== -1) { return "The user is already in a world."; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));
		$world = trim(escapeStringMySQL($world));

		//Sends the query:
		$query = "INSERT INTO queues (user_id, world) VALUES (" . $userId . ", " . $world . ");";
		$results = $dataBase->query($query);

		if (!$results) { $response = "Unable to set a world for the user!"; }

		return $response;
	}


	//Function that deletes a user from the queue:
	function deleteUserQueue($userId)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "DELETE FROM queues WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		if (!$results) { $response = "Unable to delete the user from queues!"; }

		return $response;
	}


	//Function that returns the world of a given user id:
	function getUserWorld($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return -1; }

		$response = -1; //If all goes well, it will return the world.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return -1; }

		//Sends the query:
		$query = "SELECT world FROM queues WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["world"];
		}

		return $response;
	}


	//Function that inserts a user in a world (after finding it or creates a new one):
	function insertUserWorld($userId)
	{
		$response = "";

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't find a valid for an invalid user."; }

		//If the user is already in a world, exits the function:
		if (getUserWorld($userId) !== -1) { return "The user is already in a world."; }

		//Gets user gender:
		$userGender = getUserGender($userId);

		//Gets all the worlds:
		$worlds = getWorlds();

		//Looks for the worlds which have less people of the same gender and are not full:
		$maximumPeoplePerQueue = MAXIMUM_PEOPLE_PER_QUEUE;
		$minimumPeopleFound = $maximumPeoplePerQueue; //Starts looking for worlds with less people than the maximum allowed.
		$worldChosen = -1;
		$worldHighestNumber = 0; //We will use this to create a new world.
		foreach ($worlds as $world)
		{
			//Gets the number of people in the queue for the same gender:
			$numberOfPeople = sizeof(getPeoplePerGenderWorld($world, $userGender));

			//If this world has less people as the previous one, we choose it:
			if ($numberOfPeople < $minimumPeopleFound)
			{
				$worldChosen = $world;
			}

			//If the current world has an highest number that the previously found, we store it:
			if ($world > $worldHighestNumber) { $worldHighestNumber = $world; } //Userful to create a new world.
		}

		//If a world has not been found, we choose a new one (giving it the next number):
		if ($worldChosen === -1) { $worldChosen = $worldHighestNumber + 1; }

		//If the user has been inserted in the world succesfully:
		//if ($response === "")
		//{
			//If the world has not the minimum users required, insert them:
			$numberOfMales = sizeof(getPeoplePerGenderWorld($worldChosen, "male"));
			$numberOfFemales = sizeof(getPeoplePerGenderWorld($worldChosen, "female"));

			//Inserts fake male users (if needed):
			while ($numberOfMales++ < MINIMUM_MALE_USERS)
			{
				//Inserts a fake male user:
				$fakeUserId = insertFakeUser("male", $worldChosen);
			}

			//Inserts fake female users (if needed):
			while ($numberOfFemales++ < MINIMUM_FEMALE_USERS)
			{
				//Inserts a fake male user:
				$fakeUserId = insertFakeUser("female", $worldChosen);
			}

			//Inserts the user in the world chosen:
			$response = insertUserQueue($userId, $worldChosen);

		//}

		return $response;
	}


	//Function that returns the gender of a user:
	function getUserGender($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the user gender.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT user_gender FROM users WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_gender"];
		}

		return $response;
	}



	//Function that returns an array with the existing worlds:
	function getWorlds()
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array();

		//Sends the query:
		$query = "SELECT world FROM queues GROUP BY world;";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$response[] = $row["world"];
			}
		}

		return $response;
	}



	//Function that returns an array with the users id of a given gender which are in a given world:
	function getPeoplePerGenderWorld($world, $gender)
	{
		//Only lets male or female (will use male as default):
		if ($gender !== "male" && $gender !== "female") { $gender = "male"; }
		return getUsersWorld($world, $gender);
	}



	//Function that returns an array with the users of a given world:
	function getUsersWorld($world, $gender = "")
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array();

		//Prevents mySQL injection:
		$world = trim(escapeStringMySQL($world));
		$gender = strtolower(trim(escapeStringMySQL($gender)));

		if ($world === "" || !is_numeric($world) || $world < 0) { return Array(); }
		
		//Sends the query:
		$query = "SELECT user_id FROM queues AS A WHERE world = " . $world . "";
 		if ($gender === "male" || $gender === "female")
 		{
 			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}
		$query .= " AND user_id NOT IN (SELECT user_id FROM finalists AS B WHERE A.user_id = B.user_id)";
		$query .= " AND user_id NOT IN (SELECT user_id FROM winners_or_losers AS C WHERE A.user_id = C.user_id)";
 		$query .= " ORDER BY initial_position;";

		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				//Only if the user is not finalist and not winner:
				//if (!isUserFinalist($row["user_id"]) && !isUserWinner($row["user_id"]))
				{
					$response[] = $row["user_id"];
				}
			}
		}

		return $response;
	}


	//Functions that tells whether a user is finalist or not (has entered through door):
	function isUserFinalist($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM finalists WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}


	//Function that inserts a user as a finalist (has entered through the door):
	function insertUserFinalist($userId)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't insert an invalid user in finalists."; }

		//If the user is already a finalist, exits the function:
		if (isUserFinalist($userId)) { return "The user is already a finalist."; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "INSERT INTO finalists (user_id) VALUES (" . $userId . ");";
		$results = $dataBase->query($query);

		//If the user has been inserted as a finalist:
		if ($results)
		{
			//Deletes the user from the queue:
			////////$response = deleteUserQueue($userId);
		}
		else { $response = "Unable to set the user as a finalist!"; }

		return $response;
	}


	//Function that inserts a user as finalist if he has got enough invitations:
	function insertUserFinalistIfAble($userId, $rankingPositionBeforeInvitation)
	{
		$inserted = FALSE;

		//If the host will enter through the door thanks to this invitation, is a finalist:
		if ($rankingPositionBeforeInvitation !== FALSE) //If the host has been found in the ranking.
		{
			//If the host enter the door with this invitation:
			if ($rankingPositionBeforeInvitation - PLACES_JUMP_ON_GET_GUEST < 0)
			{
				//echo $userId . " goes from " . $rankingPositionBeforeInvitation . " to " . ($rankingPositionBeforeInvitation - PLACES_JUMP_ON_GET_GUEST);

				//The host becomes a finalist:
				$response = insertUserFinalist($userId); //It will be deleted from the queue.
				if ($response === "")
				{
					$inserted = TRUE;

					//Tries to make a couple:
					giveUserAPartner($userId);

					//echo "user inserted!";
				} //else { echo "User NOT inserted!"; }
			} //else { echo "Not enough to jumb! from " . $rankingPositionBeforeInvitation . " to " . ($rankingPositionBeforeInvitation - PLACES_JUMP_ON_GET_GUEST) . " is not less than zero"; }
		} //else { echo "rankingPositionBeforeInvitation is FALSE!!!"; }

		return $inserted;
	}


	//Function that inserts all user finalists found for a given world:
	function insertUsersFinalistIfAble($world, $gender = "")
	{
		//Gets an array with the users of the given world:
		$users = getUsersWorld($world, $gender);

		$anyInserted = FALSE;

		//Loops through the users:
		foreach ($users as $userId)
		{
			$gender = getUserGender($userId);
			$ranking = getRanking($world, $gender);
			$rankingPosition = array_search($userId, $ranking);
			if (insertUserFinalistIfAble($userId, $rankingPosition)) { $anyInserted = TRUE; }
		}

		return $anyInserted;
	}


	//Function that deletes a user from the finalists:
	function deleteUserFinalist($userId)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "DELETE FROM finalists WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		if (!$results) { $response = "Unable to delete the user from finalists!"; }

		return $response;
	}


	//Function that inserts a couple:
	function setLotteryResult($userId, $lotteryResult)
	{
		global $dataBase;

		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't set a lottery result for an invalid user."; }

		//If the user is not a finalist, exits:
		if (!isUserFinalist($userId)) { return "Can't set a lottery result for a non finalist user."; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));
		if ($lotteryResult !== NULL) { $lotteryResult = strtolower(trim(escapeStringMySQL($lotteryResult))); }

		//Sends the query:
		$query = "UPDATE finalists SET lottery_result = " . (($lotteryResult === NULL) ? "NULL" : "'" . $lotteryResult . "'") . " WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the lottery result has not been inserted:
		if (!$results)
		{
			$response = "Unable to set a lottery result for the user!";
		}

		return $response;
	}


	//Function that gets the lottery result of a given user id:
	function getLotteryResult($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the lottery result.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//If the user id is empty, exits:
		if ($userId === "") { return ""; }

		//Sends the query:
		$query = "SELECT lottery_result FROM finalists WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["lottery_result"];
		}

		if ($response == NULL) { $response = ""; }

		return $response;
	}



	//Function that deletes a given user from the winners:
	function deleteUserFromWinnersOrLosers($userId)
	{
		//Deletes the user from winners (just in case):
		$response = "";
		global $dataBase;
		if (!isset($dataBase)) { return "DataBase object not found!"; }
		$userId = trim(escapeStringMySQL($userId)); //Prevents mySQL injection.
		$query = "DELETE FROM winners_or_losers WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);
		if (!$results) { $response = "Unable to delete the user $userId from winners or losers!"; }
		return $response;
	}



	//Functions that tells whether a user is a winner or not:
	function isUserWinner($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM winners_or_losers WHERE user_id = " . $userId . " AND winner != 0;";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}


	//Functions that tells whether a user is a loser or not:
	function isUserLoser($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM winners_or_losers WHERE user_id = " . $userId . " AND winner = 0;";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}


	//Functions that tells whether a user is a winner or not (has won a prize):
	function isUserWinnerOrLoser($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM winners_or_losers WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}



	//Function that inserts a user as a winner (has won a prize):
	function insertUserWinnerLoser($userId, $prizeType, $winner = TRUE, $deleteFromFinalists = FALSE)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't insert an invalid user in winners."; }

		//If the user is already a winner or loser, exits the function (unless we want to update):
		if (isUserWinnerOrLoser($userId)) { return "The user " . $userId . " is already a winner or loser."; }

//echo "Inserting " . $userId . " as winner = " . $winner . "<br />";

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));
		if ($prizeType !== NULL) { $prizeType = strtolower(trim(escapeStringMySQL($prizeType))); }

		if ($prizeType === 0) { $prizeType = NULL; }

		//Sends the query:
		$query = "INSERT INTO winners_or_losers (user_id, prize_type, winner) VALUES (" . $userId . ", " . (($prizeType === NULL) ? "NULL" : "'" . $prizeType . "'") . ", '" . ($winner ? 1 : 0) . "');";
		$results = $dataBase->query($query);

		//If the user has been inserted as a winner:
		if ($results)
		{
			if ($deleteFromFinalists)
			{
				//Deletes the user from the finalists:
				$response = deleteUserFinalist($userId);
			}
		}
		else { $response = "Unable to set the user as a winner!"; }

		return $response;
	}



	//Function that returns prize type of a winner user:
	function getUserPrizeType($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the prize type.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT prize_type FROM winners_or_losers WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["prize_type"];
		}

		if ($response == NULL) { $response = ""; }

		return $response;
	}


	//Function that returns an array with users ordered by time who won a prize type (or all) given:
	function getWinnersOrLosersByTime($prizeType = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return Array(); }

		$response = Array();

		//Prevents mySQL injection:
		$prizeType = strtolower(trim(escapeStringMySQL($prizeType)));

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM winners_or_losers";

		//If we don't want the time of all prizes, we select just the prize type desired:
		if (isset($prizeType) && $prizeType != NULL && $prizeType !== "all")
		{
			$query .= " WHERE prize_type = '" . $prizeType . "'";
		}
		//Finishes the query:
		$query .= " ORDER BY time;";

		//Sends the query:
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$userId = $row["user_id"];
				$userName = getUserName($userId);

				$isFakeUser = FALSE;

				//Defines whether is a fake user or not:
				if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
				else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

				//If the user name is not empty and is not a fake user:
				if ($userName !== "" && !$isFakeUser)
				{
					$response[] = $userId;
				}
			}
		}

		return $response;
	}


	//Function that returns the total of times a prize type has been won:
	function getTotalPrizeTypeWon($prizeType)
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0; //If all goes well, it will return the times a prize has been won.

		//Prevents mySQL injection:
		$prizeType = trim(escapeStringMySQL($prizeType));

		//Sends the query:
		//$query = "SELECT count(prize_type) AS total FROM winners_or_losers WHERE prize_type = '" . $prizeType . "' GROUP BY prize_type;";
		$query = "SELECT user_id FROM winners_or_losers WHERE prize_type = '" . $prizeType . "';";
		$results = $dataBase->query($query);

/*
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_array($results);
			//$row = mysqli_fetch_assoc($results);
			//$response = $row["total"];
			$response = $row[0];
		}
*/

		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$userId = $row["user_id"];
				$userName = getUserName($userId);

				$isFakeUser = FALSE;

				//Defines whether is a fake user or not:
				if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
				else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

				//If the user name is not empty and is not a fake user:
				if ($userName !== "" && !$isFakeUser)
				{
					$response++;
				}
			}
		}

		return $response;
	}


	//Function that returns the partner of a given user id:
	function getUserPartner($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0; //If all goes well, it will return the user partner.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT user1_id, user2_id FROM couples WHERE user1_id = " . $userId . " OR user2_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			//One of the fields will be the given user id and the other the partner:
			$response = ($row["user1_id"] == $userId) ? $row["user2_id"] : $row["user1_id"];
		}

		return $response;
	}



	//Function that tells whether a given users are a couple:
	function areUsersCouple($user1Id, $user2Id)
	{
		return (getUserPartner($user1Id) == $user2Id);
	}


	//Function that inserts a couple:
	function insertUserCouple($user1Id, $user2Id)
	{
		global $dataBase;
		//if (!isset($dataBase) || !($dataBase instanceof DataBase)) { return "DataBase object not found!"; }
		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($user1Id) || !userIdExists($user2Id)) { return "Can't insert an invalid user in winners."; }

		//If they are already a couple, exits:
		if (areUsersCouple($user1Id, $user2Id)) { return "The users are already a couple."; }

		//If they don't have same gender, exits:
		$user1Gender = getUserGender($user1Id);
		$user2Gender = getUserGender($user2Id);
		if ($user1Gender !== "" && $user1Gender === $user2Gender) { return "The members of a couple can't have the same gender"; }

		$response = "";

		//Prevents mySQL injection:
		$user1Id = trim(escapeStringMySQL($user1Id));
		$user2Id = trim(escapeStringMySQL($user2Id));

		//Sends the query:
		$query = "INSERT IGNORE INTO couples (user1_id, user2_id) VALUES (" . $user1Id . ", " . $user2Id . ");";
		$results = $dataBase->query($query);

		//If the couple has not been inserted:
		if (!$results)
		{
			$response = "Unable to set the users as a couple!";
		}

		return $response;
	}



	//Function that creates a finalist couple when they enter through the door:
	function createFinalistCouple($user1Id, $user2Id)
	{
		//Sets both users as finalists (if they are not already):
		insertUserFinalist($user1Id);
		insertUserFinalist($user2Id);

		//Set them as a couple (if they are not already):
		insertUserCouple($user1Id, $user2Id);

		//If they both are now finalists and a couple, returns true:
		return (isUserFinalist($user1Id) && isUserFinalist($user2Id) && areUsersCouple($user1Id, $user2Id));
	}



	//Function that looks for a partner for an user and make them a couple:
	function giveUserAPartner($userId, $partnerWithoutResult = FALSE, $createFakeIfNotFound = TRUE)
	{
		//Gets the gender:
		$gender = strtolower(getUserGender($userId));

		//If can't get the gender, exits:
		if ($gender === "") { return "Impossible to get the gender"; }

		$genderOpposite = ($gender === "male") ? "female" : "male";

		$response = "";

		//Finds a single partner of the opposite gender:
		$partnerId = getSinglePartner($genderOpposite, $partnerWithoutResult);
		//If there is any, insert them as a couple:
		if ($partnerId !== 0)
		{
			$response = insertUserCouple($userId, $partnerId);
		}
		else { $response = "Unable to find a single partner"; }

		//If the couple has been inserted:
		if ($response === "")
		{
			//Set the couple as winner or loser if able:
			setWinnersOrLosersCouple($userId);
		}
		//...otherwise, if we have not found a partner, we create a fake one (if we want to):
		else if ($createFakeIfNotFound)
		{
			$response = giveUserPartnerFake($userId);
		}

		return $response;
	}


	//Function that gives a partner to a user:
	function giveUserPartnerFake($userId)
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
			$response = goToLotteryRoom($fakeUserId);

			//If the fake user has become a finalist:
			if ($response === "")
			{
				//Makes the fake user become the partner of the user:
				$response = insertUserCouple($userId, $fakeUserId);

				//If all went well, gives the partner a lottery result:
				if ($response === "")
				{
					//Makes the partner play the lottery:
					$lotteryResult = trim(playLottery($fakeUserId));
					$response = (is_numeric($lotteryResult) && $lotteryResult > 0) ? "" : $lotteryResult;
				}
			}
		} else { return "Fake user couldn't be created!"; }
		
		return $response;
	}



	//Function that finds single partners of an specific gender:
	function getSinglePartner($gender, $partnerWithoutResult = FALSE)
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0; //If all goes well, it will return the user partner.

		//Prevents mySQL injection:
		$gender = strtolower(trim(escapeStringMySQL($gender)));

		//Sends the query:
		$query = "SELECT user_id FROM finalists WHERE user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "') AND user_id NOT IN (SELECT user1_id FROM couples) AND user_id NOT IN (SELECT user2_id FROM couples) AND user_id NOT IN (SELECT user_id FROM winners_or_losers)";
		if ($partnerWithoutResult) { $query .= " AND lottery_result IS NULL"; }
		$query .=  " ORDER BY time;";
		
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["user_id"];
		}

		return $response;
	}



	//Function that gets the users id ranking of a world calculated with initial position and guests of each user:
	function getRanking($world, $gender = "")
	{
		//Array which will contain the users id ordered by their position:
		$ranking = Array();

		//Gets an array with the users of the given world:
		$users = getUsersWorld($world, $gender); //This gives the users ordered by initial_position!

		//First, the ranking uses the initial ranking:
		$ranking = $users; //Note that in PHP arrays are not passed by reference unless you use "&".

		//Gets an array of the hosts ordered by the time they sent the invitation.
		$hosts = getHosts($world);

		//Loops through the hosts (ordered by the time they sent the invitation):
		foreach ($hosts as $hostId)
		{
			//Loops through the users (ordered by initial position):
			foreach ($users as $userId)
			{
				//If the current user is the one who sent the invitation (the host):
				if ($userId === $hostId)
				{
					//Changes the position of the user where s/he should jump:
					$ranking = moveValueNumberPlaces($userId, PLACES_JUMP_ON_GET_GUEST * -1, $ranking);
				}
			}
		}

		//Returns the ranking array:
		return $ranking;
	}


	//Function that moves a desired number of places a given value from a given array:
	function moveValueNumberPlaces($value, $numberPlaces, $array)
	{
		//Gets the position of the value in the array given:
		$valuePosition = array_search($value, $array);

		//If the value is not found, exits:
		if ($valuePosition === FALSE) { return $array; }

		//Calculates the new position desired:
		$valuePositionNew = $valuePosition + $numberPlaces;

		//NOTE: If the new position is less than zero, the user should become a finalist!!!

		//If the position is less than possible, corrects it:
		if ($valuePositionNew < 0) { $valuePositionNew = 0; }

		//If the position is more than possible, corrects it:
		if ($valuePositionNew >= sizeof($array)) { $valuePositionNew = sizeof($array) - 1; }

		//If the old and positions are the same, exits:
		if ($valuePosition === $valuePositionNew) { return $array; }

		//Deletes the value from the array:
		//unset($array[$valuePosition]);
		$array = array_diff($array, Array($value));

		//Inserts the value into the array again in the new position:
		array_splice($array, $valuePositionNew, 0, Array($value));
		
		//Reorganize the array giving all values ordered keys (because some keys will have disappeared):
		//$newArray = $array;
		/*
		$newArray = Array();
		foreach ($array as $value)
		{
			$newArray[] = $value;
		}
		*/
		//return $newArray;
		return $array;
	}


	//Function that gets the users id ranking of a world calculated with initial position and guests of each user:
	/*
	function getRanking_OLD($world, $gender = "")
	{
		//Array which will contain the users id ordered by their position:
		$ranking = Array();

		//Gets an array with the users of the given world:
		$users = getUsersWorld($world, $gender); //This gives the users ordered by initial_position!

		//Calculates the score of each user, having in mind initial position and guests invited (less is better):
		$rankingUnsorted = Array();
		$x = 0;
		foreach ($users as $userId)
		{
			//$initialPosition = getUserInitialPosition($userId);
			$realInitialPosition = $x++;
			$guestsInvited = sizeof(getUserGuests($userId));
			$score = $realInitialPosition - $guestsInvited * PLACES_JUMP_ON_GET_GUEST; //Less is better!
			$rankingUnsorted[] = Array("user_id" => $userId, "score" => $score);
		}

		//Orders the ranking (uses bubble sort algorithm):
		$size = sizeof($rankingUnsorted);
		for ($i = 0; $i < $size; $i++)
		{
	    	for ($j = 0; $j < $size - 1 - $i; $j++)
	    	{
	        	//If the score is less (less is better), swaps them:
	        	if ($rankingUnsorted[$j + 1]["score"] < $rankingUnsorted[$j]["score"])
	        	{
	                swap($rankingUnsorted, $j, $j + 1);
	    	    }
	    	    //...otherwise, if the users have same score:
	    	    else if ($rankingUnsorted[$j + 1]["score"] == $rankingUnsorted[$j]["score"])
	    	    {
					//Checks how many users they have invited:
					$guestsInvited1 = sizeof(getUserGuests($rankingUnsorted[$j]["user_id"]));
					$guestsInvited2 = sizeof(getUserGuests($rankingUnsorted[$j + 1]["user_id"]));
	    	    	//If the last one has more invitations, swap them:
	    	    	if ($guestsInvited2 > $guestsInvited1)
	    	    	{
	    	    		swap($rankingUnsorted, $j, $j + 1);
	    	    	}
	    	    }
			}
		}

		//Fills the real ranking array with just the user id's (we don't need the current position):
		foreach ($rankingUnsorted as $array)
		{
			$ranking[] = $array["user_id"];
		}

		//Returns the ranking array:
		return $ranking;
	}


	//Function that swaps two values in an array given (used for bubble sort algorithm):
	function swap(&$array, $a, $b)
	{
	    $temp = $array[$a];
	    $array[$a] = $array[$b];
	    $array[$b] = $temp;
	}
	*/


	//Function that calculates if the game has ended:
	function isGameFinished()
	{
		//If there are no more prize types available, then the game ended:
		return (!anyPrizeCanBeWon());
	}


	//Function that returns the data (JSON object) of a given user:
	function getData($userId)
	{
		//Gets the needed data:
		$partnerId = getUserPartner($userId);
		$world = getUserWorld($userId);

		$isFinalist = isUserFinalist($userId);
		$isWinner = isUserWinner($userId);

		//If the user is not in a world and is neither a winner nor a finalist, places the user in one world:
		if (!$isFinalist && !$isWinner && $world === -1)
		{
			insertUserWorld($userId);
		}

		//Processes the game cycle:
		//gameCycle($userId);
		//insertUsersFinalistIfAble($world);

		//Creates the JSON object with the data needed:
		//NOTE: userName, userPassword, userKeyword, userHost, world and initialPosition are only
		//necessary in DEBUG_MODE.
		$JSON = '
					{';
		
		if (DEBUG_MODE)
		{
			$JSON .= '
							"userName" : "' . getUserName($userId) . '",
							"userPassword" : "' . getUserPassword($userId) . '",
							"userKeyword" : "' . getUserKeyword($userId) . '",
							"userHost" : ' . getUserHost($userId) . ',
							"world" : "' . $world . '",
							"initialPosition" : "' . getUserInitialPosition($userId) . '",

							"totalUsers" : "' . getTotalUsers() . '",
							"totalUsersNoFake" : "' . getTotalUsers(true) . '",
							"totalUsersNoFakeMale" : "' . getTotalUsers(true, "male") . '",
							"totalUsersNoFakeFemale" : "' . getTotalUsers(true, "female") . '",
							
							"totalInQueues" : "' . getTotalInQueues() . '",
							"totalInQueuesNoFake" : "' . getTotalInQueues(true) . '",
							"totalInQueuesNoFakeMale" : "' . getTotalInQueues(true, "male") . '",
							"totalInQueuesNoFakeFemale" : "' . getTotalInQueues(true, "female") . '",
							
							"totalWithoutGuests" : "' . (1||getTotalWithoutGuests()) . '",
							"totalWithoutGuestsNoFake" : "' . (1||getTotalWithoutGuests(true)) . '",
							"totalWithoutGuestsNoFakeMale" : "' . (1||getTotalWithoutGuests(true, "male")) . '",
							"totalWithoutGuestsNoFakeFemale" : "' . (1||getTotalWithoutGuests(true, "female")) . '",

							"totalInLotteryRoom" : "' . getTotalInLotteryRoom() . '",
							"totalInLotteryRoomNoFake" : "' . getTotalInLotteryRoom(true) . '",
							"totalInLotteryRoomNoFakeMale" : "' . getTotalInLotteryRoom(true, "male") . '",
							"totalInLotteryRoomNoFakeFemale" : "' . getTotalInLotteryRoom(true, "female") . '",

							"totalInLotteryRoomSingle" : "' . getTotalInLotteryRoomSingle() . '",
							"totalInLotteryRoomSingleNoFake" : "' . getTotalInLotteryRoomSingle(true) . '",
							"totalInLotteryRoomSingleNoFakeMale" : "' . getTotalInLotteryRoomSingle(true, "male") . '",
							"totalInLotteryRoomSingleNoFakeFemale" : "' . getTotalInLotteryRoomSingle(true, "female") . '",

							"totalInLotteryRoomWithoutResult" : "' . getTotalInLotteryRoomWithoutResult() . '",
							"totalInLotteryRoomWithoutResultNoFake" : "' . getTotalInLotteryRoomWithoutResult(true) . '",
							"totalInLotteryRoomWithoutResultNoFakeMale" : "' . getTotalInLotteryRoomWithoutResult(true, "male") . '",
							"totalInLotteryRoomWithoutResultNoFakeFemale" : "' . getTotalInLotteryRoomWithoutResult(true, "female") . '",

							"totalWinners" : "' . getTotalWinners() . '",
							"totalWinnersNoFake" : "' . getTotalWinners(true) . '",
							"totalWinnersNoFakeMale" : "' . getTotalWinners(true, "male") . '",
							"totalWinnersNoFakeFemale" : "' . getTotalWinners(true, "female") . '",

							"totalLosers" : "' . getTotalLosers() . '",
							"totalLosersNoFake" : "' . getTotalLosers(true) . '",
							"totalLosersNoFakeMale" : "' . getTotalLosers(true, "male") . '",
							"totalLosersNoFakeFemale" : "' . getTotalLosers(true, "female") . '",

							"invitationsSentAverageInQueue" : "' . (1||invitationsSentAverageInQueue()) . '",
							"invitationsSentAverageInQueueNoFake" : "' . (1||invitationsSentAverageInQueue(true)) . '",
							"invitationsSentAverageInQueueNoFakeMale" : "' . (1||invitationsSentAverageInQueue(true, "male")) . '",
							"invitationsSentAverageInQueueNoFakeFemale" : "' . (1||invitationsSentAverageInQueue(true, "female")) . '",

							"invitationsSentAverageToBeFinalist" : "' . invitationsSentAverageToBeFinalist() . '",
							"invitationsSentAverageToBeFinalistNoFake" : "' . invitationsSentAverageToBeFinalist(true) . '",
							"invitationsSentAverageToBeFinalistNoFakeMale" : "' . invitationsSentAverageToBeFinalist(true, "male") . '",
							"invitationsSentAverageToBeFinalistNoFakeFemale" : "' . invitationsSentAverageToBeFinalist(true, "female") . '",

							"totalPrizesWon" : "' . addslashes(getTotalPrizesWon()) . '",
					';
		}

		$JSON .= '
							"guests" : ' . sizeof(getUserGuests($userId)) . ',
							"isFinalist" : ' . ($isFinalist ? 'true' : 'false') . ',
							"partner" : ' . $partnerId . ',
							"gender" : "' . getUserGender($userId) . '",
							"lotteryResult" : "' . getLotteryResult($userId) . '",
							"lotteryResultPartner" : "' . getLotteryResult($partnerId) . '",
							"isLoser" : ' . (isUserLoser($userId) ? 'true' : 'false') . ',
							"isWinner" : ' . ($isWinner ? 'true' : 'false') . ',
							"shopSelected" : "' . getSelectedShop($userId) . '",
							"prizeType" : "' . getUserPrizeType($userId) . '",
							"prizeGiven" : ' . (getPrizeGiven($userId) ? 'true' : 'false') . ',
							"ranking" : {
											"male" : [' . implode(", ", getRanking($world, "male")) . '],
											"female" : [' . implode(", ", getRanking($world, "female")) . ']
										},
							"isGameFinished" : ' . (isGameFinished() ? 'true' : 'false') . '
					}
				';

		//Returns the JSON object:
		return $JSON;
	}


	//Function that tells whether an user is loser (the player partner has another lottery result):
	/*
	function isUserLoser($userId)
	{
		//If the user is winner, then is not a loser:
		if (isUserWinner($userId)) { return FALSE; }

		$isUserLoser = false;

		//Gets the result of both (user and partner):
		$lotteryResult = trim(getLotteryResult($userId));
		$partnerId = getUserPartner($userId);
		$lotteryResultPartner = trim(getLotteryResult($partnerId));

		//If both results are not empty:
		if ($lotteryResult !== "" && $lotteryResultPartner !== "")
		{
			//If they have different result, the user is a loser:
			if ($lotteryResult !== $lotteryResultPartner)
			{
				$isUserLoser = TRUE;
			}
		}

		return $isUserLoser;
	}
	*/


	//Function that makes an user get a lottery result:
	function playLottery($userId)
	{
		//If the user has already won, exits:
		if  (isUserWinnerOrLoser($userId)) { return "A winner or loser cannot play the lottery again!"; }

		//If the user is not finalist, exits:
		if  (!isUserfinalist($userId)) { return "An user not finalist cannot play lottery!"; }

		//If the user has already a result, exits returning it:
		$previousResult = trim(getLotteryResult($userId));
		if ($previousResult != "") { return $previousResult; }

		$lotteryResult = calculateLotteryResult($userId); //If there is no prize, it will return an empty string.

		//If there has been a valid lottery result:
		if ($lotteryResult !== "")
		{
			//echo "<br />Se introduce " . $lotteryResult . "<br />";

			//Introduces it to the database:
			setLotteryResult($userId, $lotteryResult);

			//Set the couple as winner or loser if able:
			setWinnersOrLosersCouple($userId);
		} //else { return "Unable to get a valid lottery result (maybe no prizes left)."; }

		//Returns the lottery result:
		return $lotteryResult;
	}



	//Function that sets a couple as winners or losers if they already have a result:
	function setWinnersOrLosersCouple($userId)
	{
		$winners = FALSE;

		//If the user has a partner and the partner has a lottery result:
		$partnerId = getUserPartner($userId);
		$lotteryResult = getLotteryResult($userId);
		$lotteryResultPartner = getLotteryResult($partnerId);
		if ($partnerId && $lotteryResult !== "" && $lotteryResultPartner !== "")
		{
			//If they both have the same lottery result, they are winners:
			if ($lotteryResult == $lotteryResultPartner) { $winners = TRUE; }

			//We choose a prize type for each:
			$prizeType = getAnyPrizeType($winners, 2);
			//$prize1Type = getAnyPrizeType($winners);
			//$prize2Type = getAnyPrizeType($winners);

			//Set them as winners or losers with the prize type chosen:
			insertUserWinnerLoser($userId, $prizeType, $winners);
			insertUserWinnerLoser($partnerId, $prizeType, $winners);
		}

		return $winners;
	}


	//Function that calculates a lottery result for an user given:
	function calculateLotteryResult($userId)
	{
		$lotteryResult = ""; //If there is no prize, it will return an empty string.

		//If the user has not a partner, we get it:
		$partnerId = getUserPartner($userId);
		//if (!is_numeric($partnerId) || $partnerId <= 0) { return ""; }

		$partnerLotteryResult = getLotteryResult($partnerId); //Partner lottery result (if any).

		//If the partner doesn't have any result, any lottery result is possible:
		if ($partnerLotteryResult === "") { $lotteryResult = rand(1, 12); }
		//...otherwise, if the partner has a result:
		else
		{
			//If there are no prizes available, the user has to lose:
			if (!anyPrizeCanBeWon()) { do { $lotteryResult = rand(1, 12); } while ($partnerLotteryResult == $lotteryResult); }
			//...otherwise, if we have prizes available:
			else
			{
				//Calculates a number between 1 and 100:
				$randomNumber = rand(1, 100);

				//If the number calculated is less or equal than the chances to win, the user win:
				if ($randomNumber <= PRIZE_CHANCES_TO_WIN) { $lotteryResult = $partnerLotteryResult; }
				//...otherwise, we make the user lose:
				else { do { $lotteryResult = rand(1, 12); } while ($partnerLotteryResult == $lotteryResult); }
			}
		}

/*

		//We get a lottery result (if the user can't get a prize, then we make the user lose):
		//Note: if the partner doesn't have a prize yet, any result is ok.
		//      Otherwise, any result is ok as long as a prize can be won.
		
		do
		{
			$lotteryResult = rand(1, 12);
		} while ($partnerLotteryResult !== "" && !anyPrizeCanBeWon() && $lotteryResult == $partnerLotteryResult);
*/

		//Returns the calculated lottery result:
		return $lotteryResult;
	}



	//Function that calculates whether a prize can be won or not (to assure a maximum of prizes):
	function anyPrizeCanBeWon()
	{
		return (getAnyPrizeType(true) !== 0); //If there is no more prizes for winners, the game finishes.
	}


	//Function that returns whether a type of prize can be won (to assure a maximum of prizes):
	function prizeTypeCanBeWon($prizeType, $quantity = 1)
	{
		global $prizeTypes;

		if (!isset($prizeTypes) || !isset($prizeTypes[$prizeType]) || !isset($prizeTypes[$prizeType]["maximum"]))
		{
			return FALSE;
		}

		$canBeWon = FALSE;

		//Gets the maximum number of the prizes of this type that can be won:
		$maximumCanBeWon = $prizeTypes[$prizeType]["maximum"];
		if (!is_numeric($maximumCanBeWon) || $maximumCanBeWon <= 0) { return FALSE; }

		//Gets the number of prizes already won for this type:
		$alreadyWon = getTotalPrizeTypeWon($prizeType);

		//If we have still not reached the limit, then it still can be won:
		if ($alreadyWon + $quantity <= $maximumCanBeWon) { $canBeWon = TRUE; }

		return $canBeWon;
	}


	//Function that returns a random prize type that can be won (used for the winners or losers):
	function getAnyPrizeType($winner = TRUE, $quantity = 2)
	{
		global $prizeTypesForWinners, $prizeTypesForLosers;

		if (!isset($prizeTypesForWinners) && $winner || !isset($prizeTypesForLosers) && !$winner) { return 0; }

		$prizeTypeChosen = 0; //It will return 0 if no prize type is available.

		//Array which will store the available prize types that can be won:
		$prizeTypesAvailable = Array();

		//Check for the prize types that can still be won:
		$prizeTypesArray = $prizeTypesForWinners;
		if (!$winner) { $prizeTypesArray = $prizeTypesForLosers; } //If we want just a prize for a loser.
		foreach ($prizeTypesArray as $prizeType)
		{
			//If the prize type can be won, we store it as available:
			if (prizeTypeCanBeWon($prizeType, $quantity))
			{
				$prizeTypesAvailable[] = $prizeType;
			}
		}

		//If we have found available prize types that can be won:
		if (sizeof($prizeTypesAvailable) > 0)
		{
			//We choose a random prize type of the available ones:
			$prizeTypeChosen = $prizeTypesAvailable[rand(0, sizeof($prizeTypesAvailable) - 1)];
		}

		//Returns the prize type chosen (if any):
		return $prizeTypeChosen;
	}


	//Function that returns the information of a prize won by a user (as a JSON object):
	function getPrizeInformation($userId, $userName, $userPassword)
	{
		global $prizeTypes;

		//If the user is not neither winner nor loser, exits:
		if (!isUserWinnerOrLoser($userId)) { return ""; }

		//If the name and password given are not from the same user, exits:
		if ($userId != getUserId($userName, $userPassword)) { return ""; }

		$response = "";

		//Gets the prize of the user:
		$prizeType = trim(getUserPrizeType($userId));

		//If the user doesn't have a prize or the array key doesn't exists, exits:
		if ($prizeType === "" || !isset($prizeTypes[$prizeType])) { return ""; }

		$userPrizePosition = getUserPrizePosition($userId, $prizeType);

		//If the user is not found in any position, exists:
		if ($userPrizePosition === FALSE) { return ""; }

		$userGender = getUserGender($userId);
		if ($userGender !== "male" && $userGender !== "female") { $userGender = "male"; }

		//Gets information about the prize:
		$prizeName = localize($prizeType);
		$prizeImage = $prizeTypes[$prizeType]["image_" . $userGender];
		$prizeCodePositionLeft = $prizeTypes[$prizeType]["code_position_left"];
		$prizeCodePositionTop = $prizeTypes[$prizeType]["code_position_top"];
		$prizeCodes = $prizeTypes[$prizeType]["codes"];
		$prizeCode = "";
		if (isset($prizeCodes[$userPrizePosition])) { $prizeCode = $prizeCodes[$userPrizePosition]; }

		//Prepares the information (in JSON object):
		$response = '{
						"prizeType" : "' . $prizeType . '",
						"prizeName" : "' . $prizeName . '",
						"prizeImage" : "' . $prizeImage . '",
						"prizeCodePositionLeft" : "' . $prizeCodePositionLeft . '",
						"prizeCodePositionTop" : "' . $prizeCodePositionTop . '",
						"prizeCode" : "' . $prizeCode . '"
					}';

		//Returns the information:
		return $response;
	}



	//Function that returns the position (by time) that a winner or loser has for a prize type:
	function getUserPrizePosition($userId, $prizeType)
	{
		$users = getWinnersOrLosersByTime($prizeType);
		return array_search($userId, $users);
	}


	//Function that returns user name, password and gender, if any, through a keyword given (as a JSON object):
	function getUserByKeyword($userKeyword)
	{
		$response = "";

		//If we can get the user keyword, checks the database whether exists or not:
		$userKeyword = trim($userKeyword);
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

				if ($userName !== "" && $userPassword !== "")
				{
					$response = '{ "userName" : "' . $userName . '", "userPassword" : "' . $userPassword . '", "userGender" : "' . $userGender . '" }';
				}
			}
		}

		return $response;
	}



	//Function that checks whether a prize for an user has been given or not:
	function getPrizeGiven($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return FALSE; }

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		if ($userId === "" || !is_numeric($userId) || $userId <= 0) { return FALSE; }

		//Sends the query:
		$query = "SELECT user_id FROM winners_or_losers WHERE user_id = " . $userId . " AND prize_given != 0;";
		$results = $dataBase->query($query);

		return (mysqli_num_rows($results) > 0);
	}



	//Function that gets the selected shop for an user given:
	function getSelectedShop($userId)
	{
		global $dataBase;
		if (!isset($dataBase)) { return ""; }

		$response = ""; //If all goes well, it will return the user name.

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));

		//Sends the query:
		$query = "SELECT shop_selected FROM winners_or_losers WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the query went well, gets the id:
		if ($results && mysqli_num_rows($results) > 0)
		{
			$row = mysqli_fetch_assoc($results);
			$response = $row["shop_selected"];
		}

		return $response;
	}


	//Function that selects a shop for an user:
	function selectShop($userId, $shopSelected)
	{
		global $dataBase;

		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't select a shop for an invalid user."; }

		//If the user is not a winner or loser, exits:
		if (!isUserWinnerOrLoser($userId)) { return "Can't set a shop for a non winner or loser user."; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));
		$shopSelected = strtolower(trim(escapeStringMySQL($shopSelected)));

		//Sends the query:
		$query = "UPDATE winners_or_losers SET shop_selected = '" . $shopSelected . "' WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the city has not been inserted:
		if (!$results)
		{
			$response = "Unable to set a shop for the user!";
		}

		return $response;
	}


	//Function that marks a prize for an user as given:
	function setPrizeGiven($userId, $setPrizeGivenCode, $prizeGiven = true)
	{
		global $dataBase;

		if (!isset($dataBase)) { return "DataBase object not found!"; }

		//If the user id is not valid, exits:
		if (!userIdExists($userId)) { return "Can't set prize as given or not for an invalid user."; }

		//If the user is not a winner or loser, exits:
		if (!isUserWinnerOrLoser($userId)) { return "Can't set prize as given or not for a non winner or loser user."; }

		//If the code given is empty, exits:
		$setPrizeGivenCode = trim($setPrizeGivenCode);
		if ($setPrizeGivenCode === "") { return "Code to set prize as given is empty"; }

		//If the code given is not correct, exists:
		if ($setPrizeGivenCode !== PRIZE_GIVEN_PASSWORD) { return "Given code to mark the prize as given is not valid."; }

		$response = "";

		//Prevents mySQL injection:
		$userId = trim(escapeStringMySQL($userId));
		$prizeGiven = ($prizeGiven) ? '1' : '0';

		//Sends the query:
		$query = "UPDATE winners_or_losers SET prize_given = " . $prizeGiven . " WHERE user_id = " . $userId . ";";
		$results = $dataBase->query($query);

		//If the city has not been inserted:
		if (!$results)
		{
			$response = "Unable to set prize as given or not for the user!";
		}

		return $response;
	}


	
	//Function that returns a JSON object with a list of the winners:
	function getWinnersList()
	{
		global $prizeTypes;

		$response = "";

		//Gets the winners:
		$winners = getWinnersOrLosersByTime();


		//Prepares the information (in JSON object):
		$response = '[';

		//Loops through the winners:
		$winnersCounter = 0;
		foreach ($winners as $userId)
		{
			//Gets the user name:
			$userName = trim(getUserName($userId));
			
			//Gets information about the prize:
			$prizeType = getUserPrizeType($userId);
			$prizeName = localize($prizeType);

			$isFakeUser = FALSE;

			//Defines whether is a fake user or not:
			if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
			else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

			//If the user name is not empty and is not a fake user:
			if ($userName !== "" && !$isFakeUser)
			{
				//Hides some letters from the user name:
				for ($x = 3; $x < strlen($userName) - 4; $x++)
				{
					$userName[$x] = "*";
				}

				//Adds it to the array:
				$response .= '{ "userName" : "' . $userName . '", "prizeName" : "' . $prizeName . '" }, ';
				$winnersCounter++;
			}
		}

		//If there are less winners than the minimum requiered, uses fake winners:
		if ($winnersCounter < WINNERS_LIST_MINIMUM_PEOPLE)
		{
			//Creates an array with numeric indexes and prize types as values:
			$prizeTypesArray = Array();
			foreach ($prizeTypes as $prizeType => $array) { $prizeTypesArray[] = $prizeType; }
			//Creates fake winners and stores them on the response:
			do
			{
				$prizeName = localize($prizeTypesArray[rand(0, sizeof($prizeTypesArray) - 1)]);
				$userName = "139" . "****" . getRandomString(4, Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"));
				$response .= '{ "userName" : "' . $userName . '", "prizeName" : "' . $prizeName . '" }, ';
			} while ($winnersCounter++ < WINNERS_LIST_MINIMUM_PEOPLE);
		}

		//Deletes the trailing comma:
		$response = rtrim($response, ", ");

		$response .= "]";

		//Returns the information:
		return $response;
	}


	//Function that returns the total people in the queue (total or only real without counting fake ones):
	function getTotalUsers($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_name from users";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " WHERE user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = $row["user_name"];

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}



	//Function that returns the total people in the queue (total or only real without counting fake ones):
	function getTotalInQueues($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_name from users WHERE user_id NOT IN (SELECT user_id FROM finalists) AND user_id NOT IN (SELECT user_id FROM winners_or_losers)";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = $row["user_name"];

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}
		

	//Function that returns the total people in the lottery room (total or only real without counting fake ones):
	function getTotalInLotteryRoom($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM finalists WHERE user_id NOT IN (SELECT user_id FROM winners_or_losers)";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = getUserName($row["user_id"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}


	//Function that returns the total people in the lottery room without lottery result (total or only real without counting fake ones):
	function getTotalInLotteryRoomWithoutResult($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM finalists WHERE user_id NOT IN (SELECT user_id FROM winners_or_losers) AND lottery_result IS NULL";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = getUserName($row["user_id"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}


	//Function that returns the total singles in the lottery room (total or only real without counting fake ones):
	function getTotalInLotteryRoomSingle($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM finalists WHERE user_id NOT IN (SELECT user_id FROM winners_or_losers) AND user_id NOT IN (SELECT user1_id FROM couples) and user_id NOT IN (SELECT user2_id FROM couples)";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = getUserName($row["user_id"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}



	//Function that returns the total number of winners (total or only real without counting fake ones):
	function getTotalWinners($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM winners_or_losers WHERE winner != 0";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = getUserName($row["user_id"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}


	//Function that returns the total number of losers (total or only real without counting fake ones):
	function getTotalLosers($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id FROM winners_or_losers WHERE winner = 0";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = getUserName($row["user_id"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}



	//Function that returns the total number of people without any invitation accepted (they are not host):
	function getTotalWithoutGuests($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_name FROM users WHERE user_id NOT IN (SELECT host_user_id FROM invitations GROUP BY host_user_id)";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		//Counts the number of results:
		if (!$noFakePeople)
		{
			$response = mysqli_num_rows($results);
		}
		//...otherwise, counts only no fake people:
		else
		{
			if ($results && mysqli_num_rows($results) > 0)
			{
				while ($row = mysqli_fetch_assoc($results))
				{
					$userName = trim($row["user_name"]);

					$isFakeUser = FALSE;

					//Defines whether is a fake user or not:
					if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
					else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

					//If the user name is not empty and is not a fake user:
					if ($userName !== "" && !$isFakeUser) { $response++; }
				}
			}
		}

		return $response;
	}



	//Function that calculates the average of invitations sent by the people who has been in lottery room (including winners and losers):
	function invitationsSentAverageToBeFinalist($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id, user_name FROM users WHERE (user_id IN (SELECT user_id FROM finalists) OR user_id IN (SELECT user_id FROM winners_or_losers))";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		$users = 0;
		$invitations = 0;
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$userId = $row["user_id"];
				$userName = trim($row["user_name"]);

				$isFakeUser = FALSE;

				//Defines whether is a fake user or not:
				if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
				else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

				//If we allow fake people or the user name is not empty and is not a fake user:
				if (!$noFakePeople || $userName !== "" && !$isFakeUser)
				{
					$users++;
					$invitations += sizeof(getUserGuests($userId));
				}
			}
		}

		if ($users > 0) { $response = $invitations / $users; }

		return number_format($response, 2);
	}


	//Function that calculates the average of invitations sent by the people who is still in the queue:
	function invitationsSentAverageInQueue($noFakePeople = FALSE, $gender = "all")
	{
		global $dataBase;
		if (!isset($dataBase)) { return 0; }

		$response = 0;

		//Sets the query (maybe it is partial):
		$query = "SELECT user_id, user_name FROM users WHERE user_id NOT IN (SELECT user_id FROM finalists) AND user_id NOT IN (SELECT user_id FROM winners_or_losers)";

		$gender = strtolower(trim(escapeStringMySQL($gender)));
		if ($gender === "male" || $gender === "female")
		{
			$query .= " AND user_id IN (SELECT user_id FROM users WHERE user_gender = '" . $gender . "')";
		}

		//Sends the query:
		$results = $dataBase->query($query);

		$users = 0;
		$invitations = 0;
		if ($results && mysqli_num_rows($results) > 0)
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				$userId = $row["user_id"];
				$userName = $row["user_name"];

				$isFakeUser = FALSE;

				//Defines whether is a fake user or not:
				if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_MALE)) === FAKE_USERS_PREFIX_MALE) { $isFakeUser = TRUE; }
				else if (substr($userName, 0, strlen(FAKE_USERS_PREFIX_FEMALE)) === FAKE_USERS_PREFIX_FEMALE) { $isFakeUser = TRUE; }

				//If we allow fake people or the user name is not empty and is not a fake user:
				if (!$noFakePeople || $userName !== "" && !$isFakeUser)
				{
					$users++;
					$invitations += sizeof(getUserGuests($userId));
				}
			}
		}

		if ($users > 0) { $response = $invitations / $users; }

		return number_format($response, 2);
	}


	//Function that returns a JSON object with the prize types and the totally won for each type:
	function getTotalPrizesWon()
	{
		global $prizeTypes;

		$response = "<br />{ ";

		foreach ($prizeTypes as $prizeType => $array)
		{
			$response .= '<br />"' . localize($prizeType) . ' (' . $prizeType . ')" : ' . getTotalPrizeTypeWon($prizeType) . ', ';
		}

		//Deletes the trailing comma:
		$response = rtrim($response, ", ");

		$response .= "<br /> }";

		return $response;
	}



	//Function that makes a user go to the lottery room:
	function goToLotteryRoom($userId, $deleteFromWinners = TRUE, $givePartner = FALSE)
	{
		$response = "";

		//Deletes the user from winners (just in case):
		if ($deleteFromWinners) { $response = trim(deleteUserFromWinnersOrLosers($userId)); }

		//If the user has been deleted from winners (or it was never there):
		if ($response === "")
		{
			//Inserts user as finalist (if the user is already finalist, it will not do anything):
			$response = insertUserFinalist($userId);

			//If the user was inserted as a finalist, provides him/her a partner:
			if ($response === "")
			{
				if ($givePartner) { giveUserAPartner($userId); }
			}
		}

		return $response;
	}





/*
if (isset($_GET["prueba"]))
{
if (file_exists("db.php")) { require_once "db.php"; }
else { require_once "php/db.php"; }
if (file_exists("../config.php")) { require_once "../config.php"; }
else { require_once "config.php"; }

//Connects to the database:
$dataBase = new DataBase();
$dataBase->connect(DB_HOST, DB_USER, DB_PASSWORD);
$dataBase->select(DB_NAME);


}
*/

//NOTA: MIRAR LOS RETURNS QUE DEVUELVEN OTRA COSA!!!