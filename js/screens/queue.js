// Super Queue
// * Chinese translation: 董双丽
// * Graphics: 乔安
// * Code: Joan Alba Maldonado



//Function that shows the queue (will be called every game loop):
var rankingMalePrevious = null;
var rankingFemalePrevious = null;
var firstTimeShowingQueue = true;
var youAreHereInterval;
function showQueueScreen()
{
	//Sets as we are performing an animation:
	showingAnimation = true;

	//If it is the first time:
	if (firstTimeShowingQueue === true)
	{
		//Hides and shows the corresponding instructions:
		hideElement("lottery_room_instructions");
		showElement("queue_instructions");

		//Resets the people and liberates all containers:
		people = [];
		containersFree = Array(MAXIMUM_PEOPLE_PER_QUEUE * 2 + 2); //Store true if it is free.
		containersFreeLength = containersFree.length;
		for (var x = 0; x < containersFreeLength; x++) { containersFree[x] = true; } //First time all are free.

		//liberateContainersFree();

		hideAllContainers();

		//Creates the character giving it the first container (so it will use always the same image):
		createPerson(userId, gameData.gender, null, null, (gameData.gender === "male") ? 0 : 1); //Male uses the container 0 and female the contaienr 1.

		//Resets the previous rankings (in case we return to the queue as in debug mode):
		rankingMalePrevious = null;
		rankingFemalePrevious = null;

		//Changes class name of "you are here" message every certain time:
		showElement("queue_you_are_here");
		clearInterval(youAreHereInterval);
		youAreHereInterval = setInterval(
					function()
					{
						if (screenCurrent !== "queue_screen") { return; }
						//If the user id is in the ranking, shows the "you are here" message (and alternates its class):
						if (personInRanking(userId, "male") || personInRanking(userId, "female"))
						{
							var youAreHereDiv = document.getElementById("queue_you_are_here");
							if (youAreHereDiv !== null)
							{
								youAreHereDiv.className = (youAreHereDiv.className == "") ? "alternative_class" : "";
							}
						}
						//...otherwise, if the person is not in ranking, hides the "you are here" message:
						else
						{
							hideElement("queue_you_are_here");
						}
					}, 300);

		//Starts getting the winners list and showing it:
		getWinners();
		showWinners();
	}

	//Checks whether there are differences between previous and new ranking:
	var maleRankingIsSame = areSameRanking(gameData.ranking.male, rankingMalePrevious);
	var femaleRankingIsSame = areSameRanking(gameData.ranking.female, rankingFemalePrevious);

	//If the rankings are the same, there is no need to do anything and exits:
	if (maleRankingIsSame && femaleRankingIsSame) { showingAnimation = false; return; }

	//If it is the first time, there is no previous ranking so we use the current one:
	if (rankingMalePrevious === null) { rankingMalePrevious = gameData.ranking.male; }
	if (rankingFemalePrevious === null) { rankingFemalePrevious = gameData.ranking.female; }

	//Shows the users in the queue according to the previous ranking:
	showPeopleQueue(rankingMalePrevious, "male");
	showPeopleQueue(rankingFemalePrevious, "female");

	//Liberates free people containers (and make people disappear):
	liberateContainersFree();

	//If the ranking is different as the previous one and is not the first time, shows an animation:
	if (!maleRankingIsSame)
	{
		personMoveAnimation(rankingMalePrevious, gameData.ranking.male, "male");
	}
	if (!femaleRankingIsSame)
	{
		personMoveAnimation(rankingFemalePrevious, gameData.ranking.female, "female");
	}

	//Shows the new queue after a while (to let previous animations finish, including the ones from liberate contaners):
	setTimeout(
				function()
				{
					//Shows the users in the queue according to the new ranking:
					showPeopleQueue(gameData.ranking.male, "male");
					showPeopleQueue(gameData.ranking.female, "female");

					//There will not be any animation more after a while:
					setTimeout(function() { showingAnimation = false; }, 2000);
				}, 2000);

	//Stores the processed data for the next loop:
	rankingMalePrevious = gameData.ranking.male;
	rankingFemalePrevious = gameData.ranking.female;

	//If it was the first time we show the queue:
	if (firstTimeShowingQueue === true)
	{
		//Show the queue container element for the first time:
		showScreen("queue_screen");

		//We have to hide the waiting message
		showWaitMessage("");
		firstTimeShowingQueue = false;
	}
}



