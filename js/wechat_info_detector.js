// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


/*
	NOTE: This game should be open through:
		https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
		* Where:
		 	APPID is the Wechat AppId
		 	REDIRECT_URI should be the index.php of the game.
		 	SCOPE can be either snsapi_base (to get only OpenID) or snsapi_userinfo (to get more user info)
		 	STATE and #wechat_redirect are optional (the last one is required for 302 redirection).

		** Afther the user gives authorization permission, the page redirects to:
			redirect_uri/?code=CODE&state=STATE.
			...otherwise, only the state parameter is included in the URL, such as redirect_uri?state=STATE

		Information source: http://admin.wechat.com/wiki/index.php?title=User_Profile_via_Web
*/


//Constants (don't touch them, they will be modified by PHP!):
var WECHAT_APPID = ""; //Wechat AppID.
var WECHAT_APPSECRET = ""; //Wechat AppSecret.
var WECHAT_CODE = ""; //Wechat code (certificate to obtain the access token later).


//Variables (don't touch them, they will be obtained by AJAX!):
var WECHAT_accessToken = ""; //Access token.
var WECHAT_openID = ""; //User OpenID. There is only an unique id for every user.
var WECHAT_userInfo = ""; //User information obtained if all goes well (JSON object).


//Function that gets all Wechat info:
function WECHAT_getInfo(callback)
{
	//If not provided a callback function, by default calls init function:
	if (typeof(callback) !== "function") { callback = init; }

	//If we don't have the Wechat AppId, Secret and Code, exits:
	WECHAT_APPID = trim(WECHAT_APPID);
	WECHAT_APPSECRET = trim(WECHAT_APPSECRET);
	WECHAT_CODE = trim(WECHAT_CODE);
	if (WECHAT_APPID === "" || WECHAT_APPSECRET === "" || WECHAT_CODE === "")
	{
		callback.call(); //Calls the callback function.
		return;
	}

	//Shows the wait message:
	showWaitMessage(localize("WECHAT_GETTING_DATA"));


	//NOTE: After finishes, fills the userKeyword, tries to get user name and user password thanks to
	//		this keyword and calls the callback function when finishes:

	//Calls the function to get the access token and the OpenID:
	WECHAT_getAccessTokenAndOpenID(WECHAT_APPID, WECHAT_APPSECRET, WECHAT_CODE, callback);
}


//Function that gets the acess token and OpenID by the AppID, Secret and Code:
//* https://api.wechat.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
function WECHAT_getAccessTokenAndOpenID(appID, secret, code, callback)
{
	//NOTE: use ajax.php to override cross-domain call restrictions.
	
	//If any parameter needed is missed, exits (calling the callback function):
	if (typeof(callback) !== "function") { callback = init; } //By default, calls init function.
	if (typeof(appID) === "undefined" || appID === null || !appID) { callback.call(); return; }
	if (typeof(secret) === "undefined" || secret === null || !secret) { callback.call(); return; }
	if (typeof(code) === "undefined" || code === null || !code) { callback.call(); return; }
	if (appID === "" || secret === "" || code === "") { callback.call(); return; }

	var URL = "https://api.wechat.com/sns/oauth2/access_token";
	var variables = "appid=" + encodeURLValue(appID);
	variables += "&secret=" + encodeURLValue(secret);
	variables += "&code=" + code;
	variables += "&grant_type=authorization_code";
	URL = encodeURLValue(URL + "?" + variables); //Encodes the URL to pass it by URL to the PHP proxy.
	var headers = {
					"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8",
					"Cache-Control" : "no-cache",
					"Pragma" : "no-cache"
			  	};

	var callbackFunctionOK = function (XHR)
	{
		var allFine = false;
		var response = trim(XHR.responseText);
		//If the answer is not empty:
		if (response !== "")
		{
			//Tries to parse the data as a JSON object:
			try
			{
				eval("var object = " + response);

				//Example of successful response:
				/*
					{
					   "access_token":"ACCESS_TOKEN",
					   "expires_in":7200,
					   "refresh_token":"REFRESH_TOKEN",
					   "openid":"OPENID",
					   "scope":"SCOPE"
					}
				*/
				//Example of unsuccessful response:
				/*
					{"errcode":40029,"errmsg":"invalid code"}
				*/

				//If the response is successful:
				var dataValid = false;
				if (typeof(object) === "object" && typeof(object.access_token) !== "undefined" && typeof(object.openid) !== "undefined")
				{
					var accessToken = trim(object.access_token);
					var openID = trim(object.openid);

					if (accessToken !== "" && openID !== "")
					{
						//All has been fine:
						dataValid = allFine = true;

						//Sets the OpenID as the user keyword:
						userKeyword = openID;
						
						//Sets OpenID as user password:
						userPassword = openID;

						//Tries to get other user info:
						WECHAT_getUserInfo(accessToken, openID, callback);
					}
				}
				if (DEBUG_MODE && !dataValid) { showError(localize("WECHAT_DATA_IS_NOT_VALID") + ":<br />" + response); }
			}
			catch (e)
			{
				if (DEBUG_MODE) { showError(localize("WECHAT_ERROR_PARSING_DATA") + ":<br />" + e); }
			}
		}
		//...otherwise, shows an error in debug mode:
		else if (DEBUG_MODE) { showError(localize("WECHAT_GET_DATA_EMPTY")); }

		//If something went wrong:
		if (allFine === false)
		{
			//Hides any waiting message:
			showWaitMessage("");

			//Calls the callback function:
			callback.call();
		}
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);

		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("WECHAT_GET_DATA_FAILED") + ":<br />" + response); }

		//Hides any waiting message:
		showWaitMessage("");

		//Calls the callback function:
		callback.call();
	}
	CB_XHR("GET", "php/ajax.php?url=" + URL, null, headers, null, "text", "callbackFunction", callbackFunctionOK, callbackFunctionError, true);	
}



