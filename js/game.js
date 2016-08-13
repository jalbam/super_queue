// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Global constants (NOTE: don't touch this, PHP will change it!):
var AVOID_QUEUE = false;
var AVOID_QUEUE_GUESTS_NEEDED = 0;
var MAXIMUM_PEOPLE_PER_QUEUE = 30;
var GAME_LOOP_MS = 16; //Milliseconds after the GameLoop function will be called again.
var GET_DATA_MS = 3000; //Milliseconds after the getGameData function will be called again.
var GET_WINNERS_MS = 30000; //Milliseconds after the getWinners function will be called again.
var SHOW_WINNERS_MS = 3000; //Milliseconds after the getWinners function will be called again.
var WECHAT_SHOW_SHARE_LINK = false; //Tells whether show the share link on Wechat or not.


//Global variables:
var userId = 0;
var gameLoopTimeout; //Timeout that does the game loop.
var getGameDataTimeout; //Timeout that gets all data needed from the user.
var getWinnersTimeout; //Timeout that gets all data needed from the user.
var showWinnersTimeout; //Timeout that gets all data needed from the user.
var gameData; //Stores the data needed for the game.
var winnersList; //Stores the the winners list.
var showingAnimation = false; //Tells whether an animation is being showed or not.
var screenCurrent = ""; //Tells the current screen showing.
var alreadyJumpedToLotteryRoom = false; //Defines if the game has already jumped to the lottery room.