//Function that performs the animation (recursive):
var personMoveAnimationTimeout = []; //Array with the timeouts for the animation steps.
var personMoveAnimationTimeoutMs = 10; //Milliseconds between one step and another.
var Xsteps = 0.2; //Quantity to move horizontally.
var Ysteps = 0.2; //Quantity to move vertically.
var peopleMoving = []; //Stack with the id of every person moving.
function personMoveAnimation(rankingPrevious, rankingCurrent, gender)
{
	//If any of the rankings is not defined or null, returns false:
	if (typeof(rankingPrevious) === "undefined" || rankingPrevious === null || !rankingPrevious) { return false; }
	if (typeof(rankingCurrent) === "undefined" || rankingCurrent === null || !rankingCurrent) { return false; }

	var rankingPreviousLength = rankingPrevious.length;
	var rankingCurrentLength = rankingCurrent.length;

	//Loops through the previous ranking:
	var personId;
	var Xfinal;
	var YFinal;
	var person;
	var Xdirection;
	var Ydirection;
	for (var x = 0; x < rankingPreviousLength; x++)
	{
		personId = rankingPrevious[x];
		//Loops through the current ranking:
		for (var y = 0; y < rankingCurrentLength; y++)
		{
			//If the person still in the current ranking but in different position:
			if (rankingCurrent[y] === personId && x !== y)
			{
				//Gets the person:
				person = findPerson(personId);	

				//If the person doesn't exists (not found), skips this loop:
				if (person === null) { continue; }

				//Calculates the final coordinates needed:
				Xfinal = getLeftByRankingPosition(y, gender);
				Yfinal = getTopByRankingPosition(y, gender);

				//If the person doesn't needs to move, skips this loop:
				if (Xfinal === person.x && Yfinal === person.y) { continue; }

				Xdirection = (parseFloat(Xfinal) > parseFloat(person.x)) ? "right" : "left";
				Ydirection = (parseFloat(Yfinal) > parseFloat(person.y)) ? "down" : "up";

				//Push the person in the array:
				peopleMoving.push(personId);

				//Starts performing the animation:
				new function (personId, gender, Xdirection, Ydirection, Xfinal, Yfinal) { //Needs to evaluate the value of the variables right now in the loop!
					personMoveAnimationTimeout[personId] = setTimeoutSynchronized(
																		function()
																		{
																			personMoveAnimationStep(personId, gender, Xdirection, Ydirection, Xfinal, Yfinal);
																		}
																		, personMoveAnimationTimeoutMs);
				}(personId, gender, Xdirection, Ydirection, Xfinal, Yfinal); 
			}

		}
	}

}



//Function that performs an animation step (called by personMoveAnimation):
function personMoveAnimationStep(personId, gender, Xdirection, Ydirection, Xfinal, Yfinal)
{
	//Gets the person:
	var person = findPerson(personId);	

	//If the person doesn't exists (not found), exits:
	if (person === null) { return; }

	//Defines whether the person has arrived to the destination:
	var personArrived = false;

	var Xpartial = person.x;
	var Ypartial = person.y;

	//Moves the person according to the X direction (being careful not to overtake the limit):
	if (Xdirection === "right" && parseFloat(person.x) + Xsteps <= parseFloat(Xfinal)) //Moves to right.
	{
		Xpartial = (parseFloat(person.x) + Xsteps) + "%";
	}
	else if (Xdirection === "left" && parseFloat(person.x) - Xsteps >= parseFloat(Xfinal)) //Moves to left.
	{
		Xpartial = (parseFloat(person.x) - Xsteps) + "%";
	}
	//...otherwise, the limit has been reached or overtaken:
	else { Xpartial = Xfinal; } //Fixes the position in order not to overtake the destiny.

	//Moves the person according to the Y direction (being careful not to overtake the limit):
	if (Ydirection === "down" && parseFloat(person.y) + Ysteps <= parseFloat(Yfinal)) //Moves to right.
	{
		Ypartial = (parseFloat(person.y) + Ysteps) + "%";
	}
	else if (Ydirection === "up" && parseFloat(person.y) - Ysteps >= parseFloat(Yfinal)) //Moves to left.
	{
		Ypartial = (parseFloat(person.y) - Ysteps) + "%";
	}
	//...otherwise, the limit has been reached or overtaken:
	else { Ypartial = Yfinal; } //Fixes the position in order not to overtake the destiny.

	//Draws the person with the new coordinates:
	drawPerson(personId, gender, Xpartial, Ypartial);

	//Sets whether the person has arrived or not:
	if (Xpartial === Xfinal && Ypartial === Yfinal)
	{
		personArrived = true;
	}

	//If the person arrived to the destination, pops it from the array:
	if (personArrived)
	{
		//One person less is moving:
		peopleMoving.pop();

		//Clers the timeout of this person:
		clearTimeout(personMoveAnimationTimeout[personId]);
	}
	//...otherwise, the function calls itself again:
	else
	{
		//Starts performing the animation:
		personMoveAnimationTimeout[personId] = setTimeoutSynchronized(
															function()
															{
																personMoveAnimationStep(personId, gender, Xdirection, Ydirection, Xfinal, Yfinal);
															}
															, personMoveAnimationTimeoutMs);
	}
}



