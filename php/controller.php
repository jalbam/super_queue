<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado

    //Permite una demora de carga "infinita":
    if (!ini_get("safe_mode")) { @set_time_limit(0); }


	//Includes required files:
	if (file_exists("db.php")) { require_once "db.php"; }
	else { require_once "php/db.php"; }
	if (file_exists("operations.php")) { require_once "operations.php"; }
	else { require_once "php/operations.php"; }
	if (file_exists("../functions.php")) { require_once "../functions.php"; }
	else { require_once "functions.php"; }
	if (file_exists("../config.php")) { require_once "../config.php"; }
	else { require_once "config.php"; }
	if (DEBUG_MODE)
	{
		if (file_exists("debug.php")) { require_once "debug.php"; }
		else { require_once "php/debug.php"; }
	}
	
	//If it is not using AJAX, exits:
	$usingAjax = strtolower(getGet("using_ajax"));
	if (!DEBUG_MODE && $usingAjax !== "yes") { exit(); }

	//Gets the action requested:
	$action = strtolower(getVar("action"));

	//If there is no operation requested, exits:
	if ($action === "") { exit(); }

	//Lists allowed requests:
	$actionsAllowed = Array(
								"getuserbykeyword", "loginregisteruser", "getuserid", "getdata",
								"playlottery", "getprizeinformation", "selectshop", "markprizeasgiven",
								"getwinnerslist", "jumptolotteryroom"
							);
	$actionsAllowedDebug = Array(
									"getaguest", "loseaguest", "gotolotteryroom", "getapartner",
									"losepartner", "gotoqueue", "becomewinner", "becomeloser",
									"insertnewuserqueue", "givepartnerlotteryresult", "getworlds",
									"getoneuserworld", "winprize", "givepartnertoeveryone"
								);


	//If it is an allowed operation:
	if (in_array($action, $actionsAllowed) || DEBUG_MODE && in_array($action, $actionsAllowedDebug))
	{
		$operationAccepted = FALSE;
	
		//Checks whether we have all needed parameters for known operations:
		if ($action === "getwinnerslist" || DEBUG_MODE && $action === "givepartnertoeveryone")
		{
			$operationAccepted = TRUE; //These actions doesn't need parameters!
		}
		else if (DEBUG_MODE && $action === "getoneuserworld")
		{
			$world = getVar("world");
			if ($world !== "" && is_numeric($world) && $world >= 0) { $operationAccepted = TRUE; }			
		}
		else if ($action === "jumptolotteryroom" || $action === "markprizeasgiven" || $action === "getdata" || $action === "playlottery" || DEBUG_MODE && in_array($action, $actionsAllowedDebug))
		{
			$userId = getVar("user_id");
			if ($action === "getworlds" || $userId !== "" && is_numeric($userId) && $userId > 0) { $operationAccepted = TRUE; }
			if ($action === "insertnewuserqueue" && $operationAccepted)
			{
				$gender = strtolower(getVar("gender"));
				if ($gender !== "male" && $gender !== "female") { $operationAccepted = FALSE; }
			}
			else if ($action === "winprize" && $operationAccepted)
			{
				$prizeType = strtolower(getVar("prize_type"));
				if ($prizeType === "") { $operationAccepted = FALSE; }
			}
			else if ($action === "markprizeasgiven" && $operationAccepted)
			{
				$prizeGivenCode = getVar("prize_given_code");
				if ($prizeGivenCode === "") { $operationAccepted = FALSE; }
			}
			else if ($action === "jumptolotteryroom")
			{
				if (!AVOID_QUEUE) { $operationAccepted = FALSE; }
			}
		}
		else if ($action === "loginregisteruser")
		{
			$userName = getVar("user_name");
			$userPassword = getVar("user_password");
			$userGender = getVar("user_gender");
			$userKeyword = getVar("user_keyword");
			$userHost = getVar("user_host");

			if ($userName !== "" && $userPassword !== "" && $userGender !== "") { $operationAccepted = TRUE; }
		}
		else if ($action === "getuserid")
		{
			$userName = getVar("user_name");
			$userPassword = getVar("user_password");

			if ($userName !== "" && $userPassword !== "") { $operationAccepted = TRUE; }
		}
		else if ($action === "getprizeinformation")
		{
			$userId = getVar("user_id");
			$userName = getVar("user_name");
			$userPassword = getVar("user_password");
			if ($userId !== "" && is_numeric($userId) && $userId > 0 && $userName !== "" && $userPassword !== "") { $operationAccepted = TRUE; }
		}
		else if ($action === "getuserbykeyword")
		{
			$userKeyword = getVar("user_keyword");

			if ($userKeyword !== "") { $operationAccepted = TRUE; }
		}
		else if ($action === "selectshop")
		{
			$userId = getVar("user_id");
			$shopSelected = getVar("shop_selected");

			if ($userId !== "" && is_numeric($userId) && $userId > 0 && $shopSelected !== "") { $operationAccepted = TRUE; }
		}

		//If the operation is accepted (we have all the needed parameters):
		if ($operationAccepted)
		{
			//Connects to the database:
			$dataBase = new DataBase();
			$dataBase->connect(DB_HOST, DB_USER, DB_PASSWORD);
			$dataBase->select(DB_NAME);

			//If we want to get the data:
			if ($action === "getdata")
			{
				$response = trim(getData($userId));
				echo $response;
			}
			//...otherwise, if we want to get the list of the winners:
			else if ($action === "getwinnerslist")
			{
				$response = trim(getWinnersList());
				echo $response;
			}
			//...otherwise, if we want to login the user, we do it (will register the user if doesn't exist):
			else if ($action === "loginregisteruser")
			{
				$response = trim(loginRegisterUser($userName, $userPassword, $userGender, $userKeyword, $userHost));
				if ($response === "") { echo "OK"; } //Returns OK if all goes well.
				else { echo $response; }
			}
			//...otherwise, if we want to retrieve the user id:
			else if ($action === "getuserid")
			{
				$response = trim(getUserId($userName, $userPassword));
				echo $response; //Returns the ID of the user if all goes well.
			}
			//...otherwise, if we want to play the lottery:
			else if ($action === "playlottery")
			{
				$response = trim(playLottery($userId));
				echo $response;
			}
			//...otherwise, if we want to get prize information:
			else if ($action === "getprizeinformation")
			{
				$response = trim(getPrizeInformation($userId, $userName, $userPassword));
				echo $response;
			}
			//...otherwise, if we want to get user name, password and gender through the keyword:
			else if ($action === "getuserbykeyword")
			{
				$response = trim(getUserByKeyword($userKeyword));
				echo $response;
			}
			//...otherwise, if we want to select a shop:
			else if ($action === "selectshop")
			{
				$response = trim(selectShop($userId, $shopSelected));
				echo $response;
			}
			//...otherwise, if we want to mark the prize of an user as given:
			else if ($action === "markprizeasgiven")
			{
				$response = trim(setPrizeGiven($userId, $prizeGivenCode, true));
				echo $response;
			}
			else if ($action === "jumptolotteryroom")
			{
				//Doesn't delete winners or losers and gives a partner when enter the lottery room:
				$response = trim(goToLotteryRoom($userId, FALSE, TRUE));
				echo $response;
			}
			//...otherwise, if we are in debug mode:
			else if (DEBUG_MODE && in_array($action, $actionsAllowedDebug))
			{
				//If we want to get a guest for the user given:
				if ($action === "getaguest")
				{
					$response = trim(DEBUG_getAGuest($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to delete a guest for the user given:
				else if ($action === "loseaguest")
				{
					$response = trim(DEBUG_loseAGuest($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the user given to enter the lottery room:
				else if ($action === "gotolotteryroom")
				{
					$response = trim(DEBUG_goToLotteryRoom($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the user given to get a partner:
				else if ($action === "getapartner")
				{
					$response = trim(DEBUG_getAPartner($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the user given to lose the partner:
				else if ($action === "losepartner")
				{
					$response = trim(DEBUG_losePartner($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the user given to go to the queue:
				else if ($action === "gotoqueue")
				{
					$response = trim(DEBUG_goToQueue($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the make the user given win:
				else if ($action === "becomewinner")
				{
					$response = trim(DEBUG_becomeWinner($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want the make the user given lose:
				else if ($action === "becomeloser")
				{
					$response = trim(DEBUG_becomeLoser($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to insert a new user in the queue of the user:
				else if ($action === "insertnewuserqueue")
				{
					$response = trim(DEBUG_insertNewUserQueue($userId, $gender));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to give a lottery result to the user's partner:
				else if ($action === "givepartnerlotteryresult")
				{
					$response = trim(DEBUG_givePartnerLotteryResult($userId));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to give the user a prize:
				else if ($action === "winprize")
				{
					$response = trim(DEBUG_winPrize($userId, $prizeType));
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to give a partner to everyone in the lottery room:
				else if ($action === "givepartnertoeveryone")
				{
					$response = trim(DEBUG_givePartnerToEveryone());
					if ($response === "") { echo "OK"; } //Returns OK if all goes well.
					else { echo $response; }
				}
				//...otherwise, if we want to get a list of the existing worlds:
				else if ($action === "getworlds")
				{
					echo trim(DEBUG_getListWorlds());
				}
				//...otherwise, if we want to get an existing user id of a given world:
				else if ($action === "getoneuserworld")
				{
					echo trim(DEBUG_getOneUserWorld($world));
				}
			}

			//Disconnects from the database:
			$dataBase->disconnect();
		}
		else { echo localize("CONTROLLER_OPERATION_KNOWN_NOT_ACCEPTED:"); }
	}
	else { echo localize("CONTROLLER_OPERATION_UNKNOWN"); }