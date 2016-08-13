// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that shows the winner screen (static):
function showWinnerScreen() //Shows static screen about the prize won.
{
	//NOTE: don't forget to stop all previous animations! (from both lottery queue screens)

	//Shows the lottery room in the background:
	showLotteryRoomScreen();

	//Introduces information about the prize in the right element:
	showPrizeInformation();

	//If the product has been given, shows it:
	if (gameData.prizeGiven) { showElement("product_already_given_container"); }
	//...otherwise, if a shop has been selected, shows the container with input for product given code:
	else if (trim(gameData.shopSelected) !== "" && document.getElementById("prize_given_code_container_" + gameData.shopSelected) !== null) { showElement("prize_given_code_container_" + gameData.shopSelected); }
	//...otherwise, shows the shop selector:
	else { showElement("shop_selector_container"); }

	//Show the winner screen container element:
	showScreen("winner_screen", true);

	//We have to hide the waiting message
	showWaitMessage("");
}