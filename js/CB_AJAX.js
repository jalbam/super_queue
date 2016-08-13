//CB_AJAX By Joan Alba Maldonado.

//Returns whether AJAX is available or not:
var CB_XHRIsSupportedLastReturn = null;
function CB_XHRIsSupported()
{
	if (CB_XHRIsSupportedLastReturn !== null) { return CB_XHRIsSupportedLastReturn; }
	else
	{
		CB_XHRIsSupportedLastReturn = (CB_getXHR() !== null);
		return CB_XHRIsSupportedLastReturn;
	}
}


//Returns an AJAX object:
var CB_XmlHttpVersionsIELastIndexWorked = null; //Defines the last index of CB_XmlHttpVersion that worked (for optimization).
//var CB_XmlHttpVersionsIE = ["Msxml2.XMLHTTP.7.0", "MSXML2.XmlHttp.6.0", "MSXML2.XmlHttp.5.0", "MSXML2.XmlHttp.4.0", "MSXML2.XmlHttp.3.0", "Msxml2.XMLHTTP", "Microsoft.XMLHTTP"];
var CB_XmlHttpVersionsIE = ["MSXML2.XmlHttp.6.0", "MSXML2.XmlHttp.3.0", "Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //XmlHttpVersions in order of preference for old IE versions.
var CB_XmlHttpVersionsIELength = CB_XmlHttpVersionsIE.length; //Length of CB_XmlHttpVersionsIE.
function CB_getXHR()
{
	if (typeof XMLHttpRequest !== "undefined") //if (window.XMLHttpRequest).
	{
		return new XMLHttpRequest();
	}
	else if (typeof(ActiveXObject) !== "undefined")
	{
		if (CB_XmlHttpVersionsIELastIndexWorked !== null)
		{
			return new ActiveXObject(CB_XmlHttpVersionsIE[CB_XmlHttpVersionsIELastIndexWorked]);
		}
		else
		{
			var XHR = null;
			for (var x = 0; x < CB_XmlHttpVersionsIELength; x++)
			{
				try
				{
					XHR = new ActiveXObject(CB_XmlHttpVersionsIE[x]);
					CB_XmlHttpVersionsIELastIndexWorked = x; //Defines this index as the last working one (for optimization).
					return XHR;
				}
				catch (e) {}
			}
			return null;
		}
	}
	return null;
}


//Gets an AJAX connection:
function CB_XHR(getOrPost, url, variables, headers, mimeType, responseType, callbackFunction, callbackFunctionOK, callbackFunctionError, asynchronous, XHR)
{
	//If the URL is empty, exits the function:
	url = trim(url);
	if (url == "") { return; }
	
	//If not given, sets the default parameters:
	getOrPost = trim(getOrPost).toUpperCase();
	if (getOrPost != "GET" && getOrPost != "POST") //Only allows to be "GET" or "POST":
	{
		getOrPost = "POST"; //Request method by default.
	}
	variables = trim(variables); //If it was unset or null, it will be an empty string.
	if (typeof(headers) != "object" || headers == null) //Sets headers by default:
	{
		headers = {};
	}
	mimeType = trim(mimeType); //If it was unset or null, it will be an empty string.
	responseType = trim(responseType).toLowerCase(); //If it was unset or null, it will be an empty string.
	if (typeof(asynchronous) == "undefined" || asynchronous == null) { asynchronous = true; } //Async by default.

	//Creates the AJAX object:
	if (typeof(XHR) === "undefined" || XHR === null || !XHR) { XHR = CB_getXHR(); }
	
	//If the XHR object is null, exits the function:
	if (XHR === null) { return; }
	
	//If there are variables and the method is GET, it adds them to the URL:
	if (variables != "" && getOrPost == "GET")
	{
		if (CB_indexOf(url, "?") == -1) { url += "?" + variables; } //There was not ? symbol in the URL, so we add it.
		else { url += "&" + variables; } //There was ? symbol in the URL, so we add the & symbol.
	}
	
	//Opens the connection:
	XHR.open(getOrPost, url, asynchronous);

	//Applies the given headers (if any):
	//if (mimeType != null) { headers["Content-Type"] = mimeType; }
	for (var headerName in headers)
	{
		XHR.setRequestHeader(headerName, headers[headerName]);
		//alert(headerName + "=>" + headers[headerName]);
	}

	//Applies the given mime type (if any):
	if (XHR.overrideMimeType && mimeType != "")
	{
		//XHR.overrideMimeType("text/plain; charset=UTF-8");
		XHR.overrideMimeType(mimeType);
	}
	
	//Applies the given response type (if any):
	if (typeof(XHR.responseType) != "undefined" && responseType != "")
	{
		XHR.responseType = responseType;
	}

	/*
	else
	{
		if (responseType == "" || responseType == "text")
		{
			XHR.overrideMimeType("text/plain; charset=UTF-8");
			alert("ey");
		}
		else if (responseType == "xml")
		{
			XHR.overrideMimeType("text/xml");
		}
		else if (responseType == "arraybuffer")
		{
			//XHR.overrideMimeType("text/plain; charset=x-user-defined");
		}
	}*/

	//If set, defines the callback function:
	if (typeof(callbackFunction) == "function")
	{
	//alert("aki1");
		XHR.onreadystatechange = function() { callbackFunction(XHR); };
	}
	//...otherwise, defines the callback functions for OK and error status:
	else
	{
		XHR.onreadystatechange = function()
		{
			if (XHR.readyState == 4)
			{//alert("aki2222");
				if (XHR.status == 200)
				{
				//alert("aki2");
					if (typeof(callbackFunctionOK) == "function")
					{
						callbackFunctionOK(XHR);
					}
				}
				//else if (XHR.readyState == 4 && (XHR.status == 0 || XHR.status == 502 || XHR.status == 12002 || XHR.status == 12029 || XHR.status == 12030 || XHR.status == 12031 || XHR.status == 12029 || XHR.status == 12152 || XHR.status == 12159))
				//else if (XHR.status != 12152 && XHR.status != 12030 && XHR.status != 0 && XHR.status != 12002 && XHR.status != 12007 && XHR.status != 12029 && XHR.status != 12031)
				else
				{
					//alert("eeeeeeee");
					if (typeof(callbackFunctionError) == "function")
					{
						callbackFunctionError(XHR);
					}
				}
				//else { alert("ierpiosejfsf: " + XHR.status); }
			}
		}
	}

	//Sends the XHR request:
	XHR.send(variables);
	
	//Returns the XHR object:
	return XHR;
}


//Function that calls a standard XHR request to send form data by POST (no files):
function CB_XHRForm(url, variables, headers, responseType, charset, callbackFunction, callbackFunctionOK, callbackFunctionError, XHR)
{
	//If not given, sets the default parameters:
	charset = trim(charset);
	if (typeof(charset) == "undefined" || charset == "") { charset = "UTF-8"; } //Default charset.
	if (typeof(headers) != "object" || headers == null)
	{
		headers = {
					"Content-Type" : "application/x-www-form-urlencoded; charset=" + charset,
					"Cache-Control" : "no-cache",
					"Pragma" : "no-cache"
				  };
	}

	//Makes the AJAX request function and returns the same: 
	return CB_XHR(
		"POST", //getOrPost
		url, //url
		variables, //variables
		headers, //headers
		null, //mimeType
		responseType, //responseType
		callbackFunction, //callbackFunction
		callbackFunctionOK, //callbackFunctionOK
		callbackFunctionError, //callbackFunctionError
		true, //asynchronous
		XHR
	);
}


//Function that calls a standard XHR request for a binary file:
function CB_XHRBinary(url, variables, headers, blobOrArrayBuffer, callbackFunction, callbackFunctionOK, callbackFunctionError)
{
	//If not given, sets the default parameters:
	blobOrArrayBuffer = trim(blobOrArrayBuffer).toLowerCase();
	if (typeof(headers) != "object" || headers == null)
	{
		headers = {
					"Content-Type" : "text/plain; charset=x-user-defined",
					"Cache-Control" : "no-cache",
					"Pragma" : "no-cache"
				  };
	}
	if (blobOrArrayBuffer != "arraybuffer" && blobOrArrayBuffer != "blob") //Only allows to be "blob" or "arraybuffer":
	{
		blobOrArrayBuffer = "arraybuffer";
	}
	
	//Makes the AJAX request function and returns the same:
	return CB_XHR(
		"GET", //getOrPost
		url, //url
		variables, //variables
		headers, //headers
		"text/plain; charset=x-user-defined", //mimeType
		blobOrArrayBuffer, //responseType
		callbackFunction, //callbackFunction
		callbackFunctionOK, //callbackFunctionOK
		callbackFunctionError, //callbackFunctionError
		true //asynchronous
	);
}


//Implementation of indexOf method for arrays in browsers that doesn't support it natively:
//* Polyfill source: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/indexOf
function CB_indexOf(that, searchElement, fromIndex, extendedDOM)
{
	if (typeof(extendedDOM) == "undefined" || extendedDOM == null) { extendedDOM = false; }
	
	if (Array.prototype.indexOf && !extendedDOM) { return Array.prototype.indexOf.call(that, searchElement, fromIndex); }

	if ( that === undefined || that === null )
	{
		throw new TypeError( '"that" is null or not defined' );
	}

	var length = that.length >>> 0; // Hack to convert object.length to a UInt32

	fromIndex = +fromIndex || 0;

	if (Math.abs(fromIndex) === Infinity) { fromIndex = 0; }

	if (fromIndex < 0)
	{
		fromIndex += length;
		if (fromIndex < 0) { fromIndex = 0; }
	}

	for (;fromIndex < length; fromIndex++)
	{
		if (that[fromIndex] === searchElement)
		{
			return fromIndex;
		}
	}

	return -1;
}