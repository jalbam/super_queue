// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Global constants (NOTE: don't touch this, PHP will change it!):
var DEBUG_MODE = true; //Defines debug mode.
var DEBUG_MODE_PASSWORD = ""; //Defines debug mode password.
var WECHAT_INFO_DETECTOR_ENABLED = true;
var GAME_FINISHED = false; //Defines whether the game has finished or not.
var USE_SIMPLE_LOGIN = false; //Defines whether just ask for password or not.
var PASSWORD_IS_PHONE = false; //Defines whether the password must be a phone number or not.
var USER_NAME_CHARACTERS_MINIMUM = 3; //Defines minimum characters for user name.
var USER_NAME_CHARACTERS_MAXIMUM = 50; //Defines maximum characters for user name.
var PASSWORD_CHARACTERS_MINIMUM = 3; //Defines minimum characters for password.
var PASSWORD_CHARACTERS_MAXIMUM = 25; //Defines maximum characters for password.
var LANGUAGE_DEFAULT = "en"; //Default language.


//Global variables:
var userName = ""; //User name. Don't touch this, PHP will change it!
var userPassword = ""; //User name. Don't touch this, PHP will change it!
var userKeyword = ""; //User keyword (as WeChat id). Don't touch this, PHP will change it!
var userHost = ""; //User host (the one who sent the invitation). Don't touch this, PHP will change it!
var userGender = ""; //User gender. Don't touch this, PHP will change it!
var languageCurrent = LANGUAGE_DEFAULT; //Current language. Don't touch this, PHP will change it!


//Initialize the game when the web loads (we need enough time to let WeixinJSBridge start):
window.onload = function() { setTimeout(init, (WECHAT_INFO_DETECTOR_ENABLED) ? 5000 : 100); }


//If the screen is resized, resizes elements:
window.onresize = resizeAll;



//Main function:
var wechatInfoDetectorTried = false;
var identifyUserByKeywordTried = false;
function init()
{
	//If the game has finished, shows the game finished screen and exits:
	//if (GAME_FINISHED) { showGameFinishedScreen(); return; }

	//If AJAX is not supported, exits the game:
	if (!CB_XHRIsSupported()) { if (DEBUG_MODE) { showError(localize("AJAX_NOT_SUPPORTED")); } return; }

	//If we want to use the wechat detector and it has not been tried before:
	if (WECHAT_INFO_DETECTOR_ENABLED && !wechatInfoDetectorTried && WECHAT_usingWechat())
	{
		//Sets as we have already tried the Wechat detector:
		wechatInfoDetectorTried = true;

		//Calls the Wechat detector:
		WECHAT_getInfo(init); //The function will call the callback function (this one) after finishes.

		//Exits the function:
		return;
	}

	//If there is user keyword but not name or password, tries to get them before (if the user exists):
	if (!identifyUserByKeywordTried && userKeyword !== "")// && (userName === "" || userPassword === ""))
	{
		identifyUserByKeywordTried = true;

		//Tries to identify get the user name and password from the keyword:
		identifyUserByKeyword(init); //The function will call the callback function (this one) after finishes.

		//Exists the function:
		return;
	}

 	//Hides the loading message:
 	showWaitMessage("");

	//Refreshes the Wechat sharing options (we still don't have the user id to share it as host user):
	setTimeout(WECHAT_updateSharingOptions, (!WECHAT_INFO_DETECTOR_ENABLED) ? 5000 : 100); //Uses a timeout to let the WeixinJSBridge start (if we use info detector, we have already waited to start!).

	//Resizes all elements for the first time:
	resizeAll(true);

	//If we can already got user name, password and gender (thanks to PHP or Wechat info detector):
	if (userName !== "" && userPassword !== "" && (userGender === "male" || userGender === "female"))
	{
		//Logins the user (will register the user if doesn't exist already):
		loginRegisterUser(userName, userPassword, userGender);
	}
	//...otherwise, we show the registration form:
	else
	{
		registrationFormShow();
	}
}


//Function that shows registration form:
function registrationFormShow()
{
	//Shows the host user (if any):
	showMessage(userHost, "host_user", "host_user_container");

	//If we have a gender, selects the correct option:
	registrationFormSelectGender(userGender);

	//If we have an user name, writes it on the input:
	if (userName !== "") { changeUserNameInput(userName); }

	//Hides the waiting message (just in case):
	showWaitMessage();

	//Shows the registration form:
	showElement("registration_form_container");
}