//Function that checks whether two rankings are the same or not:
function areSameRanking(ranking1, ranking2)
{
	//If any of the rankings is not defined or null, returns false:
	if (typeof(ranking1) === "undefined" || ranking1 === null || !ranking1) { return false; }
	if (typeof(ranking2) === "undefined" || ranking2 === null || !ranking2) { return false; }

	var ranking1Length = ranking1.length;
	var ranking2Length = ranking2.length;

	//If they have different size, they are not the same ranking:
	if (ranking1Length !== ranking2Length) { return false; }

	for (var x = 0; x < ranking1Length; x++)
	{
		if (ranking1[x] !== ranking2[x]) { return false; }
	}

	return true;
}


//Function that shows the people on the queue:
function showPeopleQueue(ranking, gender)
{
	//Loops through the ranking and paints every person:
	var rankingLength = ranking.length;
	for (var x = 0; x < rankingLength; x++)
	{
		drawPerson(ranking[x], gender, getLeftByRankingPosition(x, gender), getTopByRankingPosition(x, gender));
	}
}


//Function that returns the exact x-coordinates a person should be for a given ranking position:
function getLeftByRankingPosition(rankingPosition, gender)
{
	//NOTE: the x-coordinates should depend on the ranking position!!!
	var x = 0;

	var initialSeparation = 16;
	var separation = 4.5;

	if (gender === "female") { initialSeparation = 9; separation = 4.8; }

	

	x = initialSeparation + (rankingPosition * separation) + "%";

	return x;
}

//Function that returns the exact y-coordinates a person should be for a given ranking position:
function getTopByRankingPosition(rankingPosition, gender)
{
	var y = "66%";

	//NOTE: every gender should have the same y-coordinates!!! So ranking position is useless so far.
	if (gender === "female") { y = "74%"; }
	
	return y;
}


//Functions that draws a person:
function drawPerson(personId, gender, x, y)
{
	//Gets the person from the array (if it doesn't exists, will be created):
	var person = getPerson(personId, gender);

	//If the person is not already there, draws the person:
	if (person["x"] !== x || person["y"] !== y || person["showing"] === false)
	{
		//Draws the person:
		var elementId = "person_container_" + person["container"];
		var element = document.getElementById(elementId);
		if (element !== null)
		{
			element.style.left = !isNaN(x) ? x + "px" : x;
			element.style.top = !isNaN(y) ? y + "px" : y;

			showElement(elementId); //ESTO DEBERIA SOLO HACERSE AL CREAR LA PERSONA!!!!!!

			//If the element was not showing before, shows the appearing animation:
			if (person["showing"] === false)
			{
				element.className = 'person_container_' + gender + ' appearing';
				setTimeout(function() { element.className = 'person_container_' + gender + ' appeared'; }, 100); //Needs to wait to change the class name after a previous change!

				//The person is showing now:
				person["showing"] = true;
			}

			//Changes the "title" property of the element with information about his person:
			if (DEBUG_MODE)
			{
				element.title = (userId === personId) ? personId + " " + localize("YOU_ARE_HERE") : personId;
				element.onclick = function() { changeUserId(personId); getGameData(); };
				element.style.cursor = "pointer";
			}

			//If the person is the player, shows the "you are here" message:
			if (userId === personId)
			{
				var youAreHereDiv = document.getElementById("queue_you_are_here");
				if (youAreHereDiv !== null)
				{
					marginLeft = 3;
					marginTop = 3;
					youAreHereDiv.style.left = !isNaN(x) ? (x + marginLeft) + "px" : (parseInt(x) + marginLeft) + "%";
					youAreHereDiv.style.top = !isNaN(y) ? (y - marginTop) + "px" : (parseInt(y) - marginTop) + "%";
				}
			}
		}

		//Sets the new coordinates for the person:
		person["x"] = x;
		person["y"] = y;
	}
}


//Function that creates a person in the people array:
var people = []; //Array which will store the data for the people.
function createPerson(personId, gender, x, y, containerNumber)
{
	//If the person id is not defined, exits:
	if (typeof(personId) === "undefined" || personId === null) { return; }

	//If the person gender is not defined, exits:
	if (typeof(gender) === "undefined" || gender === null) { return; }

	//If not defined, by default x, y are null:
	if (typeof(x) === "undefined" || !x) { x = null; }
	if (typeof(y) === "undefined" || !y) { y = null; }
	
	//If not defined, the person will have a new free container (if any):
	if (typeof(containerNumber) === "undefined" || containerNumber === null || containerNumber === false) { containerNumber = getFreeContainer(gender); }

	//If a container has been assigned, is not free anymore:
	if (containerNumber !== null)
	{
		containersFree[containerNumber] = false;
	}

	//Creates the person and returns that person:
	return people[people.length] = { "showing" : false, "id" : personId, "gender" : gender, "x" : x, "y" : y, "container" : containerNumber };
}