//Function that refreshes access token:
//* https://api.wechat.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN
function WECHAT_refreshAccessToken(appID, secret, code, callback)
{
	//NOTE: use ajax.php to override cross-domain call restrictions.
}



//Function that gets the user info by the access token and OpenID:
//* https://api.wechat.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID
function WECHAT_getUserInfo(accessToken, openID, callback)
{
	//NOTE: use ajax.php to override cross-domain call restrictions.

	//If any parameter needed is missed, exits (calling the callback function):
	if (typeof(callback) !== "function") { callback = init; } //By default, calls init function.
	if (typeof(accessToken) === "undefined" || accessToken === null || !accessToken) { callback.call(); return; }
	if (typeof(openID) === "undefined" || openID === null || !openID) { callback.call(); return; }

	var URL = "https://api.wechat.com/sns/userinfo";
	var variables = "access_token=" + encodeURLValue(accessToken);
	variables += "&openid=" + encodeURLValue(openID);
	URL = encodeURLValue(URL + "?" + variables); //Encodes the URL to pass it by URL to the PHP proxy.
	var headers = {
					"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8",
					"Cache-Control" : "no-cache",
					"Pragma" : "no-cache"
			  	};

	var callbackFunctionOK = function (XHR)
	{
		var response = trim(XHR.responseText);
		//If the answer is not empty:
		if (response !== "")
		{
			//Tries to parse the data as a JSON object:
			try
			{
				eval("var object = " + response);

				//Example of successful response:
				/*
					{
					   "openid":" OPENID",
					   " nickname": NICKNAME,
					   "sex":"1",
					   "province":"PROVINCE"
					   "city":"CITY",
					   "country":"COUNTRY",
					   "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
					   "privilege":[
					   "PRIVILEGE1"
					   "PRIVILEGE2"
					   ]
					}				*/
				//Example of unsuccessful response:
				/*
					{"errcode":40003,"errmsg":" invalid openid "}
				*/

				//If the response is successful:
				var dataValid = false;
				if (typeof(object) === "object" && typeof(object.nickname) !== "undefined" && typeof(object.nickname) !== "undefined")
				{
					var nickname = trim(object.nickname);
					var sex = trim(object.sex);

					//Sets the user gender:
					if (sex == 1) { userGender = "male"; }
					else if (sex == 2) { userGender = "female"; }

					//Sets the user name:
					if (nickname !== "")
					{
						dataValid = true;

						//Tries to get other user info:
						userName = nickname; //Sets nickname as user name.
					}
				}
				if (DEBUG_MODE && !dataValid) { showError(localize("WECHAT_DATA_IS_NOT_VALID") + ":<br />" + response); }
			}
			catch (e)
			{
				if (DEBUG_MODE) { showError(localize("WECHAT_ERROR_PARSING_DATA") + ":<br />" + e); }
			}
		}
		//...otherwise, shows an error in debug mode:
		else if (DEBUG_MODE) { showError(localize("WECHAT_GET_DATA_EMPTY")); }

		//Hides any waiting message:
		showWaitMessage("");

		//Calls the callback function:
		callback.call();
	}
	var callbackFunctionError = function (XHR)
	{
		var response = trim(XHR.responseText);

		//Shows the error and te registration form again:
		if (DEBUG_MODE) { showError(localize("WECHAT_GET_DATA_FAILED") + ":<br />" + response); }

		//Hides any waiting message:
		showWaitMessage("");

		//Calls the callback function:
		callback.call();
	}
	CB_XHR("GET", "php/ajax.php?url=" + URL, null, headers, null, "text", "callbackFunction", callbackFunctionOK, callbackFunctionError, true);	
}