//Function that changes the input for the user name with a name given:
function changeUserNameInput(userName)
{
	if (userName === "-") { userName = ""; }
	var userNameInput = document.getElementById("form_user")
	if (userNameInput !== null) { userNameInput.value = userName; }
}


//Function that hides registration form:
function registrationFormHide()
{
	//Hides the registration form:
	hideElement("registration_form_container");
}


//Function that checks whether an user name has valid characters:
function userNameLegalCharacters(userName)
{
	var isValid = true;

	//Note: not implemented.

	return isValid;
}


//Function that checks whether an user name has valid characters:
function passwordLegalCharacters(userPassword)
{
	var isValid = true;

	//If we only want to allow telephone numbers as passwords:
	if (PASSWORD_IS_PHONE)
	{
		userPassword = userPassword.replace(/ /g, "_"); //Strips out any space.
		
		//Strips out country code:
		if (userPassword.substr(0, 3) === "+86") { userPassword = userPassword.substr(3); }
		//userPassword = userPassword.replace("+86", ""); //Strips out country code.
		if (userPassword.substr(0, 4) === "0086") { userPassword = userPassword.substr(4); }
		
		//The mobile phones in China contain 11 figures:
		if (userPassword.substr(0, 1) !== "1") { isValid = false; } //All mobile phone numbers start with 1 in China.
		else if (userPassword.length !== 11) { isValid = false; }
		else if (isNaN(userPassword)) { isValid = false; }
	}

	return isValid;
}


//Function that selects a gender:
function registrationFormSelectGender(gender)
{
	if (typeof("gender") === "undefined" || gender === null || !gender) { return; }
	gender = trim(gender);
	if (gender !== "male" && gender !== "female") { return; }

	var genderOpposite = (gender === "male") ? "female" : "male";

	//Selects the chosed gender:
	var genderCheckbox = document.getElementById("form_" + gender);
	var genderOppositeCheckbox = document.getElementById("form_" + genderOpposite);
	var genderImage = document.getElementById("registration_form_" + gender + "_image");
	var genderOppositeImage = document.getElementById("registration_form_" + genderOpposite + "_image");

	if (genderCheckbox !== null && typeof(genderCheckbox.checked) !== "undefined")
	{
		//Selects the radiobutton selected:
		genderCheckbox.checked = true;

		genderImage.className = "selected";
	}
	if (genderOppositeCheckbox !== null && typeof(genderOppositeCheckbox.checked) !== "undefined")
	{
		//Selects the radiobutton selected:
		genderOppositeCheckbox.checked = false;

		genderOppositeImage.className = "";
	}
}


//Function that checks whether the registration form is valid or not:
function registrationFormValidate(userSent, passwordSent, genderSent)
{
	var errors = "";

	//Check the information sent:
	if (typeof(userSent) !== "undefined" && typeof(passwordSent) !== "undefined" && typeof(genderSent) !== "undefined")
	{
		userSent = trim(userSent);
		passwordSent = trim(passwordSent);
		genderSent = trim(genderSent).toLowerCase();

		//If we only want to allow telephone numbers as passwords:
		if (PASSWORD_IS_PHONE)
		{
			passwordSent = passwordSent.replace(/ /g, "_"); //Strips out any space.
			if (passwordSent.length > 3)
			{
				passwordSent = passwordSent.replace("+86", ""); //Strips out country code.
			}
		}		

		//Check empty required fields and allowed values:
		if (!USE_SIMPLE_LOGIN && userSent === "") { errors +=  localize("FORM_USER_NAME") + " " + localize("FORM_ERROR_FIELD_EMPTY") + "<br />"; }
		else if (!USE_SIMPLE_LOGIN && userSent.length < USER_NAME_CHARACTERS_MINIMUM) { errors +=  localize("FORM_USER_NAME") + " " + localize("FORM_ERROR_FIELD_TOO_SHORT") + "<br />"; }
		else if (!USE_SIMPLE_LOGIN && userSent.length > USER_NAME_CHARACTERS_MAXIMUM) { errors +=  localize("FORM_USER_NAME") + " " + localize("FORM_ERROR_FIELD_TOO_LONG") + "<br />"; }
		else if (!USE_SIMPLE_LOGIN && !userNameLegalCharacters(userSent)) { errors +=  localize("FORM_USER_NAME") + " " + localize("FORM_ERROR_FIELD_ILLEGAL_CHARACTERS") + "<br />"; }

		if (passwordSent === "") { errors += localize("FORM_PASSWORD") + " " + localize("FORM_ERROR_FIELD_EMPTY") + "<br />"; }
		else if (passwordSent.length < PASSWORD_CHARACTERS_MINIMUM) { errors +=  localize("FORM_PASSWORD") + " " + localize("FORM_ERROR_FIELD_TOO_SHORT") + "<br />"; }
		else if (passwordSent.length > PASSWORD_CHARACTERS_MAXIMUM) { errors +=  localize("FORM_PASSWORD") + " " + localize("FORM_ERROR_FIELD_TOO_LONG") + "<br />"; }
		else if (!passwordLegalCharacters(passwordSent)) { errors +=  localize("FORM_PASSWORD") + " " + localize("FORM_ERROR_FIELD_ILLEGAL_CHARACTERS") + "<br />"; }
		
		if (genderSent === "") { errors += localize("FORM_GENDER") + " " + localize("FORM_ERROR_FIELD_NOT_CHOSEN") + "<br />"; }
		else if (genderSent !== "male" && genderSent !== "female") { errors += localize("FORM_ERROR_GENDER_UNKNOWN") + "<br />"; }
	}
	else { errors += localize("FORM_ERROR_DATA_NOT_FOUND"); }

	//If it is not valid, shows the errors:
	if (errors !== "") { registrationFormShowErrors(errors); }
	//...otherwise, no errors are shown:
	else { registrationFormShowErrors(""); }

	return (errors === "");
}


