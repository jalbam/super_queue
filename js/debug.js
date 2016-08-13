// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado



//Function that displays game data:
var gameDataPrevious;
var DEBUG_userInfoShow = false;
var DEBUG_worldInfoShow = false;
var DEBUG_gameInfoShow = true;
var DEBUG_prizesInfoShow = true;
var DEBUG_displayGameDataFirstTime = true;
function DEBUG_displayGameData(gameData)
{
	if (gameData === gameDataPrevious) { return; }

	if (DEBUG_displayGameDataFirstTime === true)
	{
		DEBUG_displayGameDataFirstTime = false;

		var userInfoStyle = "visibility:hidden; display:none;";
		if (DEBUG_userInfoShow) { userInfoStyle = "visibility:visible; display:block;"; }

		var worldInfoStyle = "visibility:hidden; display:none;";
		if (DEBUG_worldInfoShow) { worldInfoStyle = "visibility:visible; display:block;"; }

		var gameInfoStyle = "visibility:hidden; display:none;";
		if (DEBUG_gameInfoShow) { gameInfoStyle = "visibility:visible; display:block;"; }

		var prizesInfoStyle = "visibility:hidden; display:none;";
		if (DEBUG_prizesInfoShow) { prizesInfoStyle = "visibility:visible; display:block;"; }

		var message = "<br /><center>[ Game data ]</center><br />";
		message += '<div class="DEBUG_game_data_option" onClick="DEBUG_userInfoShow = !DEBUG_userInfoShow; if (DEBUG_userInfoShow) { hideElement(\'DEBUG_userInfo\'); } else { showElement(\'DEBUG_userInfo\'); }">User info</div><div id="DEBUG_userInfo" style="' + userInfoStyle + '"></div>';
		message += '<div class="DEBUG_game_data_option" onClick="DEBUG_worldInfoShow = !DEBUG_worldInfoShow; if (DEBUG_worldInfoShow) { hideElement(\'DEBUG_worldInfo\'); } else { showElement(\'DEBUG_worldInfo\'); }">World info</div><div id="DEBUG_worldInfo" style="' + worldInfoStyle + '"></div>';
		message += '<div class="DEBUG_game_data_option" onClick="DEBUG_gameInfoShow = !DEBUG_gameInfoShow; if (DEBUG_gameInfoShow) { hideElement(\'DEBUG_gameInfo\'); } else { showElement(\'DEBUG_gameInfo\'); }">Game info</div><div id="DEBUG_gameInfo" style="' + gameInfoStyle + '"></div>';
		message += '<div class="DEBUG_game_data_option" onClick="DEBUG_prizesInfoShow = !DEBUG_prizesInfoShow; if (DEBUG_prizesInfoShow) { hideElement(\'DEBUG_prizesInfo\'); } else { showElement(\'DEBUG_prizesInfo\'); }">Prizes info</div><div id="DEBUG_prizesInfo" style="' + prizesInfoStyle + '"></div>';
		
		showMessage(message, "DEBUG_game_data_text", "DEBUG_game_data");
	}

	var userInfo = "";
	var worldInfo = "";
	var gameInfo = "";
	var prizesInfo = "";

	//User info:
	userInfo += "<b>userId:</b> " + userId + "<br /><br />";
	userInfo += "<b>userName:</b> " + gameData.userName + "<br /><br />";
	userInfo += "<b>userPassword:</b> " + gameData.userPassword + "<br /><br />";
	userInfo += "<b>userKeyword:</b> " + gameData.userKeyword + "<br /><br />";
	userInfo += "<b>userHost:</b> " + gameData.userHost + "<br /><br />";
	userInfo += "<b>world:</b> " + gameData.world + "<br /><br />";
	userInfo += "<b>initialPosition:</b> " + gameData.initialPosition + "<br /><br />";
	userInfo += "<b>guests:</b> " + gameData.guests + "<br /><br />";
	userInfo += "<b>isFinalist:</b> " + gameData.isFinalist + "<br /><br />";
	userInfo += "<b>partner:</b> " + gameData.partner + "<br /><br />";
	userInfo += "<b>gender:</b> " + gameData.gender + "<br /><br />";
	userInfo += "<b>lotteryResult:</b> " + gameData.lotteryResult + "<br /><br />";
	userInfo += "<b>lotteryResultPartner:</b> " + gameData.lotteryResultPartner + "<br /><br />";
	userInfo += "<b>isLoser:</b> " + gameData.isLoser + "<br /><br />";
	userInfo += "<b>isWinner:</b> " + gameData.isWinner + "<br /><br />";
	userInfo += "<b>shopSelected:</b> " + gameData.shopSelected + "<br /><br />";
	userInfo += "<b>prizeType:</b> " + gameData.prizeType + "<br /><br />";
	userInfo += "<b>prizeGiven:</b> " + gameData.prizeGiven + "<br /><br />";

	//World info:
	worldInfo += "<b>ranking.male (" + gameData.ranking.male.length + "):</b> " + gameData.ranking.male.join(", ") + "<br /><br />";
	worldInfo += "<b>ranking.female (" + gameData.ranking.female.length + "):</b> " + gameData.ranking.female.join(", ") + "<br /><br />";

	//Game info:
	gameInfo += "<b>isGameFinished:</b> " + gameData.isGameFinished + "<br /><br />";
	gameInfo += "<b>totalUsers:</b> " + gameData.totalUsers + " (" + gameData.totalUsersNoFake + " real: " + gameData.totalUsersNoFakeMale + " male, " + gameData.totalUsersNoFakeFemale + " female)<br /><br />";
	gameInfo += "<b>totalInQueues:</b> " + gameData.totalInQueues + " (" + gameData.totalInQueuesNoFake + " real: " + gameData.totalInQueuesNoFakeMale + " male, " + gameData.totalInQueuesNoFakeFemale + " female)<br /><br />";
	//gameInfo += "<b>totalWithoutGuests:</b> " + gameData.totalWithoutGuests + " (" + gameData.totalWithoutGuestsNoFake + " real: " + gameData.totalWithoutGuestsNoFakeMale + " male, " + gameData.totalWithoutGuestsNoFakeFemale + " female)<br /><br />";
	gameInfo += "<b>totalInLotteryRoom:</b> " + gameData.totalInLotteryRoom + " (" + gameData.totalInLotteryRoomNoFake + " real: " + gameData.totalInLotteryRoomNoFakeMale + " male, " + gameData.totalInLotteryRoomNoFakeFemale + " female)<br /><br />";
	gameInfo += "<b>totalInLotteryRoomSingle:</b> " + gameData.totalInLotteryRoomSingle + " (" + gameData.totalInLotteryRoomSingleNoFake + " real: " + gameData.totalInLotteryRoomSingleNoFakeMale + " male, " + gameData.totalInLotteryRoomSingleNoFakeFemale + " female)<br /><br />";		
	gameInfo += "<b>totalInLotteryRoomWithoutResult:</b> " + gameData.totalInLotteryRoomWithoutResult + " (" + gameData.totalInLotteryRoomWithoutResultNoFake + " real: " + gameData.totalInLotteryRoomWithoutResultNoFakeMale + " male, " + gameData.totalInLotteryRoomWithoutResultNoFakeFemale + " female)<br /><br />";		
	gameInfo += "<b>totalWinners:</b> " + gameData.totalWinners + " (" + gameData.totalWinnersNoFake + " real: " + gameData.totalWinnersNoFakeMale + " male, " + gameData.totalWinnersNoFakeFemale + " female)<br /><br />";
	gameInfo += "<b>totalLosers:</b> " + gameData.totalLosers + " (" + gameData.totalLosersNoFake + " real: " + gameData.totalLosersNoFakeMale + " male, " + gameData.totalLosersNoFakeFemale + " female)<br /><br />";
	//gameInfo += "<b>invitationsSentAverageInQueue:</b> " + gameData.invitationsSentAverageInQueue + " (" + gameData.invitationsSentAverageInQueueNoFake + " real: " + gameData.invitationsSentAverageInQueueNoFakeMale + " male, " + gameData.invitationsSentAverageInQueueNoFakeFemale + " female)<br /><br />";
	gameInfo += "<b>invitationsSentAverageToBeFinalist:</b> " + gameData.invitationsSentAverageToBeFinalist + " (" + gameData.invitationsSentAverageToBeFinalistNoFake + " real: " + gameData.invitationsSentAverageToBeFinalistNoFakeMale + " male, " + gameData.invitationsSentAverageToBeFinalistNoFakeFemale + " female)<br /><br />";

	//Prize info:
	prizesInfo += "<b>totalPrizesWon:</b> " + gameData.totalPrizesWon; + "<br /><br />";

	showMessage(userInfo, "DEBUG_userInfo", null, null, true); //Prevents showing the message.
	showMessage(worldInfo, "DEBUG_worldInfo", null, null, true); //Prevents showing the message.
	showMessage(gameInfo, "DEBUG_gameInfo", null, null, true); //Prevents showing the message.
	showMessage(prizesInfo, "DEBUG_prizesInfo", null, null, true); //Prevents showing the message.

	gameDataPrevious = gameData;
}