//Function that starts the game:
function startGame(userNameLocal, userPasswordLocal)
{
	//If it is in debug mode:
	if (DEBUG_MODE)
	{
		//Displays game state in real time:
		DEBUG_displayGameState(true);

		//Displays the God controls:
		showElement("DEBUG_game_god_controls");

		//Displays the controls to hide or show debug boxes:
		showElement("DEBUG_showHideboxes");
	}

	showWaitMessage(localize("LOADING"));

	//Gets the id of the user:
	var variables = "action=getuserid";
	variables += "&user_name=" + encodeURLValue(userNameLocal);
	variables += "&user_password=" + encodeURLValue(userPasswordLocal);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = parseInt(trim(XHR.responseText));
		//If login (and register if needed) is correct, will start the game:
		if (!isNaN(response) && response > 0)
		{
			//Sets the response as the user id:
			//userId = response;
			changeUserId(response);

			//Since they are valid, sets global variables with the value of local ones:
			userName = userNameLocal;
			userPassword = userPasswordLocal;

			//Starts getting the data:
			getGameData();

			//Starts the game loop:
			gameLoop();

			//If we want to avoid the queue, we do it:
			if (AVOID_QUEUE && AVOID_QUEUE_GUESTS_NEEDED === 0 && !alreadyJumpedToLotteryRoom)
			{
				jumpToLotteryRoom();
				alreadyJumpedToLotteryRoom = true;
			}
		}
		//...otherwise, shows the error and the registration form again:
		else
		{
			if (DEBUG_MODE) { showError(localize("START_GAME_FAILED") + ":<br />" + response); }
			registrationFormShow();
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and the registration form again:
		if (DEBUG_MODE) { showError(localize("START_GAME_FAILED") + ":<br />" + response); }
		registrationFormShow();
	}
	
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that does the game loop:
var winnerScreenJumpedOnce = false;
var loserScreenJumpedOnce = false;
var queueScreenBefore = false;
function gameLoop()
{
	//If there is an animation in the game, will call the function again later and exits:
	if (showingAnimation) { gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS); return; }

	//If we already have the game data:
	if (gameDataValid(gameData))
	{
		//If the user has played but lost, shows the loser screen:
		if (gameData.isLoser)
		{
			//If it is the first time, we will jump this time (to let the lottery room represent an animation):
			if (loserScreenJumpedOnce === false)
			{
				loserScreenJumpedOnce = true;
				showLotteryRoomScreen(); //Shows the lottery room again.
				gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS);
				return;
			}

			//Hides any instructions:
			hideElement("lottery_room_instructions");
			hideElement("queue_instructions");

			//Shows the loser screen:
			showLoserScreen();

			//We don't need the game data anymore (unless we are in debug mode):
			if (!DEBUG_MODE) { clearTimeout(getGameDataTimeout); }

			//We don't need the winners list anymore (in debug mode):
			if (!DEBUG_MODE)
			{
				clearTimeout(getWinnersTimeout);
				clearTimeout(showWinnersTimeout);
			}
		}
		//...otherwise, if the user has already won, shows the congratulation screen:
		else if (gameData.isWinner)
		{
			//If it is the first time, we will jump this time (to let the lottery room represent an animation):
			if (winnerScreenJumpedOnce === false)
			{
				winnerScreenJumpedOnce = true;
				showLotteryRoomScreen(); //Shows the lottery room again.
				gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS);
				return;
			}

			//Hides any instructions:
			hideElement("lottery_room_instructions");
			hideElement("queue_instructions");

			//Shows the winner screen:
			showWinnerScreen();

			//We don't need the game data anymore (unless we are in debug mode):
			if (!DEBUG_MODE) { clearTimeout(getGameDataTimeout); }

			//We don't need the winners list anymore (in debug mode):
			if (!DEBUG_MODE)
			{
				clearTimeout(getWinnersTimeout);
				clearTimeout(showWinnersTimeout);
			}
		}
		//...otherwise, if the gamer has finished, shows the message of Game End:
		else if (gameData.isGameFinished || GAME_FINISHED)
		{
			//Shows the game finished screen:
			showGameFinishedScreen();
			
			//We don't need the game data anymore (unless we are in debug mode):
			if (!DEBUG_MODE) { clearTimeout(getGameDataTimeout); }

			//We don't need the winners list anymore (in debug mode):
			if (!DEBUG_MODE)
			{
				clearTimeout(getWinnersTimeout);
				clearTimeout(showWinnersTimeout);
			}
		}
		//...otherwise, if the user if finalist (has entered through the door), shows the lottery room:
		else if (gameData.isFinalist)
		{
			//If we were showing queue screen just before:
			if (queueScreenBefore === true)
			{
				//Shows the queue screen one loop more (to let show the animation):
				showQueueScreen();
				queueScreenBefore = false;
				gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS);

				//We don't need the winners list anymore (in debug mode):
				if (!DEBUG_MODE)
				{
					clearTimeout(getWinnersTimeout);
					clearTimeout(showWinnersTimeout);
				}

				return;
			}

			showLotteryRoomScreen();
			//The function calls itself after some time:
			gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS);
		}
		//...otherwise, shows the queue which is in the world of the user:
		else
		{
			queueScreenBefore = true;

			showQueueScreen();

			//If we want to avoid the queue, we do it:
			if (AVOID_QUEUE && AVOID_QUEUE_GUESTS_NEEDED > 0 && !alreadyJumpedToLotteryRoom && gameData.guests >= AVOID_QUEUE_GUESTS_NEEDED)
			{
				jumpToLotteryRoom();
				alreadyJumpedToLotteryRoom = true;
			}

			//The function calls itself after some time:
			gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS);
		}
	}
	//...otherwise, if the data is not valid, it will try it again later:
	else { gameLoopTimeout = setTimeoutSynchronized(gameLoop, GAME_LOOP_MS); }
}