//Function that process the registration form:
function registrationFormProcess()
{
	//Hides any previous error message:
	showError();

	//Gets the user and password from the form:
	var userSentInput = document.getElementById("form_user");
	var passwordSentInput = document.getElementById("form_password");

	//If we want to use simple login, uses password as username:
	if (USE_SIMPLE_LOGIN) { userSentInput = passwordSentInput; }

	//var registrationForm = document.getElementById("registration_form");
	var UserSent = passwordSent = genderSent = "";
	if (userSentInput !== null && typeof(userSentInput.value) !== "undefined") { userSent = trim(userSentInput.value); }
	if (passwordSentInput !== null && typeof(passwordSentInput.value) !== "undefined") { passwordSent = trim(passwordSentInput.value); }
	
	//Gets the gender from the form:
	var formMaleCheckbox = document.getElementById("form_male");
	var formFemaleCheckbox = document.getElementById("form_female");
	if (formMaleCheckbox !== null && typeof(formMaleCheckbox.checked) !== "undefined" && formMaleCheckbox.checked === true)
	{
		genderSent = "male";
	}
	else if (formFemaleCheckbox !== null && typeof(formFemaleCheckbox.checked) !== "undefined" && formFemaleCheckbox.checked === true)
	{
		genderSent = "female";
	}
	else { genderSent = ""; }

	//if (registrationForm !== null && typeof(registrationForm.gender) !== "undefined" && typeof(registrationForm.gender.value) !== "undefined") { genderSent = trim(registrationForm.gender.value); }

	//If the information sent is not valid, exits:
	if (!registrationFormValidate(userSent, passwordSent, genderSent)) { return; }

	//Hides the registration form:
	registrationFormHide();

	//Shows wait message:
	showWaitMessage(localize("WAIT_PLEASE"));

	//Logins the user (will register the user if doesn't exist already):
	loginRegisterUser(userSent, passwordSent, genderSent);
}


//Function that let the user log in  (will register the user if doesn't exist already):
function loginRegisterUser(userName, userPassword, userGender)
{
	var variables = "action=loginregisteruser";
	variables += "&user_name=" + encodeURLValue(userName);
	variables += "&user_password=" + encodeURLValue(userPassword);
	variables += "&user_gender=" + encodeURLValue(userGender);
	variables += "&user_keyword=" + encodeURLValue(userKeyword);
	variables += "&user_host=" + encodeURLValue(userHost);
	if (DEBUG_MODE) { variables += "&debug_mode=yes&debug_password=" + encodeURLValue(DEBUG_MODE_PASSWORD); }
	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText).toUpperCase();
		//If login (and register if needed) is correct, will start the game:
		if (response == "OK") { startGame(userName, userPassword); }
		//...otherwise, shows the error and the registration form again:
		else
		{
			if (DEBUG_MODE) { showError(localize("LOGIN_REGISTER_USER_FAILED") + ":<br />" + response); }
			registrationFormShow();
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);
		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("LOGIN_REGISTER_USER_FAILED") + ":<br />" + response); }
		registrationFormShow();
	}
	CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);
}