//Function that shows the game state:
function DEBUG_displayGameState(recursive)
{
	var message = "<br /><center>[ Game status ]</center><br /><br />";

	message += "<b>screenCurrent:</b> " + screenCurrent + "<br /><br />";
	message += "<b>showingAnimation:</b> " + showingAnimation + "<br /><br />";
	message += "<b>userHost:</b> " + userHost + "<br /><br />";
	message += "<b>userKeyword:</b> " + userKeyword + "<br /><br />";

	if (typeof(winnersList) !== "undefined" && winnersListValid(winnersList))
	{
		message += "<b>winnersList:</b> [ ";
			winnersListLength = winnersList.length;
			for (var x = 0; x < winnersListLength; x++)
			{
				message += "{ 'userName' : '" + winnersList[x].userName + "', 'prizeName' : '" + winnersList[x].prizeName + "' }, ";
			}
		message = message.substr(0, message.length - 2);
		message += " ]<br /><br />";
	}

	//userName
	//userPassword
	//languageCurrent

	showMessage(message, "DEBUG_game_state_text", "DEBUG_game_state");

	if (recursive) { setTimeout(function() { DEBUG_displayGameState(true); }, 100); }
}


//Functions that hides or shows an element:
function DEBUG_hideShowBox(element)
{
	element.className = (element.className == "") ? "hidden" : "";
}


