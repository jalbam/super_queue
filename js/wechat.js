// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Modifies the options when the URL is shared:
var WECHAT_SHARE_IMAGE_URL = ""; //Address of the thumb image when it is shared.
var WECHAT_SHARE_TITLE = ""; //Title of the site when it is shared.
var WECHAT_SHARE_DESCRIPTION = ""; //Description of the site when it is shared.
var WECHAT_APPID = ""; //AppID (can be left blank).

//NOTE: to close the site we could use WeixinJSBridge.call('closeWindow'); (better with setTimeout).

//Adds the desired listeners for Wechat (Weixin):
try
{
	if (document.addEventListener)
	{
		document.addEventListener('WeixinJSBridgeReady', WECHAT_onBridgeReady, false);
	}
	else if (document.attachEvent)
	{
		document.attachEvent('WeixinJSBridgeReady', WECHAT_onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', WECHAT_onBridgeReady);
	}
} catch (e) { if (DEBUG_MODE) { showError("Error adding Wechat event listeners: " + e); } }



//Function that detects whether we are using Wechat or not:
function WECHAT_usingWechat()
{
	return (typeof(WeixinJSBridge) !== "undefined");
}


//Function to invoke when Wechat API is ready to change Wechat options:
var WECHAT_isBridgeReady = false; //Tells whether the Wechat Bridget is ready or not.
function WECHAT_onBridgeReady(link, imageAddress, title, description, appId)
{
	if (!WECHAT_usingWechat()) { return; }

	WECHAT_isBridgeReady = true;

	if (typeof(link) === "undefined" || link == null || !link || trim(link) === "") { link = location.href; }
	if (typeof(imageAddress) === "undefined" || imageAddress == null || !imageAddress || trim(imageAddress) === "") { imageAddress = WECHAT_SHARE_IMAGE_URL; }
	if (typeof(title) === "undefined" || title == null || !title || trim(title) === "") { title = WECHAT_SHARE_TITLE; }
	if (typeof(description) === "undefined" || description == null || !description || trim(description) === "") { description = WECHAT_SHARE_DESCRIPTION; }
	if (typeof(appId) === "undefined" || appId == null || !appId || trim(appId) === "") { appId = WECHAT_APPID; }

	try
	{
		//Hides Wechat toolbar:
		//WeixinJSBridge.call('hideToolbar');

		//Hides the option menu (share button):
		//WeixinJSBridge.call('hideOptionMenu'); //Don't hide it if you want to let people share!

		//Changes the options to share the site with a friend:
		WeixinJSBridge.on('menu:share:appmessage', function (argv) { WECHAT_shareFriend(link, imageAddress, title, description, appId); });
		
		//Changes the options to share the site in user's timeline:
		WeixinJSBridge.on('menu:share:timeline', function (argv) { WECHAT_shareTimeline(link, imageAddress, title, description); });
	        
		//Changes the options to share the site in Weibo:
		WeixinJSBridge.on('menu:share:weibo', function (argv) { WECHAT_shareWeibo(link, description); });

		//Changes the options to share the site in Facebook:
		WeixinJSBridge.on('menu:share:facebook', function (argv) { WECHAT_shareFacebook(link, description); });
	} catch (e) { if (DEBUG_MODE) { showError("Error setting Wechat events:" + e); } }
}



//Function that sets the options for sharing with a friend:
function WECHAT_shareFriend(link, imageAddress, title, description, appId)
{
	if (!WECHAT_usingWechat() || !WECHAT_isBridgeReady) { return; }

	if (typeof(link) === "undefined" || link == null || !link || trim(link) === "") { link = location.href; }
	if (typeof(imageAddress) === "undefined" || imageAddress == null || !imageAddress || trim(imageAddress) === "") { imageAddress = WECHAT_SHARE_IMAGE_URL; }
	if (typeof(title) === "undefined" || title == null || !title || trim(title) === "") { title = WECHAT_SHARE_TITLE; }
	if (typeof(description) === "undefined" || description == null || !description || trim(description) === "") { description = WECHAT_SHARE_DESCRIPTION; }
	if (typeof(appId) === "undefined" || appId == null || !appId || trim(appId) === "") { appId = WECHAT_APPID; }

	WeixinJSBridge.invoke
	(
		'sendAppMessage',
		{
    		"appid": appId,
			"img_url": imageAddress,
			"img_width": "200",
			"img_height": "200",
			"link": link,
			"desc": description,
			"title": title
		},
		function (res)
		{
			//_report('send_msg', res.err_msg);
		}
	);
}


//Function that sets the options for sharing in user's timeline:
function WECHAT_shareTimeline(link, imageAddress, title, description)
{
	if (!WECHAT_usingWechat() || !WECHAT_isBridgeReady) { return; }

	if (typeof(link) === "undefined" || link == null || !link || trim(link) === "") { link = location.href; }
	if (typeof(imageAddress) === "undefined" || imageAddress == null || !imageAddress || trim(imageAddress) === "") { imageAddress = WECHAT_SHARE_IMAGE_URL; }
	if (typeof(title) === "undefined" || title == null || !title || trim(title) === "") { title = WECHAT_SHARE_TITLE; }
	if (typeof(description) === "undefined" || description == null || !description || trim(description) === "") { description = WECHAT_SHARE_DESCRIPTION; }

	WeixinJSBridge.invoke
	(
		'shareTimeline',
		{
			"img_url": imageAddress,
			"img_width": "200",
			"img_height": "200",
			"link": link,
			"desc": description,
			"title": title
		},
		function (res)
		{
			//_report('timeline', res.err_msg);
		}
	);
}



//Function that sets the options for sharing in Weibo:
function WECHAT_shareWeibo(link, description)
{
	if (!WECHAT_usingWechat() || !WECHAT_isBridgeReady) { return; }

	if (typeof(link) === "undefined" || link == null || !link || trim(link) === "") { link = location.href; }
	if (typeof(description) === "undefined" || description == null || !description || trim(description) === "") { description = WECHAT_SHARE_DESCRIPTION; }

	WeixinJSBridge.invoke
	(
		'shareWeibo',
		{
			"content": description,
			"url": link,
		},
		function (res)
		{
			//_report('weibo', res.err_msg);
		}
	);
}



//Function that sets the options for sharing in Facebook:
function WECHAT_shareFacebook(link, description)
{
	if (!WECHAT_usingWechat() || !WECHAT_isBridgeReady) { return; }

	if (typeof(link) === "undefined" || link == null || !link || trim(link) === "") { link = location.href; }
	if (typeof(imageAddress) === "undefined" || imageAddress == null || !imageAddress || trim(imageAddress) === "") { imageAddress = WECHAT_SHARE_IMAGE_URL; }
	if (typeof(title) === "undefined" || title == null || !title || trim(title) === "") { title = WECHAT_SHARE_TITLE; }
	if (typeof(description) === "undefined" || description == null || !description || trim(description) === "") { description = WECHAT_SHARE_DESCRIPTION; }

	WeixinJSBridge.invoke
	(
		'shareFB',
		{
			"img_url" : imageAddress,
			"img_width" : "640",
			"img_height" : "640",
			"link" : link,
			"desc" : description,
			"title" : title
		},
		function (res)
		{
			//_report('weibo', res.err_msg);
		}
	);
}