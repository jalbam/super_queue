<?php require_once "config.php"; if (!DEBUG_MODE) { exit("Overload test only allowed in debug mode!"); } ?><html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Overload test</title>
		<script src="js/CB_AJAX.js" language="javascript" type="text/javascript"></script>
		<script language="JavaScript" type="text/javascript">
		<!--
			var statusDiv;
			var informationDiv;
			var responsesDiv;

			var userIdInput;
			var numberCallsInput;
			var msBetweenCallsInput;
			var informationShowCheckbox;
			var responsesShowCheckbox;
			var responsesErrorShowCheckbox;
			
			var userIdDefault = 1;
			var numberCallsDefault = 10;
			var msBetweenCallsDefault = 3000;
			var informationShowDefault = true;
			var responsesShowDefault = true;
			var responsesErrorShowDefault = true;

			var applyInformationAndResponsesBufferMs = 2000;
			var informationShow = informationShowDefault;
			var responsesShow = responsesShowDefault;
			var responsesErrorShow = responsesErrorShowDefault;
			var testInterval;
			var testRunning = false;


			function init()
			{
				statusDiv = document.getElementById("test_status");
				informationDiv = document.getElementById("test_information");
				responsesDiv = document.getElementById("test_responses");

				userIdInput = document.getElementById("user_id");
				numberCallsInput = document.getElementById("number_calls");
				msBetweenCallsInput = document.getElementById("ms_between_calls");

				informationShowCheckbox = document.getElementById("information_show");
				responsesShowCheckbox = document.getElementById("responses_show");
				responsesErrorShowCheckbox = document.getElementById("responses_error_show");

				if (statusDiv !== null && informationDiv !== null && userIdInput !== null && numberCallsInput !== null && msBetweenCallsInput !== null && informationShowCheckbox !== null)
				{
					informationClear();
					informationLine("Starting overload test engine...");
					userIdInput.value = userIdDefault;
					numberCallsInput.value = numberCallsDefault;
					msBetweenCallsInput.value = msBetweenCallsDefault;
					informationShowCheckbox.checked = informationShowDefault;
					responsesShowCheckbox.checked = responsesShowDefault;
					responsesErrorShowCheckbox.checked = responsesErrorShowDefault;
					setInterval(applyInformationAndResponsesBuffer, applyInformationAndResponsesBufferMs);
					statusDiv.innerHTML = "stopped";
					informationLine("Overload test engine started.");
				}
			}

			var showedLastBuffers = false;
			function applyInformationAndResponsesBuffer()
			{
				if (testRunning === false)
				{
					if (showedLastBuffers === true)
					{
						informationBuffer = responseBuffer = "";
						return;
					}
					showedLastBuffers = true;
				}
				
				if (informationBuffer !== "") { informationDiv.innerHTML = informationBuffer + informationDiv.innerHTML; informationBuffer = ""; }
				if (responsesBuffer !== "") { responsesDiv.innerHTML = responsesBuffer + responsesDiv.innerHTML; responsesBuffer = ""; }
			}

			var informationLineNumber = 1;
			var informationBuffer = "";
			function informationLine(text)
			{
				if (informationShow === false) { return; }
				//informationDiv.innerHTML = "[" + informationLineNumber++ + "] " + text + "<br />" + informationDiv.innerHTML;
				informationBuffer = "[" + informationLineNumber++ + "] " + text + "<br />" + informationBuffer;
			}

			var responsesLineNumber = 1;
			var responsesBuffer = "";
			function responsesLine(text, error)
			{
				if (typeof(error) === "undefined" || error === null) { error = false; }
				if (responsesShow === false && error === false) { return; }
				else if (responsesErrorShow === false && error === true) { return; }
				//responsesDiv.innerHTML = "[" + responsesLineNumber++ + "] " + text + "<br />" + responsesDiv.innerHTML;
				responsesBuffer = "[" + responsesLineNumber++ + "] " + text + "<br />" + responsesBuffer;
			}

			function informationClear()
			{
				informationBuffer = responsesBuffer = "";
				informationDiv.innerHTML = responsesDiv.innerHTML = "";
				informationLineNumber = responsesLineNumber = 1;
			}

			function testStart()
			{
				showedLastBuffers = false;
				informationClear("");
				
				if (informationShowCheckbox !== null) { informationShow = informationShowCheckbox.checked; }
				if (responsesShowCheckbox !== null) { responsesShow = responsesShowCheckbox.checked; }
				if (responsesErrorShowCheckbox !== null) { responsesErrorShow = responsesErrorShowCheckbox.checked; }

				informationLine("Trying to start the test...");

				if (userIdInput !== null && numberCallsInput !== null && msBetweenCallsInput !== null)
				{
					userId = userIdInput.value;
					numberCalls = numberCallsInput.value;
					msBetweenCalls = msBetweenCallsInput.value;

					if (userId > 0 && numberCalls > 0 && msBetweenCalls > 0)
					{
						testStop();
						statusDiv.innerHTML = '<span class="running">running</span>';
						testRunning = true;
						testSetInterval(userId, numberCalls, msBetweenCalls);
					} else { informationLine("userId (" + userId + "), numberCalls(" + numberCalls + ") and msBetweenCalls(" + msBetweenCalls + ") must be greater than zero!"); }
				} else { informationLine("Some of the input elements is null!"); }
			}

			function testStop()
			{
				clearInterval(testInterval);
				informationLine("Test stopped.");
				testRunning = false;
				intervalCounter = 1;
				statusDiv.innerHTML = "stopped";
			}


			function testSetInterval(userId, numberCalls, msBetweenCalls)
			{
				informationLine("Setting interval for user " + userId + ", " + numberCalls + " times every " + msBetweenCalls + " milliseconds");
				testInterval = setInterval(function() { testDo(userId, numberCalls); }, msBetweenCalls);
			}

			var intervalCounter = 1;
			function testDo(userId, numberCalls)
			{
				informationLine("Starting interval #" + intervalCounter + " with " + numberCalls + " calls...");
				for (var x = 0; x < numberCalls; x++)
				{
					informationLine("* Doing call #" + (x + 1) + "...");
					testCall(userId, x + 1, intervalCounter);
				}
				informationLine("Interval #" + intervalCounter + " finished.");
				intervalCounter++;
			}


			function testCall(userId, callNumber, intervalNumber)
			{
				informationLine("** Inside a call #" + callNumber + " from interval #" + intervalNumber + "...");

				//Gets the id of the user:
				var variables = "action=getdata";
				variables += "&user_id=" + userId;
				var callbackFunctionOK = function (XHR)
				{
					var response = trim(XHR.responseText);

					responsesLine("@ SUCCESSFUL! Call #" + callNumber + " for interval #" + intervalNumber + ". Response:" + response);

					delete variables;
					delete response;
					delete XHR.onreadystatechange;
					delete callbackFunctionOK;
					delete callbackFunctionError;
					variables = response = callbackFunctionOK = callbackFunctionError = XHR.onreadystatechange = XHR = null;
				}
				var callbackFunctionError = function (XHR)
				{
					var response = trim(XHR.responseText);

					responsesLine("<span class=\"error\">@ ERROR! Call #" + callNumber + " for interval #" + intervalNumber + ". Response:" + response + "</span>", true);

					delete variables;
					delete response;
					delete XHR.onreadystatechange;
					delete callbackFunctionOK;
					delete callbackFunctionError;
					variables = response = callbackFunctionOK = callbackFunctionError = XHR.onreadystatechange = XHR = null;
				}
				XHR = CB_XHRForm("php/controller.php?using_ajax=yes", variables, "headers", "text", "UTF-8", "callbackFunction", callbackFunctionOK, callbackFunctionError);

				informationLine("** Call #" + callNumber + " from interval #" + intervalNumber + " finished.");
			}

			//Function that trims a string:
			function trim(str)
			{
				if (typeof(str) === "undefined" || str === null || !str) { str = ""; }
				else { str += ""; }
				return str.replace(/^\s+|\s+$/g, "");
			}
		// -->
		</script>
		<style type="text/css">
		<!--
			#test_status
			{
				font-family:arial;
				font-size:12px;
				color:#aa0000;
			}

			#test_status .running
			{
				font-family:arial;
				font-size:12px;
				color:#00aa00;
			}

			#test_information
			{
				text-align:left;
				font-family:arial;
				font-size:9px;
				color:#00aa00;
				border:1px solid #0000ff;
				width:100%;
				height:190px;
				overflow:auto;
			}
			#test_responses
			{
				text-align:left;
				font-family:arial;
				font-size:9px;
				color:#00aa00;
				border:1px solid #ff0000;
				width:100%;
				height:190px;
				overflow:auto;
			}

			#test_responses .error
			{
				color:#aa0000;
			}

			#information_show, #responses_show, #responses_error_show, .label_checkbox
			{
				cursor:hand;
				cursor:pointer;
			}
		-->
		</style>
	</head>
	<body onLoad="init();">
		<table width="100%" height="100%"><tr><td valign="middle">
			<center>
				<table width="100%">
					<tr>
						<td colspan="2" align="center">
							<h1>Overload test</h1>
						</td>
					</tr>
					<tr>
						<td align="right"><label for="user_id">User ID:</label></td>
						<td><input type="text" value="1" name="user_id" id="user_id" /></td>
					</tr>
					<tr>
						<td align="right"><label for="number_calls">Calls per time:</label></td>
						<td><input type="text" value="3" name="number_calls" id="number_calls" /></td>
					</tr>
					<tr>
						<td align="right"><label for="ms_between_calls">Milliseconds between calls:</label></td>
						<td><input type="text" value="1500" name="ms_between_calls" id="ms_between_calls" /></td>
					</tr>
					<tr>
						<td align="right"><label class="label_checkbox" for="information_show">Show information:</label></td>
						<td><input type="checkbox" name="information_show" id="information_show" checked /></td>
					</tr>
					<tr>
						<td align="right"><label class="label_checkbox" for="responses_show">Show normal responses:</label></td>
						<td><input type="checkbox" name="responses_show" id="responses_show" checked /></td>
					</tr>
					<tr>
						<td align="right"><label class="label_checkbox" for="responses_error_show">Show error responses:</label></td>
						<td><input type="checkbox" name="responses_error_show" id="responses_error_show" checked /></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="button" value="Stop" onClick="testStop();">
							<input type="button" value="Apply" onClick="testStart();">
						</td>
					</tr>
					<tr>
						<td>Status: <span id="test_status">loading...</span></td>
					</tr>
					<tr>
						<td>Information:</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<div id="test_information">Loading...</div>
						</td>
					</tr>
					<tr>
						<td>Responses:</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<div id="test_responses">Loading...</div>
						</td>
					</tr>
				</table>
			</center>
		</td></tr></table>
		<!--
			Super Queue
			* Chinese translation: 董双丽
			* Graphics: 乔安
			* Code: Joan Alba Maldonado
		-->
	</body>
</html>