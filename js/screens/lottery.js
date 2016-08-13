// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that shows the lottery room (will be called every game loop):
var firstTimeShowingLotteryRoom = true;
function showLotteryRoomScreen()
{
	//NOTE: don't forget to stop all previous animations! (from queue screens)

	//If the user is winner (that means the partner is winner too), shows an animation:
	//don't forget showingAnimation = true!!!

	//Defines whether we need to show the play button or not (hidden by default): 
	var showPlayButton = false;

	//If it was the first time we show the lottery room:
	if (firstTimeShowingLotteryRoom === true)
	{
		hideElement("roulette_result_icon_male");
		hideElement("roulette_result_icon_female");
	}

	//If we still don't have a partner, shows we are waiting for one:
	var partnerId = parseInt(trim(gameData.partner));
	var genderOpposite = (gameData.gender === "male") ? "female" : "male";
	if (isNaN(partnerId)) { partnerId = 0; }
	if (partnerId === 0)
	{
		showMessage(localize("LOTTERY_ROOM_WAITING_PARTNER"), "roulette_result_text_" + genderOpposite);

		//Changes the image to show an unknown person:
		//img/lottery/unknown_partner_male.png
		//img/lottery/unknown_partner_female.png
		showElement("finalist_" + genderOpposite + "_unknown_image");
		hideElement("finalist_" + genderOpposite + "_image");
		
		finalist_female_image
	}
	else
	{
		//Changes the image for the default:
		hideElement("finalist_" + genderOpposite + "_unknown_image");
		showElement("finalist_" + genderOpposite + "_image");
	}

	//Shows the result for male (if we are male or the male is already there):
	var lotteryResultMale = parseInt(trim((gameData.gender === "male") ? gameData.lotteryResult : gameData.lotteryResultPartner));
	if (partnerId !== 0 || gameData.gender === "male")
	{
		var maleResultMessage = "";
		if (isNaN(lotteryResultMale)) { lotteryResultMale = 0; }
		else if (lotteryResultMale < 1 || lotteryResultMale > 12) { lotteryResultMale = 0; }
		if (lotteryResultMale !== 0)
		{
			maleResultMessage = localize("LOTTERY_ROOM_RESULT") + ": " + localize("LOTTERY_ROOM_RESULT_" + lotteryResultMale);
			showMessage(localize("LOTTERY_ROOM_RESULT_ICON_" + lotteryResultMale), "roulette_result_icon_male");
			//Since there is already a result, hides the roulette of the male:
			hideElement("roulette_male");
			hideElement("roulette_male_clickable");
		}
		else
		{
			maleResultMessage = localize("LOTTERY_ROOM_RESULT_WAITING");
			if (gameData.gender === "male") { showPlayButton = true; }
			//Shows the roulette (in case it was hidden and we want to come back the room in debug mode):
			showElement("roulette_male" + ((gameData.gender === "male") ? "_clickable" : ""));
			//Hides the icon (just in case, if we used DEBUG mode to return and do strange things):
			hideElement("roulette_result_icon_male");
		}
		showMessage(maleResultMessage, "roulette_result_text_male");
	} else { lotteryResultMale = 0; }


	//Shows the result for female (if we are female or the female is already there):
	var lotteryResultFemale = parseInt(trim((gameData.gender === "female") ? gameData.lotteryResult : gameData.lotteryResultPartner));
	if (partnerId !== 0 || gameData.gender === "female")
	{
		var femaleResultMessage = "";
		if (isNaN(lotteryResultFemale)) { lotteryResultFemale = 0; }
		else if (lotteryResultFemale < 1 || lotteryResultFemale > 12) { lotteryResultFemale = 0; }
		if (lotteryResultFemale !== 0)
		{
			femaleResultMessage = localize("LOTTERY_ROOM_RESULT") + ": " + localize("LOTTERY_ROOM_RESULT_" + lotteryResultFemale);
			showMessage(localize("LOTTERY_ROOM_RESULT_ICON_" + lotteryResultFemale), "roulette_result_icon_female");
			//Since there is already a result, hides the roulette of the female:
			hideElement("roulette_female");
			hideElement("roulette_female_clickable");
		}
		else
		{
			femaleResultMessage = localize("LOTTERY_ROOM_RESULT_WAITING");
			if (gameData.gender === "female") { showPlayButton = true; }
			//Shows the roulette (in case it was hidden and we want to come back the room in debug mode):
			showElement("roulette_female" + ((gameData.gender === "female") ? "_clickable" : ""));
			//Hides the icon (just in case, if we used DEBUG mode to return and do strange things):
			hideElement("roulette_result_icon_female");
		}
		showMessage(femaleResultMessage, "roulette_result_text_female");
	} else { lotteryResultFemale = 0; }


	//Shows or hides the play button:
	if (showPlayButton === true) { showElement("roulette_play_button_" + gameData.gender, "inline"); }
	else { hideElement("roulette_play_button_" + gameData.gender); }


	//If it was the first time we show the lottery room:
	if (firstTimeShowingLotteryRoom === true)
	{
		//Hides and shows the corresponding instructions:
		hideElement("queue_instructions");
		showElement("lottery_room_instructions");

		//Shows the image of the user:
		showElement("finalist_" + gameData.gender + "_image");

		//Show the queue container element for the first time:
		showScreen("lottery_room_screen");

		//We have to hide the waiting message
		showWaitMessage("");

		firstTimeShowingLotteryRoom = false;
	}


	//If the user has a partner and both have a valid result (not zero):
	if (lotteryResultMale !== 0 && lotteryResultFemale !== 0)
	{
		//Sets as we were showing an animation:
		showingAnimation = true;

		//If the result is the same one:
		if (lotteryResultMale === lotteryResultFemale)
		{
			//Changes the images for two happy people:
			hideElement("finalist_male_image");
			hideElement("finalist_female_image");
			showElement("finalist_male_happy_image");
			showElement("finalist_female_happy_image");
		}
		//...otherwise, shows a thanks for playing screen:
		else
		{
			//Changes the images for two sad people:
			hideElement("finalist_male_image");
			hideElement("finalist_female_image");
			showElement("finalist_male_sad_image");
			showElement("finalist_female_sad_image");
		}

		//Hides instructions:
		hideElement("lottery_room_instructions");

		//After some time the animations will have finished:
		setTimeout(function() { showingAnimation = false; }, 3000);
	}
	else { showingAnimation = false; }
}