//Function that hides or shows all elements:
function DEBUG_hideShowAllboxes(show)
{
	var classToUse = "hidden";
	if (typeof(show) !== "undefined" && show !== null && show) { classToUse = ""; }
	
	var boxes = [ "DEBUG_showHideboxes", "DEBUG_game_data", "DEBUG_game_state", "DEBUG_game_god_controls" ];
	
	var boxesLength = boxes.length;
	var box;
	for (var x = 0; x < boxesLength; x++)
	{
		box = document.getElementById(boxes[x]);
		if (box !== null) { box.className = classToUse; }
	}
}


//Function that hides or shows all elements (toggler):
var boxesHidden = false;
function DEBUG_hideShowAllboxesToggler()
{
	DEBUG_hideShowAllboxes(boxesHidden);
	boxesHidden = !boxesHidden;
}


var foregroundHidden = false;
function DEBUG_hideShowForegroundToggler()
{
	if (!foregroundHidden) { hideElement(screenCurrent + "_foreground"); }
	else { showElement(screenCurrent + "_foreground"); }
	foregroundHidden = !foregroundHidden;
}


//Function that inserts a guest for a given user (host):
function DEBUG_getAGuest(desiredUserId)
{
	DEBUG_sendServer("getaguest", desiredUserId);
}


//Function that deletes a guest for a given user:
function DEBUG_loseAGuest(desiredUserId)
{
	DEBUG_sendServer("loseaguest", desiredUserId);
}


//Function that makes a user go to the lottery room:
function DEBUG_goToLotteryRoom(desiredUserId)
{
	DEBUG_sendServer("gotolotteryroom", desiredUserId);
}

//Function that gives a partner to a user:
function DEBUG_getAPartner(desiredUserId)
{
	DEBUG_sendServer("getapartner", desiredUserId);
}


//Function that deletes the partner of the user:
function DEBUG_losePartner(desiredUserId)
{
	DEBUG_sendServer("losepartner", desiredUserId);
}


//Function that makes a user go to the queue:
function DEBUG_goToQueue(desiredUserId)
{
	DEBUG_sendServer("gotoqueue", desiredUserId);
}


//Function that makes a user win a prize:
function DEBUG_becomeWinner(desiredUserId)
{
	DEBUG_sendServer("becomewinner", desiredUserId);
}


//Function that makes a user lose the game:
function DEBUG_becomeLoser(desiredUserId)
{
	DEBUG_sendServer("becomeloser", desiredUserId);
}


//Function that inserts a newcomer to the queue:
function DEBUG_insertNewUserQueue(desiredUserId, gender)
{
	DEBUG_sendServer("insertnewuserqueue", desiredUserId, gender);
}


//Function that gives the partner a lottery result:
function DEBUG_givePartnerLotteryResult(desiredUserId)
{
	DEBUG_sendServer("givepartnerlotteryresult", desiredUserId);
}


//Function that gives the partner a lottery result:
function DEBUG_givePartnerToEveryone()
{
	DEBUG_sendServer("givepartnertoeveryone");
}


//Function that gives the user a prize:
function DEBUG_winPrize(prizeType)
{
	DEBUG_sendServer("winprize", userId, null, prizeType);
}


//Function that makes the game finish:
var DEBUG_gameFinished = false;
function DEBUG_endGame()
{
	DEBUG_gameFinished = true;
}