//Function that gets all data needed:
function getGameData(ignoreAnimation)
{
	if (typeof(ignoreAnimation) === "undefined" || ignoreAnimation === null) { ignoreAnimation = false; }

	//If there is an animation in the game, will call the function again later and exits:
	if (showingAnimation && !ignoreAnimation) { getGameDataTimeout = setTimeoutSynchronized(getGameData, GET_DATA_MS); return; }

	//Gets the data of the user:
	var variables = "action=getdata";
	variables += "&user_id=" + encodeURLValue(userId);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		//Hides any previous error message (if any):
		//showError("");

		var response = trim(XHR.responseText);

		//If the data is not an empty string, it should be a JSON object:
		if (response !== "")
		{
			try
			{
				//alert(response);
				eval("var gameDataTemp = " + response);
				//If the data retrieved is valid, we save it in the final variable:
				if (gameDataValid(gameDataTemp))
				{
					gameData = gameDataTemp;

					//In debug mode:
					if (DEBUG_MODE)
					{
						//We finish the game or not depending on what we want:
						gameData.isGameFinished = DEBUG_gameFinished;

						//Lets change the queue order with the input text 
						if (document.getElementById("DEBUG_new_ranking_male_apply").checked)
						{
							var newMaleRanking = trim(document.getElementById("DEBUG_new_ranking_male").value);
							if (newMaleRanking !== "") { eval("gameData.ranking.male = [ "  + newMaleRanking + " ];"); }
						}
						if (document.getElementById("DEBUG_new_ranking_female_apply").checked)
						{
							var newFemaleRanking = trim(document.getElementById("DEBUG_new_ranking_female").value);
							if (newFemaleRanking !== "") { eval("gameData.ranking.female = [ "  + newFemaleRanking + " ];"); }
						}
					}

					//eval("gameData = " + response);
				} else if (DEBUG_MODE) { showError(localize("DATA_IS_NOT_VALID") + ": " + response); }
			} catch(e) { if (DEBUG_MODE) { showError(localize("ERROR_PARSING_DATA") + ": " + e + " [" + response + "]"); } }
		} else if (DEBUG_MODE) { showError(localize("GET_DATA_EMPTY")); }

		//In debug mode, displays the game data:
		if (DEBUG_MODE && gameDataValid(gameData)) { DEBUG_displayGameData(gameData); }

		//Calls the function again:
		getGameDataTimeout = setTimeoutSynchronized(getGameData, GET_DATA_MS);
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		
		//Shows the error in debug mode:
		if (DEBUG_MODE) { showError(localize("GET_DATA_FAILED") + ":<br />" + response); }

		//Calls the function again:
		getGameDataTimeout = setTimeoutSynchronized(getGameData, GET_DATA_MS);
	}

	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that checks whether the game data retrieved is valid:
function gameDataValid(gameData)
{
	//TODO: check all properties!!!
	//return true;
	return (typeof(gameData) !== "undefined" && gameData !== null && typeof(gameData.gender) !== "undefined" && (gameData.gender === "male" || gameData.gender === "female"));
}


