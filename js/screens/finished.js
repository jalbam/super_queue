// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado


//Function that shows the game finished screen (static):
function showGameFinishedScreen() //Shows static screen with game ended message.
{
	//Hides any possible waiting message:
	showWaitMessage("");

	//Shows the finished screen:
	showScreen("game_finished_screen");
}