//Function that sends a DEBUG request to the server controller:
function DEBUG_sendServer(action, desiredUserId, gender, prizeType)
{
	if (DEBUG_MODE) { showError(""); }

	if (typeof(desiredUserId) === "undefined" || desiredUserId === null || !desiredUserId) { desiredUserId = userId; }
	
	var variables = "action=" + encodeURLValue(action.toLowerCase());
	variables += "&user_id=" + encodeURLValue(desiredUserId);
	if (typeof(gender) !== "undefined") { variables += "&gender=" + encodeURLValue(trim(gender)); }
	if (typeof(prizeType) !== "undefined") { variables += "&prize_type=" + encodeURLValue(trim(prizeType)); }
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText).toUpperCase();
		//If the request has not been processed corretly:
		if (response !== "OK")
		{
			//Shows the error and the server response or error in debug mode:
			if (DEBUG_MODE) { showError(localize("DEBUG_SERVER_CHEATING_REQUEST_ERROR") + ":<br />" + response); }
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("DEBUG_SERVER_CHEATING_REQUEST_ERROR") + ":<br />" + response); }
	}
	
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}



//Function that gets a list of the available worlds and inserts them in the input list:
var DEBUG_worldsStringLast = "";
var worldsPrevious;
setTimeout(getWorlds, 1000);
function getWorlds()
{
	//Gets the id of the user:
	var variables = "action=getworlds";
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText);
		//If the request has not been processed corretly:
		if (response !== DEBUG_worldsStringLast)
		{
			try
			{
				eval("var worlds = [" + response + "];");

				//If we can fin the select element, we fill it:
				if (worlds != worldsPrevious)
				{
					var DEBUG_worldSelector = document.getElementById("DEBUG_worldSelector");
					var option;
					if (DEBUG_worldSelector !== null)
					{
						var worldsLength = worlds.length;
						for (var x = 0; x < worldsLength; x++)
						{
							option = document.createElement("option");
							option.text = option.value = x;
							DEBUG_worldSelector.add(option);
						}

						//Stores the last response:
						DEBUG_worldsStringLast = response;
					}
					worldsPrevious = worlds;
				}
			} catch (e) { if (DEBUG_MODE) { showError(localize("DEBUG_SERVER_CHEATING_REQUEST_ERROR") + ":<br />" + response); } }
		}

		//Calls the function after a time:
		setTimeout(getWorlds, 1000);
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("DEBUG_SERVER_CHEATING_REQUEST_ERROR") + ":<br />" + response); }

		//Calls the function after a time:
		setTimeout(getWorlds, 1000);
	}
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that makes the player become a user of a given world:
function DEBUG_becomeUserWorld(world)
{
	//If the world is empty, exits:
	if (typeof(world) === "undefined" || world === null) { return; }

	//If the world given is not a number, exists:
	world = parseInt(world);
	if (isNaN(world)) { return; }

	//Gets the id of the user:
	var variables = "action=getoneuserworld";
	variables += "&world=" + encodeURLValue(world);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = parseInt(trim(XHR.responseText));
		//If the request has not been processed corretly:
		if (!isNaN(response) && response > 0)
		{
			showWaitMessage("Loading world " + world + "...");

			//Sets as the queue has never been showed before:
			firstTimeShowingQueue = true;

			//Sets as the current user id the user received:
			userId = response;
		}
		//...otherwise, if the data is not valid:
		else
		{
			//Calls the function after a time:
			setTimeout(function() { DEBUG_becomeUserWorld(world); }, 1000);
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("DEBUG_SERVER_CHEATING_REQUEST_ERROR") + ":<br />" + response); }

		//Calls the function after a time:
		setTimeout(function() { DEBUG_becomeUserWorld(world); }, 1000);
	}
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that makes an element follow or stop following the mouse:
var DEBUG_moveWithMouseInterval;
function DEBUG_moveWithMouse(e, element, stop)
{
	if (typeof(stop) === "undefined" || stop === null) { stop = false; }
	
	if (stop)
	{
		document.onmousemove = function() {};
		hideElement("DEBUG_full_transparent_layer");
	}
	else
	{
		showElement("DEBUG_full_transparent_layer");
		document.onmousemove =
								function(e)
								{
									if (element !== null)
									{
				                        var ie = document.all ? true : false;
				                        var mouseX = 0;
				                        var mouseY = 0;
				                        if (ie)
				                        {
				                            mouseX = event.clientX + document.body.scrollLeft;
				                            mouseY = event.clientY + document.body.scrollTop;
				                        }
				                        else
				                        {
				                            mouseX = e.pageX;
				                            mouseY = e.pageY;
				                        } 
				                        if (mouseX < 0) { mouseX = 0; }
				                        if (mouseY < 0) { mouseY = 0; }
				                        element.style.left = (mouseX - 10) + "px";
				                        element.style.top = (mouseY - 10) + "px";
				                        element.style.right = element.style.bottom = "auto";
				                    }
								}
	}

}