//Function that gets the winners list:
function getWinners(ignoreAnimation)
{
	if (typeof(ignoreAnimation) === "undefined" || ignoreAnimation === null) { ignoreAnimation = true; }

	//If there is an animation in the game, will call the function again later and exits:
	if (showingAnimation && !ignoreAnimation) { getWinnersTimeout = setTimeoutSynchronized(getWinners, GET_WINNERS_MS); return; }

	//Gets the data of the user:
	var variables = "action=getwinnerslist";
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText);

		//If the data is not an empty string, it should be a JSON object:
		if (response !== "")
		{
			try
			{
				//alert(response);
				eval("var winnersListTemp = " + response);
				//If the data retrieved is valid, we save it in the final variable:
				if (winnersListValid(winnersListTemp))
				{
					winnersList = winnersListTemp;
				} else if (DEBUG_MODE) { showError(localize("DATA_IS_NOT_VALID") + ": " + response); }
			} catch(e) { if (DEBUG_MODE) { showError(localize("ERROR_PARSING_DATA") + ": " + e + " [" + response + "]"); } }
		} else if (DEBUG_MODE) { showError(localize("GET_DATA_EMPTY")); }

		//Calls the function again:
		getWinnersTimeout = setTimeoutSynchronized(getWinners, GET_WINNERS_MS);
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		
		//Shows the error in debug mode:
		if (DEBUG_MODE) { showError(localize("GET_DATA_FAILED") + ":<br />" + response); }

		//Calls the function again:
		getWinnersTimeout = setTimeoutSynchronized(getWinners, GET_WINNERS_MS);
	}

	CB_XHRForm("php/controller.php?using_ajax=yes&language=" + languageCurrent, variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that checks whether the game data retrieved is valid:
function winnersListValid(winnersList)
{
	//TODO: check all properties!!!
	//return true;
	return (typeof(winnersList) !== "undefined" && winnersList !== null && winnersList.length > 0);
}


//Function that shows the winners list:
var winnersFirstIndexPrevious = 0;
var winnersPeopleOnList = 3; //Number of people on the list.
function showWinners()
{
	//If the winners list is valid:
	if (typeof(winnersList) !== "undefined" && winnersListValid(winnersList))
	{
		//If the previous index overcomes the size of list, goes back to the beginning:
		if (winnersFirstIndexPrevious >= winnersList.length) { winnersFirstIndexPrevious = 0; }

		//Loops through the winners list:
		var code = "";
		for (var x = winnersFirstIndexPrevious; x < winnersFirstIndexPrevious + winnersPeopleOnList; x++)
		{
			if (winnersFirstIndexPrevious < winnersList.length && typeof(winnersList[x]) !== "undefined")
			{
				//code += '<span class="winners_list_name">' + winnersList[x].userName + '</span> &rarr; <span class="winners_list_prize_name">' + winnersList[x].prizeName + '</span><br />';
				code += '<table style="border:0px; padding:1px; margin:0px;"><tr><td class=""><span class="winners_list_name">' + winnersList[x].userName + '</span></td><td>&rarr;</td><td><span class="winners_list_prize_name">' + winnersList[x].prizeName + '</span></td></tr></table>';
			}
		}

		winnersFirstIndexPrevious = x;

		//Shows the list (if any):
		if (code !== "") { showMessage(code, "winners_list_message", "winners_list"); }
		//...if there is no list:
		else
		{
			if (DEBUG_MODE) { showMessage(localize("WINNERS_LIST_EMPTY"), "winners_list_message", "winners_list"); }
			else { hideElement("winners_list"); }
		}
	}
	//...otherwise, if the winners list is not valid:
	else
	{
		if (DEBUG_MODE) { showMessage(localize("WINNERS_LIST_EMPTY"), "winners_list_message", "winners_list"); }
		else { hideElement("winners_list"); }
	}

	//Calls itself again:
	showWinnersTimeout = setTimeoutSynchronized(showWinners, SHOW_WINNERS_MS);
}


//Function that shows a desired screen:
var showScreenTimeout;
var showScreenContainerTimeout;
function showScreen(screenName, noHideOthers)
{
	if (typeof(noHideOthers) === "undefined" || noHideOthers === null) { noHideOthers = false; }

	clearTimeout(showScreenTimeout);
	clearTimeout(showScreenContainerTimeout);

	//Array with the id of the screen elements:
	var screenElementsId = [ "queue_screen", "game_finished_screen", "lottery_room_screen", "loser_screen", "winner_screen" ];
	
	//Loops through the elements:
	var screenElementsIdLength = screenElementsId.length;
	var screenShowing = false; //Tells whether any screen elements are to be shown or not.
	var screenElement;
	for (var x = 0; x < screenElementsIdLength; x++)
	{
		//If we want to show this element, we show it:
		if (screenElementsId[x] === screenName)
		{
			screenElement = document.getElementById(screenElementsId[x]);
			if (screenElement !== null)
			{
				screenElement.className = "screen_hidden";
				showElement(screenElementsId[x]);
				showScreenTimeout = setTimeout(
												function()
												{
													screenElement.className = "";
												}, 100);

				screenShowing = true;

				screenCurrent = screenName;
			}
		}
		//...otherwise, we hide the element (if we want to hide others):
		else if (!noHideOthers) { hideElement(screenElementsId[x]); }
	}

	//If any screen element is showing, shows their container and resizes all:
	if (screenShowing === true)
	{
		var screenContainer = document.getElementById("screens_container");
		if (screenContainer !== null)
		{
			screenContainer.className = "screen_hidden";
			showElement("screens_container");
			showScreenContainerTimeout = setTimeout(function() { screenContainer.className = ""; }, 100);
		}
		resizeAll();
	}
	//...otherwise, hides the container:
	else
	{
		hideElement("screens_container");
	}

	//Resets all variables as it was the first time:
	firstTimeShowingLotteryRoom = true;
	firstTimeShowingQueue = true;
}



//Function that changes the current user id:
function changeUserId(newUserId)
{
	//If the user id is valid:
	if (typeof(newUserId) !== "undefined" && newUserId !== null && !isNaN(newUserId) && newUserId > 0)
	{
		//Changes the user id to use the new one:
		userId = newUserId;

		//Updates share link (after a while, to make sure the Wechat bridge has been loaded):
		setTimeout(updateShareLink, 5000);

		//Update Wechat sharing options:
		WECHAT_updateSharingOptions();
	}
}


//Function that updates the share link:
function updateShareLink()
{
	//Updates share link (if we are not on Wechat or we allow show it on Wechat):
	if (!WECHAT_usingWechat() || WECHAT_SHOW_SHARE_LINK)
	{
		showMessage(localize("SHARE_LINK") + ': <span class="share_link_url">' + getShareLink() + '</span>', 'share_link');
	} else { showMessage("", "share_link"); }
}


//Function that updates the Wechat sharing options:
function WECHAT_updateSharingOptions()
{
	if (!WECHAT_usingWechat() || !WECHAT_isBridgeReady) { return; }

	//Sets the current URL (without URL parameters):
	var currentURLWithoutVars = getCurrentURLWithoutVars().replace("index.php", "");

	//If the URL contains "weixin:" then uses the SITE_URL:
	//if (CB_indexOf(currentURLWithoutVars, "weixin:") !=== -1) { currentURLWithoutVars = SITE_URL; }

	//Sets the URL of the share image (being careful preventing double slashes):
	var imageURL = currentURLWithoutVars + WECHAT_SHARE_IMAGE_URL;
	if (currentURLWithoutVars.substr(currentURLWithoutVars.length - 1) !== "/")
	{
		imageURL = currentURLWithoutVars + "/" + WECHAT_SHARE_IMAGE_URL;
	}

	WECHAT_onBridgeReady(getShareLink(), imageURL, localize("WECHAT_SHARE_TITLE"), localize("WECHAT_SHARE_DESCRIPTION"), WECHAT_APPID);
}


//Function that returns the current URL without URL variables:
function getCurrentURLWithoutVars()
{
	var beginningVars = CB_indexOf(location.href, "?");
	if (beginningVars === -1) { beginningVars = location.href.length; }
	return location.href.slice(0, beginningVars);
}


//Function that gets the share link:
function getShareLink()
{
	var currentURLWithoutVars = getCurrentURLWithoutVars().replace("index.php", "");
	if (currentURLWithoutVars.substr(currentURLWithoutVars.length - 1) !== "/")
	{
		currentURLWithoutVars = currentURLWithoutVars + "/";
	}
	
	var shareLink = currentURLWithoutVars + "?user_host=" + userId;

	//if (WECHAT_INFO_DETECTOR_ENABLED && WECHAT_usingWechat() && WECHAT_CODE !== "" && WECHAT_APPID !== "" && WECHAT_APPSECRET !== "" && userKeyword !== "")
	if (WECHAT_INFO_DETECTOR_ENABLED && WECHAT_usingWechat() && WECHAT_APPID !== "" && WECHAT_APPSECRET !== "")
	{
		//shareLink = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + WECHAT_APPID + '&redirect_uri=' + encodeURLValue(shareLink) + '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		shareLink = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + WECHAT_APPID + '&redirect_uri=' + encodeURLValue(shareLink) + '&response_type=code&scope=snsapi_userinfo&state=NO';
	}

	return shareLink;
}


//Function to jump directly to the lottery room (if the option of avoid the queue is enabled):
function jumpToLotteryRoom()
{
	if (!AVOID_QUEUE) { return; }

	if (DEBUG_MODE) { showError(""); }

	var variables = "action=jumptolotteryroom";
	variables += "&user_id=" + encodeURLValue(userId);
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText).toUpperCase();
		//If the request has not been processed corretly:
		if (response !== "")
		{
			//Shows the error and the server response or error in debug mode:
			if (DEBUG_MODE) { showError(localize("ERROR_JUMPING_TO_LOTTERY_ROOM") + ":<br />" + response); }
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("ERROR_JUMPING_TO_LOTTERY_ROOM") + ":<br />" + response); }
	}
	
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}