//Function that plays lottery:
function playLottery()
{
	//Sets as there was an animation:
	showingAnimation = true;

	//Hides the play button:
	hideElement("roulette_play_button_" + gameData.gender);

	//Makes the roulette spin:
	var roulette = document.getElementById("roulette_" + gameData.gender + "_clickable");
	if (roulette !== null)
	{
		roulette.className = "";
		setTimeout(function() { roulette.className = "spinning"; }, 100);
	}

	//Calls the server to play lottery:
	var variables = "action=playlottery";
	variables += "&user_id=" + encodeURLValue(userId);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunction = function (XHR)
	{
		var lotteryResult = parseInt(trim(gameData.lotteryResult));
		if (isNaN(lotteryResult)) { lotteryResult = 0; }
		
		//If we already have a valid result:
		if (lotteryResult >= 1 && lotteryResult <= 12)
		{
			setTimeout(
						function()
						{ 
							//Stops the roulette:
							if (roulette !== null)
							{
								roulette.className = "";
							}

							//If we are not showing another screen, shows the lottery room again (with the new results):
							if (screenCurrent === "lottery_room_screen") { showLotteryRoomScreen(); }
						}, 200);
		}
		//...otherwise, if we still don't have a valid lottery result, calls the function again:
		else
		{
			playLottery();
		}
	}
	setTimeout(
				function()
				{
					getGameData(true); //Gets the new game data ignoring animation.
					CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", callbackFunction, "callbackFunctionOK", "callbackFunctionError");
				}, 1500); //Needs a time to let the roulette spin.
}