//Returns a person from the people array (if found):
function findPerson(personId)
{
	var peopleLength = people.length;
	for (var x = 0; x < peopleLength; x++)
	{
		if (people[x].id === personId) { return people[x]; }
	}
	return null;
}


//Function that returns a person (creates the person in the case it doesn't exists):
function getPerson(personId, gender)
{
	//Try to find that person:	
	var person = findPerson(personId);
	
	//If the person doesn't exists, creates it:
	if (person === null) { person = createPerson(personId, gender); }

	//If the person doesn't have any container, gives him/her one:
	if (person["container"] === null)
	{
		//If this person is the player, we prefer to use container 0 (male) and 1 (female):
		var preferredNumber;
		if (personId === userId) { preferredNumber = (gameData.gender === "male") ? 0 : 1; }
		
		//Gives this person a container:
		person["container"] = getFreeContainer(gender, preferredNumber);

		//If a container has been assigned, then it is not free anymore:
		if (person["container"] !== null)
		{
			containersFree[person["container"]] = false; //Now the container is not free.
		}
	}

	return person;
}


//Function that checks whether any containers are not used and liberate them:
var containersFree;// = Array(MAXIMUM_PEOPLE_PER_QUEUE * 2); //Store true if it is free.
//var containersFreeLength = containersFree.length;
//for (var x = 0; x < containersFreeLength; x++) { containersFree[x] = true; } //First time all are free.
function liberateContainersFree()
{
	//Loops through the people:
	peopleLength = people.length;
	var container;
	for (var x = 0; x < peopleLength; x++)
	{
		//If the person is not in the current ranking:
		if (!personInRanking(people[x]["id"], people[x]["gender"]))
		{
			//Liberates the used container (if any):
			container = people[x]["container"];
			if (container !== null)
			{
				//Liberates the container (marks it as free):
				containersFree[container] = true;
				people[x]["container"] = null;

				var elementId = "person_container_" + container;
				var element = document.getElementById(elementId);
				if (element !== null)
				{
					//If the element was showing before, shows the disappearing animation:
					if (people[x]["showing"] !== false)
					{
						element.className = 'person_container_' + people[x]["gender"] + ' appeared';
						new function(elementId, element, gender)
						{ //Needs to evaluate the value of the variables right now in the loop!
							setTimeout(
										function()
										{
											element.className = 'person_container_' + gender + ' appearing';
											//Hides that element container:
											setTimeout(function() { element.className = "person_container_" + gender + " appeared"; hideElement(elementId); }, 1500);
										}, 200); //Needs to wait to change the class name after a previous change!
						}(elementId, element, people[x]["gender"]);


						//The person is not showing now:
						people[x]["showing"] = false;
					}
				}
			}
		}
	}
}


//Function that returns a NUMBER of container if founds a free one (or returns null):
function getFreeContainer(gender, preferredNumber)
{
	var first = 2;
	var last = containersFreeLength - 1; //This number will never be even!

	//Female use odd container numbers:
	if (gender === "female") { first = 3; }
	//...otherwise, if it is male and the last is odd, we will use the last even container:
	else if (last % 2 !== 0) { last--; }

	//If we prefer a container number:
	if (typeof(preferredNumber) !== "undefined" && !isNaN(preferredNumber) && preferredNumber >= 0 && preferredNumber < containersFree.length)
	{
		//If the container is free, returns this container:
		if (containersFree[preferredNumber] === true) { return preferredNumber; }
	}

	//Looks for any free container and returns it:
	//for (var x = containersFreeLength - 1 - first; x >= first; x -= 2)
	for (var x = last; x >= first; x -= 2)
	{
		if (containersFree[x] === true) { return x; }
	} 

	return null;
}


//Returns whether a person is in a ranking or not:
function personInRanking(personId, gender)
{
	var ranking = gameData.ranking[gender];
	var rankingLength = ranking.length;
	for (var x = 0; x < rankingLength; x++)
	{
		if (ranking[x] === personId) { return true; }
	}
	return false;
}



//Function that hides all containers:
function hideAllContainers()
{
	var container;
	var gender;
	for (var x = 0; x < containersFreeLength; x++)
	{
		gender = (x % 2 === 0) ? "male" : "female";
		container = document.getElementById("person_container_" + x)

		if (container !== null)
		{
			container.className = 'person_container_' + gender + ' appearing';
		}
	}
}

/*
//Function that resizes all elements:
function queueScreenResizeAll()
{
	//Don't forget left, top (width and height are not needed).
	//NOTE: So far this is not necessary since we are using percentage values.
}
*/