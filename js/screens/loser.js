// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that shows the loser screen (static):
function showLoserScreen() //Shows static screen saying thanks for playing.
{
	//NOTE: don't forget to stop all previous animations! (from both lottery queue screens)

	//Shows the lottery room in the background:
	showLotteryRoomScreen();

	//Introduces information about the prize in the right element:
	showPrizeInformation();

	//Show the loser screen container element:
	showScreen("loser_screen", true);

	//We have to hide the waiting message
	showWaitMessage("");
}