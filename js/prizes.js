// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that shows information about the prize won:
var GET_PRIZE_INFORMATION_MS = 3000;
function showPrizeInformation()
{
	//var prizeInformationElement = document.getElementById("winner_screen_prize_information");
	//if (prizeInformationElement === null) { return; }

//	var text = "Your prize type: " + gameData.prizeType;
//	prizeInformationElement.innerHTML = text;
	showMessage(localize("LOADING_PRIZE_INFORMATION"), ((gameData.isWinner) ? "winner" : "loser") + "_screen_prize_information");

	//Gets the prize information:
	var variables = "action=getprizeinformation";
	variables += "&user_id=" + encodeURLValue(userId);
	variables += "&user_name=" + encodeURLValue(userName);
	variables += "&user_password=" + encodeURLValue(userPassword);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText);
		var informationValid = false;

		//If the data is not an empty string, it should be a JSON object:
		if (response !== "")
		{
			try
			{
				eval("var prizeInformation = " + response);
				//If the data retrieved is valid, we show the information:
				if (prizeInformationValid(prizeInformation))
				{
					//Shows the data:
					showMessage(prizeInformationGetHTML(prizeInformation), ((gameData.isWinner) ? "winner" : "loser") + "_screen_prize_information");

					//Sets as the data is valid:
					informationValid = true;
				} else if (DEBUG_MODE) { showError(localize("DATA_IS_NOT_VALID") + ": " + response); }
			} catch(e) { if (DEBUG_MODE) { showError(localize("ERROR_PARSING_DATA") + ": " + e + " [" + response + "]"); } }
		} else if (DEBUG_MODE) { showError(localize("GET_DATA_EMPTY")); }

		//Calls the function again if there was any error:
		if (!informationValid) { getGameDataTimeout = setTimeoutSynchronized(showPrizeInformation, GET_PRIZE_INFORMATION_MS); }
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error in debug mode:
		if (DEBUG_MODE) { showError(localize("GET_DATA_FAILED") + ":<br />" + response); }

		//Calls the function again:
		getGameDataTimeout = setTimeoutSynchronized(showPrizeInformation, GET_PRIZE_INFORMATION_MS);
	}

	CB_XHRForm("php/controller.php?using_ajax=yes&language=" + languageCurrent, variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that checks whether the game data retrieved is valid:
function prizeInformationValid(prizeInformation)
{
	//TODO: check all properties!!!
	return (typeof(prizeInformation) !== "undefined" && prizeInformation !== null && typeof(prizeInformation.prizeName) !== "undefined" && trim(prizeInformation.prizeName) !== "");
}


//Function that returns the prize information in HTML format:
function prizeInformationGetHTML(prizeInformation)
{
	if (!prizeInformationValid(prizeInformation)) { return ""; }

	//prizeName, prizeImage, prizeCodePositionLeft, prizeCodePositionTop, prizeCode.

	var html = "";

	html += '<div>';
		html += localize("YOU_WON_THIS_PRIZE") + ': ';
		html += '<span class="prize_name">' + prizeInformation.prizeName + '</span>';
		if (prizeInformation.prizeCode !== "") { html += ' (<span class="prize_code">' + prizeInformation.prizeCode + '</span>)'; }
		
		if (prizeInformation.prizeCodePositionLeft !== "" && prizeInformation.prizeCodePositionTop !== "")
		{
			html += '<div style="position:relative; width:90%; left:0%; top:0%;">';
				html += '<img src="' + prizeInformation.prizeImage + '" style="width:70%;" />';
				html += '<div class="' + prizeInformation.prizeType + '_code" style="position:absolute; left:' + prizeInformation.prizeCodePositionLeft + '; top:' + prizeInformation.prizeCodePositionTop + ';">';
					html += prizeInformation.prizeCode;
				html += '</div>'
			html += '</div>';
		}
		else
		{
			//html += '<div style="position:relative; width:90%; height:20%; left:0%; top:0%; border:2px solid blue;">';
				html += '<br />';
				html += '<img src="' + prizeInformation.prizeImage + '" style="width:50%;" />';
			//html += '</div>';
		}
	html += '</div>';

	return html;
}


//Function that shows the shop list selector belong to a city chosen:
var citySelectorPreviousOption;
function changeCitySelector(option, isLoser)
{
	if (typeof(isLoser) === "undefined" || isLoser === null) { isLoser = false; }

	//Hides the previous selector if there is any:
	hideElement("shops_city_" + citySelectorPreviousOption + ((isLoser) ? "_losers" : ""));

	//Goes back to the first to the list of the selector:
	var shopSelector = document.getElementById("shops_city_" + option + ((isLoser) ? "_losers" : ""));
	if (shopSelector !== null) { shopSelector.selectedIndex = 0; }

	//Shows the chosen selector:
	showElement("shops_city_" + option + ((isLoser) ? "_losers" : ""), "inline");

	//Hides any shop information (if any):
	showShopInformation("", isLoser);

	//Stores the previous selector for next time:
	citySelectorPreviousOption = option;
}


//Function that shows information for a shop after selecting one:
var shopSelectorPreviousOption;
function showShopInformation(shop, isLoser)
{
	if (typeof(isLoser) === "undefined" || isLoser === null) { isLoser = false; }

	//Hides the previous selector if there is any:
	hideElement("shop_information_" + shopSelectorPreviousOption + ((isLoser) ? "_losers" : ""));

	//Shows the chosen selector:
	showElement("shop_information_" + shop + ((isLoser) ? "_losers" : ""), "inline");

	//Stores the previous selector for next time:
	shopSelectorPreviousOption = shop;
}



//Function that marks a shop as selected:
var shopSelectedLast;
function selectShop(shopSelected)
{
	//Shows wait message:
	showWaitMessage(localize("SELECTING_SHOP"));

	var variables = "action=selectshop";
	variables += "&user_id=" + encodeURLValue(userId);
	variables += "&shop_selected=" + encodeURLValue(shopSelected);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText).toUpperCase();
		//If the request has not been processed corretly:
		if (response !== "")
		{
			//Shows the error and the server response or error in debug mode:
			if (DEBUG_MODE) { showError(localize("AJAX_ERROR_SELECTING_SHOP") + ":<br />" + response); }

			//Shows error message:
			showWaitMessage(localize("ERROR_SELECTING_SHOP"));

			//Hides wait message after a while:
			setTimeout(function() { showWaitMessage(""); }, 5000);
		}
		//...otherwise, the shop has been selected:
		else
		{
			//Hides the shop selector:
			hideElement("shop_selector_container");

			//Shows the code to mark the product as given:
			showElement("prize_given_code_container_" + shopSelected);

			shopSelectedLast = shopSelected;

			//Hides wait message:
			showWaitMessage("");
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("AJAX_ERROR_SELECTING_SHOP") + ":<br />" + response); }

		//Shows error message:
		showWaitMessage(localize("ERROR_SELECTING_SHOP"));

		//Hides wait message after a while:
		setTimeout(function() { showWaitMessage(""); }, 5000);
	}
	
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that marks a prize as given:
function markProductAsGiven()
{
	//If there is no code, exits:
	if (typeof(shopSelectedLast) === "undefined") { shopSelectedLast = gameData.shopSelected; }
	var codeGivenInput = document.getElementById("seller_code_" + shopSelectedLast);
	if (codeGivenInput === null || typeof(codeGivenInput.value) === "undefined") { return; }
	var codeGiven = trim(codeGivenInput.value);
	if (codeGiven === "") { return; }

	//Shows wait message:
	showWaitMessage(localize("APPLYING_CODE"));

	var variables = "action=markprizeasgiven";
	variables += "&user_id=" + encodeURLValue(userId);
	variables += "&prize_given_code=" + encodeURLValue(codeGiven);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText).toUpperCase();
		//If the request has not been processed corretly:
		if (response !== "")
		{
			//Shows the error and the server response or error in debug mode:
			if (DEBUG_MODE) { showError(localize("AJAX_ERROR_APPLYING_CODE") + ":<br />" + response); }

			//Shows error message:
			showWaitMessage(localize("CODE_NOT_APPLIED"));

			//Hides wait message after a while:
			setTimeout(function() { showWaitMessage(""); }, 5000);
		}
		//...otherwise, the shop has been selected:
		else
		{
			//Hides the code input:
			hideElement("prize_given_code_container_" + shopSelectedLast);

			//Shows the container that tells taht the product has been given:
			showElement("product_already_given_container");

			//Hides wait message:
			showWaitMessage("");
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("AJAX_ERROR_APPLYING_CODE") + ":<br />" + response); }

		//Shows error message:
		showWaitMessage(localize("CODE_NOT_APPLIED"));

		//Hides wait message after a while:
		setTimeout(function() { showWaitMessage(""); }, 5000);
	}
	
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}