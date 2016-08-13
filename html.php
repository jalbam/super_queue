<html>
	<head>
		<title><?php echo localize("GAME_TITLE"); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="author" content="Joan Alba Maldonado">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<?php if (DEBUG_MODE) { ?>
			<link rel="stylesheet" type="text/css" href="css/debug.css">
		<?php } ?>
		<script src="js/CB_AJAX.js" language="javascript" type="text/javascript"></script>
		<?php if (WECHAT_INFO_DETECTOR_ENABLED) { ?>
			<script src="js/wechat_info_detector.js" language="javascript" type="text/javascript"></script>
		<?php } ?>
		<script src="js/wechat.js" language="javascript" type="text/javascript"></script>
		<script src="js/identify_user.js" language="javascript" type="text/javascript"></script>
		<script src="js/main.js" language="javascript" type="text/javascript"></script>
		<script src="js/game.js" language="javascript" type="text/javascript"></script>
		<script src="js/prizes.js" language="javascript" type="text/javascript"></script>
		<script src="js/screens/queue.js" language="javascript" type="text/javascript"></script>
		<script src="js/screens/lottery.js" language="javascript" type="text/javascript"></script>
		<script src="js/screens/loser.js" language="javascript" type="text/javascript"></script>
		<script src="js/screens/winner.js" language="javascript" type="text/javascript"></script>
		<script src="js/screens/finished.js" language="javascript" type="text/javascript"></script>
		<?php if (DEBUG_MODE) { ?>
			<script src="js/debug.js" language="javascript" type="text/javascript"></script>
		<?php } ?>
		<script language="javascript" type="text/javascript">
			var DEBUG_MODE = <?php echo (DEBUG_MODE) ? "true" : "false"; ?>;
			var DEBUG_MODE_PASSWORD = "<?php echo (DEBUG_MODE) ? DEBUG_MODE_PASSWORD : ""; ?>";

			var WECHAT_INFO_DETECTOR_ENABLED = <?php echo (WECHAT_INFO_DETECTOR_ENABLED) ? "true" : "false"; ?>;
			var WECHAT_APPID = "<?php echo WECHAT_APPID; ?>";
			var WECHAT_APPSECRET = "<?php echo WECHAT_APPSECRET; ?>";
			var WECHAT_CODE = "<?php echo WECHAT_CODE; ?>";

			var WECHAT_SHOW_SHARE_LINK = <?php echo (WECHAT_SHOW_SHARE_LINK) ? "true" : "false"; ?>;
			var WECHAT_SHARE_IMAGE_URL = "<?php echo WECHAT_SHARE_IMAGE_URL; ?>";
			var WECHAT_SHARE_TITLE = "<?php echo addslashes(WECHAT_SHARE_TITLE); ?>";
			var WECHAT_SHARE_DESCRIPTION = "<?php echo addslashes(WECHAT_SHARE_DESCRIPTION); ?>";

			var GAME_FINISHED = <?php echo (GAME_FINISHED) ? "true" : "false"; ?>;

			var USE_SIMPLE_LOGIN = <?php echo (USE_SIMPLE_LOGIN) ? "true" : "false"; ?>;
			var PASSWORD_IS_PHONE = <?php echo (PASSWORD_IS_PHONE) ? "true" : "false" ?>;

			var USER_NAME_CHARACTERS_MINIMUM = <?php echo (USER_NAME_CHARACTERS_MINIMUM); ?>;
			var USER_NAME_CHARACTERS_MAXIMUM = <?php echo (USER_NAME_CHARACTERS_MAXIMUM); ?>;
			var PASSWORD_CHARACTERS_MINIMUM = <?php echo (PASSWORD_CHARACTERS_MINIMUM); ?>;
			var PASSWORD_CHARACTERS_MAXIMUM = <?php echo (PASSWORD_CHARACTERS_MAXIMUM); ?>;
			var LANGUAGE_DEFAULT = "<?php echo LANGUAGE_DEFAULT; ?>";

			var AVOID_QUEUE = <?php echo (AVOID_QUEUE) ? "true" : "false"; ?>;
			var AVOID_QUEUE_GUESTS_NEEDED = <?php echo AVOID_QUEUE_GUESTS_NEEDED; ?>;

			var MAXIMUM_PEOPLE_PER_QUEUE = <?php echo MAXIMUM_PEOPLE_PER_QUEUE; ?>;

			var userName = "<?php echo $userName; ?>";
			var userPassword = "<?php echo $userPassword; ?>";
			var userKeyword = "<?php echo $userKeyword; ?>";
			var userHost = "<?php echo $userHost; ?>";
			var userGender = "<?php echo $userGender; ?>";
			
			var languageCurrent = "<?php echo $languageCurrent; ?>";
		</script>
	</head>
	<body>
		<?php if (DEBUG_MODE) { ?>
			<div id="DEBUG_full_transparent_layer" onClick="DEBUG_moveWithMouse(event, null, true);" onMouseUp="DEBUG_moveWithMouse(event, null, true);" style="visibility:hidden; display:none; position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:999;"></div>
			<div id="DEBUG_console" style="position:fixed; right:0px; bottom:0px; z-index:1000;">Debug mode</div>
			<div id="DEBUG_showHideboxes" style="visibility:hidden; display:none;">
				<button onClick="DEBUG_hideShowAllboxesToggler(); this.innerHTML = (this.innerHTML == 'Hide boxes') ? 'Show boxes' : 'Hide boxes';">Hide boxes</button>
				<button onClick="DEBUG_hideShowForegroundToggler();">Toggle foreground</button>
			</div>
			<div id="DEBUG_game_data" style="visibility:hidden; display:none;" onClick="DEBUG_moveWithMouse(event, null, true);" onMouseUp="DEBUG_moveWithMouse(event, null, true);">
				<div onMouseDown="DEBUG_moveWithMouse(event, this.parentNode);" onMouseUp="DEBUG_moveWithMouse(event, null, true);" style="position:absolute; left:2px; top:2px; cursor:crosshair;" onMouseOver="this.style.color = '#000000';" onMouseOut="this.style.color = '#ffffff';">[+]</div>
				<div onClick="DEBUG_hideShowBox(document.getElementById('DEBUG_game_data')); this.innerHTML = (this.innerHTML == '[hide]') ? '[show]' : '[hide]';" style="position:absolute; right:2px; top:2px; cursor:pointer;" onMouseOver="this.style.color = '#000000';" onMouseOut="this.style.color = '#ffffff';">[hide]</div>
				<div id="DEBUG_game_data_text"></div>
			</div>
			<div id="DEBUG_game_state" onClick="DEBUG_hideShowBox(this);" style="visibility:hidden; display:none;" onClick="DEBUG_moveWithMouse(event, null, true);" onMouseUp="DEBUG_moveWithMouse(event, null, true);">
				<div onMouseDown="DEBUG_moveWithMouse(event, this.parentNode);" onMouseUp="DEBUG_moveWithMouse(event, null, true);" style="position:absolute; left:2px; top:2px; cursor:crosshair;" onMouseOver="this.style.color = '#000000';" onMouseOut="this.style.color = '#ffffff';">[+]</div>
				<div id="DEBUG_game_state_text"></div>
			</div>
			<div id="DEBUG_game_god_controls" style="visibility:hidden; display:none;" onClick="DEBUG_moveWithMouse(event, null, true);" onMouseUp="DEBUG_moveWithMouse(event, null, true);">
				<div onMouseDown="DEBUG_moveWithMouse(event, this.parentNode);" onMouseUp="DEBUG_moveWithMouse(event, null, true);" style="position:absolute; left:2px; top:2px; cursor:crosshair;" onMouseOver="this.style.color = '#000000';" onMouseOut="this.style.color = '#ffffff';">[+]</div>
				<div onClick="DEBUG_hideShowBox(document.getElementById('DEBUG_game_god_controls')); this.innerHTML = (this.innerHTML == '[hide]') ? '[show]' : '[hide]';" style="position:absolute; right:2px; top:2px; cursor:pointer;" onMouseOver="this.style.color = '#000000';" onMouseOut="this.style.color = '#ffffff';">[hide]</div>
				<input type="button" value="Get a guest" onClick="DEBUG_getAGuest();">
				<input type="button" value="Lose a guest" onClick="DEBUG_loseAGuest();">
				<input type="button" value="Add new male to queue" onClick="DEBUG_insertNewUserQueue(null, 'male');">
				<input type="button" value="Add new female to queue" onClick="DEBUG_insertNewUserQueue(null, 'female');">
				<input type="button" value="Go to queue" onClick="DEBUG_goToQueue();">
				<input type="button" value="Go to lottery room" onClick="DEBUG_goToLotteryRoom();">
				<input type="button" value="Get a partner" onClick="DEBUG_getAPartner();">
				<input type="button" value="Lose partner" onClick="DEBUG_losePartner();">
				<input type="button" value="Give partner a lottery result" onClick="DEBUG_givePartnerLotteryResult();">
				<input type="button" value="Become winner" onClick="DEBUG_becomeWinner();">
				<input type="button" value="Become loser" onClick="DEBUG_becomeLoser();">
				<input type="button" value="Give partner to everyone in lottery room" onClick="DEBUG_givePartnerToEveryone();">
				<input type="button" value="End game" onClick="DEBUG_endGame();">
				<select name="DEBUG_prizeSelector" id="DEBUG_prizeSelector" onChange="if (this.value !== '-' && confirm('Do you want to win ' + this.value + '?')) { DEBUG_winPrize(this.value); } else { this.selectedIndex = 0; }">
					<option value="-">--- win a prize ---</option>
					<?php
						foreach ($prizeTypes as $prizeType => $array)
						{
							echo '<option value="' . $prizeType . '">' . $prizeType . " (" . localize($prizeType) . ")" . '</option>';
						}
					?>
				</select>
				<br />
				<input type="button" value="Copy current queue" onClick="if (typeof(gameData) !== 'undefined' &&  typeof(gameData.ranking) !== 'undefined' && typeof(gameData.ranking.male) !== 'undefined') { document.getElementById('DEBUG_new_ranking_male').value = gameData.ranking.male.join(', '); }" />
				<label for="DEBUG_new_ranking_male">
					New male ranking: <input type="text" name="DEBUG_new_ranking_male" id="DEBUG_new_ranking_male" value="" size="40" />
				</label>
				<label for="DEBUG_new_ranking_male_apply"><input type="checkbox" name="DEBUG_new_ranking_male_apply" id="DEBUG_new_ranking_male_apply" /> Apply</label>
				<br />
				<input type="button" value="Copy current queue" onClick="if (typeof(gameData) !== 'undefined' &&  typeof(gameData.ranking) !== 'undefined' && typeof(gameData.ranking.female) !== 'undefined') { document.getElementById('DEBUG_new_ranking_female').value = gameData.ranking.female.join(', '); }" />
				<label for="DEBUG_new_ranking_female">
					New female ranking: <input type="text" name="DEBUG_new_ranking_female" id="DEBUG_new_ranking_female" value="" size="40" />
				</label>
				<label for="DEBUG_new_ranking_female_apply"><input type="checkbox" name="DEBUG_new_ranking_female_apply" id="DEBUG_new_ranking_female_apply" /> Apply</label>
				<br />
				Take the personality of an user of the world:
				<select name="DEBUG_worldSelector" id="DEBUG_worldSelector" onChange="if (this.value !== '-' && confirm('Do you want to jumpt to world #' + this.value + '?')) { DEBUG_becomeUserWorld(this.value); } else { this.selectedIndex = 0; }">
					<option value="-">---</option>
				</select>
			</div>
		<?php } ?>
		<div id="errors" style="visibility:hidden; display:none;" onDblClick="hideElement('errors');"></div>
		<div id="wait" style="visibility:visible; display:block;">
			<img src="img/wait_background.jpg" style="position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:1;" alt="" />
			<table id="wait_table" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"  style="position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:2;">
				<tr>
					<td id="wait_message" valign="middle">
						<?php echo localize("LOADING"); ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="landscape_mode" style="visibility:hidden; display:none;">
			<table id="landscape_mode_table" border="0" cellspacing="0" cellpadding="0"  style="width:100%; z-index:2;">
				<tr>
					<td id="landscape_mode_message" valign="middle">
					</td>
				</tr>
			</table>
		</div>
		<div id="registration_form_container" style="visibility:hidden; display:none;">
			<img src="img/registration_form_background.jpg" style="position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:1;" alt="" />
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:2;">
				<tr>
					<td class="registration_form_td">
						<div id="registration_form_errors" style="visibility:hidden; display:none;"></div>
						<form id="registration_form" onSubmit="registrationFormProcess(); return false;">
						<table style="text-align:center; display:inline;">
							<tr>
								<?php if (!USE_SIMPLE_LOGIN) { ?>
								<td class="registration_form_td" style="text-align:left;">
									<label for="form_user"><?php echo localize("FORM_USER_NAME"); ?>:</label>
								</td>
								<?php } ?>
								<td class="registration_form_td" style="text-align:left;">
									<?php
										$userNameInputStyle = "";
										if (USE_SIMPLE_LOGIN)
										{
											$userNameInputStyle = ' style="visibility:hidden; display:none;"';
										}
										else if (USE_DEFINED_NAMES_ON_LOGIN)
										{
											echo '<select onChange="changeUserNameInput(this.value);" class="user_name_selector">';
												echo '<option value="-">--- ' . localize("CHOOSE_A_NAME") . ' ---</option>';
												$x = 1;
												while (isset($lang["DEFINED_USER_NAMES_" . $x]))
												{
													echo '<option value="' . localize("DEFINED_USER_NAMES_" . $x) . '">' . localize("DEFINED_USER_NAMES_" . $x) . '</option>';
													$x++;
												}
											echo '</select>';
											$userNameInputStyle = ' style="visibility:hidden; display:none;"';
										}
										echo '<input type="text" name="form_user" id="form_user" class="registration_form_input"' . $userNameInputStyle . ' />';
									?>
								</td>
							</tr>
							<tr>
								<td class="registration_form_td" style="text-align:left;">
									<label for="form_password"><?php echo localize("FORM_PASSWORD"); ?>:</label>
								</td>
								<td class="registration_form_td" style="text-align:left;">
									<input type="text" name="form_password" id="form_password" class="registration_form_input" />
								</td>
							</tr>
							<tr>
								<td class="registration_form_td" style="text-align:left;">
									<?php echo localize("FORM_GENDER"); ?>:
								</td>
								<td class="registration_form_td" style="text-align:left;">
									<label for="form_male">
										<input type="radio" id="form_male" name="gender" value="male" class="registration_form_radio" />
										<img src="img/male.png" id="registration_form_male_image" alt="<?php echo localize("FORM_MALE"); ?>" title="<?php echo localize("FORM_MALE"); ?>" onClick="registrationFormSelectGender('male');" />
									</label>
									<label for="form_female">
										<input type="radio" id="form_female" name="gender" value="female" class="registration_form_radio" />
										<img src="img/female.png" id="registration_form_female_image"  alt="<?php echo localize("FORM_FEMALE"); ?>" title="<?php echo localize("FORM_FEMALE"); ?>" onClick="registrationFormSelectGender('female');" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="registration_form_td" style="text-align:center;">
									<!-- <input type="submit" value="<?php echo localize("FORM_SEND"); ?>" id="form_button" /> -->
									<input type="submit" value="<?php echo localize("FORM_SEND"); ?>" id="form_button" class="registration_form_button" />
									<div id="host_user_container" style="visibility:hidden; display:none;">
										<?php echo localize("YOUR_HOST_USER"); ?>:
										<span id="host_user"></span>
									</div>
								</td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
		<div id="screens_container" style="visibility:hidden; display:none;">
			<img src="img/screens_background.png" style="position:fixed; left:0px; top:0px; width:100%; height:100%;" alt="" />
			<div id="game_finished_screen" style="visibility:hidden; display:none;">
				<img src="img/game_finished_background.png" style="position:absolute; left:0px; top:0px; width:100%; height:100%;" alt="" />
				<table align="center" width="100%" height="100%"><tr><td valign="middle" align="center">
					<?php echo localize("GAME_FINISHED"); ?>
				</td></tr></table>
			</div>
			<div id="loser_screen" style="visibility:hidden; display:none;">
				<img src="img/loser_background.png" style="position:absolute; left:0px; top:0px; width:100%; height:100%;" alt="" />
				<table align="center" width="100%" height="100%">
					<?php if (localize("LOSER_TEXT") !== "") { ?>
					<tr><td valign="middle" align="center">
						<?php echo localize("LOSER_TEXT"); ?>
					</td></tr>
					<?php } ?>
					<tr><td valign="middle" align="center">
						<div id="shop_selector_losers_container" style="visibility:visible; display:block;">
							<?php echo localize("THESE_ARE_SHOPS_ACCEPT_COUPONS"); ?>:
							<br />
							<?php
								//Lists the cities:
								echo '<select onChange="changeCitySelector(this.value, true);" class="shop_selector">';
								echo '<option value="-">--- ' . localize("CHOOSE_A_CITY") . ' ---</option>';

								$citiesListed = Array();
								$shopsByCity = Array();
								$cityCounter = 0;
								foreach ($shops as $shop => $array)
								{
									//Only if the shop accepts coupons:
									if ($array["accepts_coupons"])
									{
										if (!in_array($array["city"], $citiesListed))
										{
											$citiesListed[] = $array["city"];
											echo '<option value="' . $cityCounter . '">' . $array["city"] . '</option>';
											$cityCounter++;
										}
										if (!isset($shopsByCity[$array["city"]])) { $shopsByCity[$array["city"]] = Array(); }
										$shopsByCity[$array["city"]][] = $shop;
									}
								}

								echo '</select> ';

								//Makes a list of shops per city:
								$cityCounter = 0;
								foreach ($citiesListed as $city)
								{
									echo '<select id="shops_city_' . $cityCounter++ . '_losers" onChange="showShopInformation(this.value, true);" style="visibility:hidden; display:none;" class="shop_selector">';
									echo '<option value="-">--- ' . localize("CHOOSE_A_SHOP") . ' ---</option>';
									foreach ($shopsByCity[$city] as $shop)
									{
										$shopName = $shops[$shop]["name"];
										$shopAddress = $shops[$shop]["address"];
										//echo '<option value="' . $shop . '">[' . $shopName . "] " . $shopAddress . '</option>';
										echo '<option value="' . $shop . '">' . $shopAddress . '</option>';
									}
									echo '</select>';
								}

								//Creates the containers with the information of the shop:
								foreach ($shops as $shop => $array)
								{
									//Only if the shop accepts coupons:
									if ($array["accepts_coupons"])
									{
										echo ' <div id="shop_information_' . $shop . '_losers" style="visibility:hidden; display:none;" class="shop_information">';
											//echo '<input type="button" value="' . localize("SELECT_SHOP_BUTTON") . '" onClick="selectShop(\'' . $shop . '\');" class="shop_selector_button" />';
											echo '<br />';
											echo $shops[$shop]["name"];
											echo ' (';
											echo $shops[$shop]["telephone"];
											echo ')';
										echo '</div>';
									}
								}
							?>
						</div>
					</tr></td>
					<tr><td valign="top" align="center" id="loser_screen_prize_information"></td></tr>
				</table>
			</div>
			<div id="winner_screen" style="visibility:hidden; display:none;">
				<img src="img/winner_background.png" style="position:absolute; left:0px; top:0px; width:100%; height:100%;" alt="" />
				<table align="center" width="100%" height="100%">
					<?php if (localize("WINNER_TEXT") !== "") { ?>
					<tr><td valign="middle" align="center">
						<?php echo localize("WINNER_TEXT"); ?>
					</td></tr>
					<?php } ?>
					<tr><td valign="middle" align="center">
						<div id="shop_selector_container" style="visibility:hidden; display:none;">
							<?php echo localize("WHERE_WILL_YOU_PICK_UP_THE_PRIZE"); ?>
							<br />
							<?php
								//Lists the cities:
								echo '<select onChange="changeCitySelector(this.value);" class="shop_selector">';
								echo '<option value="-">--- ' . localize("CHOOSE_A_CITY") . ' ---</option>';

								$citiesListed = Array();
								$shopsByCity = Array();
								$cityCounter = 0;
								foreach ($shops as $shop => $array)
								{
									if (!in_array($array["city"], $citiesListed))
									{
										$citiesListed[] = $array["city"];
										echo '<option value="' . $cityCounter . '">' . $array["city"] . '</option>';
										$cityCounter++;
									}
									if (!isset($shopsByCity[$array["city"]])) { $shopsByCity[$array["city"]] = Array(); }
									$shopsByCity[$array["city"]][] = $shop;
								}

								echo '</select> ';

								//Makes a list of shops per city:
								$cityCounter = 0;
								foreach ($citiesListed as $city)
								{
									echo '<select id="shops_city_' . $cityCounter++ . '" onChange="showShopInformation(this.value);" style="visibility:hidden; display:none;" class="shop_selector">';
									echo '<option value="-">--- ' . localize("CHOOSE_A_SHOP") . ' ---</option>';
									foreach ($shopsByCity[$city] as $shop)
									{
										$shopName = $shops[$shop]["name"];
										$shopAddress = $shops[$shop]["address"];
										//echo '<option value="' . $shop . '">[' . $shopName . "] " . $shopAddress . '</option>';
										echo '<option value="' . $shop . '">' . $shopAddress . '</option>';
									}
									echo '</select>';
								}

								//Creates the containers with the information of the shop:
								foreach ($shops as $shop => $array)
								{
									echo ' <div id="shop_information_' . $shop . '" style="visibility:hidden; display:none;" class="shop_information">';
										echo '<input type="button" value="' . localize("SELECT_SHOP_BUTTON") . '" onClick="selectShop(\'' . $shop . '\');" class="shop_selector_button" />';
										echo '<br />';
										echo $shops[$shop]["name"];
										echo ' (';
										echo $shops[$shop]["telephone"];
										echo ')';
									echo '</div>';
								}
							?>
						</div>
						<?php
							foreach ($shops as $shop => $array)
							{
								echo '<div id="prize_given_code_container_' . $shop . '" style="visibility:hidden; display:none;" class="prize_given_code_container">';
									echo localize("SHOP_SELECTED_VISIT") . ' <span class="prize_given_shop_name">' . $shops[$shop]["name"] . '</span> ' . localize("SHOP_SELECTED_AT") . ' <span class="prize_given_shop_address">' . $shops[$shop]["address"] . '</span> (<span class="prize_given_shop_telephone">' . $shops[$shop]["telephone"] . '</span>) ' . localize("SHOP_SELECTED_AND_SHOW_SCREEN");
									echo '<br />';
									echo '<span class="seller_code_text">' . localize("SELLER_CODE") . ':</span> ';
									echo '<input type="text" id="seller_code_' . $shop . '" value="" class="prize_given_code_input"> ';
									echo '<input type="button" value="' . localize("MARK_AS_GIVEN") . '" class="prize_given_button" onClick="markProductAsGiven();">';
								echo '</div>';
							}
						?>
						<div id="product_already_given_container" style="visibility:hidden; display:none;">
							<?php echo localize("PRIZE_GIVEN"); ?>
						</div>
					</tr></td>
					<tr><td valign="top" align="center" id="winner_screen_prize_information"></td></tr>
				</table>
			</div>
			<div id="lottery_room_instructions">
				<div id="lottery_room_instructions_close_button" title="<?php echo localize("CLOSE_BUTTON_TITLE"); ?>" onClick="hideElement('lottery_room_instructions');">x</div>
				<div style="width:88%;"><?php echo localize("LOTTERY_ROOM_INSTRUCTIONS"); ?></div>
			</div>
			<div id="lottery_room_screen" style="visibility:hidden; display:none;">
				<img src="img/lottery_room_background.jpg" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:1;" alt="" />
				<img src="img/lottery_room_foreground.png" id="lottery_room_foreground" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:4;" alt="" />
				<div id="finalist_male">
					<img src="img/lottery/finalist_male.png" id="finalist_male_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_male_happy.gif" id="finalist_male_happy_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_male_sad.gif" id="finalist_male_sad_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_male_unknown.png" id="finalist_male_unknown_image" style="visibility:hidden; display:none;" />
				</div>
				<div id="finalist_female">
					<img src="img/lottery/finalist_female.png" id="finalist_female_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_female_happy.gif" id="finalist_female_happy_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_female_sad.gif" id="finalist_female_sad_image" style="visibility:hidden; display:none;" />
					<img src="img/lottery/finalist_female_unknown.png" id="finalist_female_unknown_image" style="visibility:hidden; display:none;" />
				</div>
				<div id="roulette_male" style="visibility:hidden; display:none;"><img src="img/lottery/roulette_male.png" id="roulette_male_image" /></div>
				<div id="roulette_male_clickable" style="visibility:hidden; display:none;" onClick="playLottery();"><img src="img/lottery/roulette_male_clickable.png" id="roulette_male_image_clickable" /></div>
				<div id="roulette_female" style="visibility:hidden; display:none;"><img src="img/lottery/roulette_female.png" id="roulette_female_image" /></div>
				<div id="roulette_female_clickable" style="visibility:hidden; display:none;" onClick="playLottery();"><img src="img/lottery/roulette_female_clickable.png" id="roulette_female_image_clickable" /></div>
				<div id="roulette_result_male">
					<div id="roulette_result_text_male"></div>
					<input type="button" align="center" id="roulette_play_button_male" onClick="playLottery();" value="<?php echo localize('LOTTERY_ROOM_PLAY'); ?>" style="visibility:hidden; display:none;" />
				</div>
				<div id="roulette_result_female">
					<div id="roulette_result_text_female"></div>
					<input type="button" id="roulette_play_button_female" onClick="playLottery();" value="<?php echo localize('LOTTERY_ROOM_PLAY'); ?>" style="visibility:hidden; display:none;" />
				</div>
				<div id="roulette_result_icon_male"></div>
				<div id="roulette_result_icon_female"></div>
			</div>
			<div id="queue_instructions">
				<div id="queue_instructions_close_button" title="<?php echo localize("CLOSE_BUTTON_TITLE"); ?>" onClick="hideElement('queue_instructions');">x</div>
				<div style="width:88%;"><?php echo localize("QUEUE_INSTRUCTIONS"); ?></div>
				<div id="share_link"></div>
			</div>
			<div id="queue_screen" style="visibility:hidden; display:none;">
				<div id="winners_list">
					<div id="winners_list_title"><?php echo localize("WINNERS_LIST_TITLE"); ?>:</div>
					<div id="winners_list_message"><?php echo localize("WINNERS_LIST_LOADING"); ?></div>
				</div>
				<img src="img/queue_background.jpg" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:1;" alt="" />
				<img src="img/queue_foreground.png" id="queue_screen_foreground" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:5;" alt="" />
				<div id="queue_you_are_here"><?php echo localize("YOU_ARE_HERE"); ?></div>
				<?php
					//Creates the container elements for people in the queue:
					$style = "visibility:hidden; display:none;";

					$peopleImagesDirectory = "img/queue/";
					$maleImages = getImagesDirectory($peopleImagesDirectory . "male/");
					$femaleImages = getImagesDirectory($peopleImagesDirectory . "female/");
					$imagePersonPlayerMale = "male_main_user.gif"; //Male players will always use this image.
					$imagePersonPlayerFemale = "female_main_user.gif"; //Female players will always use this image.
					$gender = "male";
					for ($x = 0; $x < MAXIMUM_PEOPLE_PER_QUEUE * 2 + 2; $x++)
					{
						//The first loop, always use the first image (this image will be used for the player only)
						if ($x === 0) { $imagePerson = $peopleImagesDirectory . $imagePersonPlayerMale; }
						else if ($x === 1) { $imagePerson = $peopleImagesDirectory . $imagePersonPlayerFemale; }
						else
						{
							do
							{
								if ($gender === "male")
								{
									$imagePerson = $maleImages[rand(0, sizeof($maleImages) - 1)];
								} else { $imagePerson = $femaleImages[rand(0, sizeof($femaleImages) - 1)]; }
							} while ($imagePerson === $imagePersonPlayerMale || $imagePerson === $imagePersonPlayerFemale);
						}
						$innerHTML = '<img src="' . $imagePerson . '" id="person_image_' . $x . '" class="person_image_' . $gender . '" />';
						echo '<div id="person_container_' . $x . '" class="person_container_' . $gender . '" style="' . $style . '">' . $innerHTML . '</div>';

						$gender = ($x % 2 == 0) ? "female" : "male";
					}
				?>
			</div>
		</div>
		<?php
			//Creates the elements with the localized strings:
			$style = "visibility:hidden; display:none;";
			echo '<div id="language_default" style="' . $style . '">' . LANGUAGE_DEFAULT . '</div>';
			echo '<div id="language_current" style="' . $style . '">' . $languageCurrent . '</div>';
			foreach ($lang as $index => $arrayLanguages)
			{
				foreach ($arrayLanguages as $language => $message)
				{
					echo '<div id="' . strtolower($index) . '_' . strtolower($language) . '" style="' . $style . '">' . $lang[$index][$language] . '</div>';
				}
			}
		?>
	<!--
		Super Queue
		* Chinese translation: 董双丽
		* Graphics: 乔安
		* Code: Joan Alba Maldonado
	-->
	</body>
</html>