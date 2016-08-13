// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that identifies the user through a keyword (if the user exists):
function identifyUserByKeyword(callback)
{
	if (typeof(callback) !== "function") { callback = init; } //By default, calls init function.

	var variables = "action=getuserbykeyword";
	variables += "&user_keyword=" + encodeURLValue(userKeyword);
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
				eval("var userInfo = " + response);
				//If the data retrieved is valid, we save it in the final variable:
				var dataValid = false;
				if (typeof(userInfo) === "object" && typeof(userInfo.userName) !== "undefined" && typeof(userInfo.userPassword) !== "undefined" && typeof(userInfo.userGender) !== "undefined")
				{
					var userNameLocal = trim(userInfo.userName);
					var userPasswordLocal = trim(userInfo.userPassword);
					var userGenderLocal = trim(userInfo.userGender);
					//In debug mode, and end the game if we want:
					if (userNameLocal !== "" && userPasswordLocal !== "" && (userGenderLocal === "male" || userGenderLocal === "female"))
					{
						//Sets the data as valid:
						dataValid = true;

						//Sets the data retrieved:
						userName = userNameLocal;
						userPassword = userPasswordLocal;
						userGender = userGenderLocal;
					}
				}
				if (DEBUG_MODE && !dataValid) { showError(localize("DATA_IS_NOT_VALID") + ": " + response); }
			} catch(e) { if (DEBUG_MODE) { showError(localize("ERROR_PARSING_DATA") + ": " + e + " [" + response + "]"); } }
		} //else if (DEBUG_MODE) { showError(localize("GET_DATA_EMPTY")); }

		//In debug mode, displays the game data:
		if (DEBUG_MODE && gameDataValid(gameData)) { DEBUG_displayGameData(gameData); }

		//Calls the callback function:
		callback.call();
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error in debug mode:
		if (DEBUG_MODE) { showError(localize("GET_DATA_FAILED") + ":<br />" + response); }

		//Calls the callback function:
		callback.call();
	}

	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}