//Function that shows an error:
function showMessage(message, elementId, containerElementId, displayProperty, avoidShowingOrHiding)
{
	if (typeof(message) === "undefined" || message === null || !message) { message = ""; }
	if (typeof(elementId) === "undefined" || elementId === null || !elementId) { elementId = "errors"; }
	if (typeof(containerElementId) === "undefined" || containerElementId === null || !containerElementId) { containerElementId = elementId; }
	if (typeof(avoidShowingOrHiding) === "undefined" || avoidShowingOrHiding === null) { avoidShowingOrHiding = false; }
	var element = document.getElementById(elementId);
	
	message = trim(message);

	if (element !== null && element.innerHTML != message)
	{
		element.innerHTML = message;

		//If the message is not empty, shows the element that contents it:
		if (message !== "" && !avoidShowingOrHiding) { showElement(containerElementId, displayProperty); }
		//...otherwise, hides the element:
		else if (!avoidShowingOrHiding) { hideElement(containerElementId); }
	}
}


//Function that shows an error in registration form:
function registrationFormShowErrors(message)
{
	showMessage(message, "registration_form_errors");
}


//Function that shows an error:
function showError(message)
{
	showMessage(message, "errors");
}


//Function that shows the wait message:
function showWaitMessage(message)
{
	showMessage(message, "wait_message", "wait");
}


//Function that hides an element:
function hideElement(id)
{
	var element = document.getElementById(id);
	if (element !== null && element.style)
	{
		element.style.visibility = "hidden";
		element.style.display = "none";
	}
}


//Function that shows and element:
function showElement(id, displayProperty)
{
	var element = document.getElementById(id);
	if (element !== null && element.style)
	{
		element.style.visibility = "visible";
		if (typeof(displayProperty) === "undefined" || displayProperty == null) { displayProperty = "block"; }
		element.style.display = displayProperty;
	}
}


//Gets a localized string:
function localize(index, language)
{
	var str = "";

	if (typeof(language) === "undefined" || language == null || !language)
	{
		language = languageCurrent;
		if (typeof(language) === "undefined" || language == null || !language)
		{
			language = LANGUAGE_DEFAULT;
			if (typeof(language) === "undefined" || language == null || !language)
			{
				language = "en";
			}
		}
	}

	language = language.toLowerCase();
	index = index.toLowerCase();
	var localizedElement = document.getElementById(index + "_" + language);
	if (localizedElement !== null)
	{
		str = trim(localizedElement.innerHTML);
	}

	return str;
}


//Function that trims a string:
function trim(str)
{
	if (typeof(str) === "undefined" || str === null || !str) { str = ""; }
	else { str += ""; }
	return str.replace(/^\s+|\s+$/g, "");
}


//Encodes an URL value:
function encodeURLValue(value)
{
	if (typeof(encodeURIComponent) !== "undefined") { return encodeURIComponent(value); }
	else { return escape(value); }
}


//Encodes an URL:
function encodeURL(value)
{
	if (typeof(encodeURI) !== "undefined") { return encodeURI(value); }
	else { return escape(value); }
}

//Function that returns whether we are in debug mode or not:
function isDebugMode()
{
	return (typeof(DEBUG_MODE) !== "undefined" && DEBUG_MODE);
}


//Function that resize all elements according to the screen size:
var screenWidthPrevious;
var screenHeightPrevious;
var portraitMessageHideTimeout;
function resizeAll(all)
{
	if (typeof(all) === "undefined" || all === null) { all = false; }

	//Defines and gets the elements to resize:
	//var elementsIdToResize = [ "queue_screen", "game_finished_screen", "lottery_room_screen", "loser_screen", "winner_screen" ];
	//var elementsIdToResize = [ "queue_screen", "lottery_room_screen", "winner_screen", "loser_screen" ];
	var elementsIdToResize = [ "queue_screen", "lottery_room_screen" ];

	//Gets screen size:
	var screenWidth = getScreenWidth();
	var screenHeight = getScreenHeight();

	//If the window size is the same, exits:
	if (screenWidth === screenWidthPrevious && screenHeight === screenHeightPrevious) { return; }

	//If the width or height are not valid, sets a default one:
	if (screenWidth === 0 || screenHeight === 0) { screenWidth = 800; screenHeight = 600; }

	//Calculates the height according to the proportions:
	var ratio = 0.6;
	var proportionalHeight = screenWidth * ratio;

	//If the proportional height calculated is bigger than the screen height, we will center the image:
	var topToCenter = 0;
	if (proportionalHeight > screenHeight)
	{
		topToCenter = -parseInt((proportionalHeight - screenHeight) / 2);
	}
	else if (proportionalHeight < screenHeight)
	{
		topToCenter = parseInt((screenHeight - proportionalHeight) / 2);
	}

	//Resizes the main container:
	/*
	var screenContainer = document.getElementById("screens_container");
	if (screenContainer !== null)
	{
		screenContainer.style.top = topToCenter;
		screenContainer.width = screenWidth + "px";
		screenContainer.height = proportionalHeight + "px";
	}
	*/

	//Loops through all the elements:
	elementsIdToResizeLength = elementsIdToResize.length;
	var element;
	for (var x = 0; x < elementsIdToResizeLength; x++)
	{
		//If the element is not the current screen and we don't want to resize all, skips it:
		if (!all && elementsIdToResize[x] !== screenCurrent) { continue; }
		element = document.getElementById(elementsIdToResize[x]);
		if (element !== null)
		{
			//Applies new size to the element:
			element.style.width = screenWidth + "px"; //Width is always the same as width of the screen.
			element.style.height = proportionalHeight + "px"; //Applies the calculated propotional height.

			//If the calculated top is different than zero, we apply it:
			if (topToCenter !== 0) { element.style.top = topToCenter + "px"; }
		}
	}

	//Applies particular resizes depending on the current screen showing:
	/*
	if (screenCurrent === "queue_screen")
	{
		queueScreenResizeAll();
	}
	*/

	clearTimeout(portraitMessageHideTimeout);
	//If the screen is portrait mode, shows a notice to turn it to landscape:
	if (screenWidth < screenHeight)
	{
		showMessage(localize("LANDSCAPE_MODE_IS_BETTER"), "landscape_mode_message", "landscape_mode");
		//showElement("landscape_mode");
		portraitMessageHideTimeout = setTimeout(function() { showMessage("", "landscape_mode_message", "landscape_mode"); }, 5000);
	}
	else { showMessage("", "landscape_mode_message", "landscape_mode"); } //Hides the message to recommend landscape mode.

	//Changes the text size of "you are here":
	var youAreHereDiv = document.getElementById("queue_you_are_here");
	if (youAreHereDiv !== null)
	{
		youAreHereDiv.style.fontSize = parseInt(screenWidth / 60) + "px";
	}

	//Stores the current size for the next time:
	screenWidthPrevious = screenWidth;
	screenHeightPrevious = screenHeight;
}


//Function that returns the width of the screen:
function getScreenWidth()
{
	var screenWidth = 0;
	
	if (window && !isNaN(window.innerWidth) && window.innerWidth > 0)
	{
		screenWidth = window.innerWidth;
	}
	else if (document && document.body && document.body.clientWidth && !isNaN(document.body.clientWidth) && document.body.clientWidth > 0)
	{
		screenWidth = document.body.clientWidth;
	}
	else if (document && document.documentElement && document.documentElement.clientWidth && !isNaN(document.documentElement.clientWidth) && document.documentElement.clientWidth > 0)
   	{
		screenWidth = document.documentElement.clientWidth;
	}
	if (screenWidth < 0) { screenWidth = 320; }

	return screenWidth;
}


//Function that returns the height of the screen:
function getScreenHeight()
{
	var screenHeight = 0;

	if (window && !isNaN(window.innerHeight) && window.innerHeight > 0)
	{
		screenHeight = window.innerHeight;
	}
	else if (document && document.body && document.body.clientHeight && !isNaN(document.body.clientHeight) && document.body.clientHeight > 0)
	{
		screenHeight = document.body.clientHeight;
	}
	else if (document && document.documentElement && document.documentElement.clientHeight && !isNaN(document.documentElement.clientHeight) && document.documentElement.clientHeight > 0)
   	{
		screenHeight = document.documentElement.clientHeight;
	}
	if (screenHeight < 0) { screenHeight = 240; }

	return screenHeight;
}


//Synchronized setTimeout:
var setTimeoutLastTimes = Array();
function setTimeoutSynchronized(callbackFunction, timeMs)
{
	var currentTime = new Date().getTime();
	
	if (typeof(setTimeoutLastTimes[callbackFunction]) == "undefined" || setTimeoutLastTimes[callbackFunction] == null)
	{
		setTimeoutLastTimes[callbackFunction] = currentTime;
	}
	var lastTime = setTimeoutLastTimes[callbackFunction];
	
	var timeToCall = Math.max(0, timeMs - (currentTime - lastTime));
	var id = setTimeout(callbackFunction, timeToCall);
	lastTime = currentTime + timeToCall;
	setTimeoutLastTimes[callbackFunction] = lastTime;
	return id;
}