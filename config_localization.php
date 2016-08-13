<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	//Default language to use:
	define("LANGUAGE_DEFAULT", "zh");

	//Languages available (by priority):
	$languagesAvailable = Array("zh", "en");

	//Current language used:
	$languageCurrent = getPreferredLanguage($languagesAvailable, LANGUAGE_DEFAULT); //This could be changed through the game.
	$languageSent = strtolower(getVar("language", true));
	if (in_array($languageSent, $languagesAvailable)) { $languageCurrent = $languageSent; }


	//Default encoding:
	$encodingDefault = "UTF-8";

	
	//Localized strings:
	$lang = Array();

	$lang["GAME_TITLE"] = Array(
									"en" => "Super Queue",
									"zh" => "双11，曼卡龙2万份大礼等你排队抢！"
								);

	$lang["WECHAT_SHARE_DESCRIPTION"] = Array(
												"en" => "This game is really wonderful! Get many prizes playing it. Start now!!!",
												"zh" => "立马点开帮我插队，我要赢对戒"
											);

	$lang["LANDSCAPE_MODE_IS_BETTER"] = Array(
												"en" => "Use your phone in landscape mode for a better experience!",
												"zh" => "手机横过来体验更赞！"
											);

	$lang["LOADING"] = Array(
									"en" => "Loading...",
									"zh" => "加载中..."
								);

	$lang["AJAX_NOT_SUPPORTED"] = Array(
											"en" => "AJAX is not supported!",
											"zh" => "AJAX is not supported!"
										);

	$lang["CHOOSE_A_NAME"] = Array(
											"en" => "Choose a name",
											"zh" => "选择星座"
										);


	$lang["DEFINED_USER_NAMES_1"] = Array(
												'en' => 'Aries',
												'zh' => '白羊座'
											);


	$lang["DEFINED_USER_NAMES_2"] = Array(
												'en' => 'Taurus',
												'zh' => '金牛座'
											);

	$lang["DEFINED_USER_NAMES_3"] = Array(
												"en" => 'Gemini',
												"zh" => '双子座'
											);


	$lang["DEFINED_USER_NAMES_4"] = Array(
												'en' => 'Cancer',
												'zh' => '巨蟹座'
											);


	$lang["DEFINED_USER_NAMES_5"] = Array(
												'en' => 'Leo',
												'zh' => '狮子座'
											);


	$lang["DEFINED_USER_NAMES_6"] = Array(
												'en' => 'Virgo',
												'zh' => '处女座'
											);


	$lang["DEFINED_USER_NAMES_7"] = Array(
												"en" => 'Libra',
												"zh" => '天秤座'
											);


	$lang["DEFINED_USER_NAMES_8"] = Array(
												'en' => 'Scorpio',
												'zh' => '天蝎座'
											);


	$lang["DEFINED_USER_NAMES_9"] = Array(
												'en' => 'Sagittarius',
												'zh' => '射手座'
											);


	$lang["DEFINED_USER_NAMES_10"] = Array(
												'en' => 'Capricorn',
												'zh' => '摩羯座'
											);


	$lang["DEFINED_USER_NAMES_11"] = Array(
												'en' => 'Aquarius',
												'zh' => '水瓶座'
											);


	$lang["DEFINED_USER_NAMES_12"] = Array(
												'en' => 'Pisces',
												'zh' => '双鱼座'
											);

	$lang["FORM_USER_NAME"] = Array(
										"en" => "User name",
										"zh" => "名字"
									);

	$lang["FORM_PASSWORD"] = Array(
										"en" => "Phone",
										"zh" => "手机号"
									);

	$lang["FORM_GENDER"] = Array(
										"en" => "Gender",
										"zh" => "性别"
									);

	$lang["FORM_MALE"] = Array(
										"en" => "Male",
										"zh" => "男"
									);

	$lang["FORM_FEMALE"] = Array(
								"en" => "Female",
								"zh" => "女"
							);

	$lang["FORM_SEND"] = Array(
								"en" => "Ok",
								"zh" => "确认"
							);

	$lang["YOUR_HOST_USER"] = Array(
									"en" => "Your host user",
									"zh" => "邀请者"
								);

	$lang["WECHAT_GETTING_DATA"] = Array(
												"en" => "Trying to retrieve data from Wechat...",
												"zh" => "微信连接中..."
											);

	$lang["WECHAT_GET_DATA_FAILED"] = Array(
												"en" => "Unable to get Wechat user data",
												"zh" => "Unable to get Wechat user data"
											);

	$lang["WECHAT_ERROR_PARSING_DATA"] = Array(
												"en" => "Failed parsing Wechat JSON data",
												"zh" => "Failed parsing Wechat JSON data"
											);

	$lang["WECHAT_DATA_IS_NOT_VALID"] = Array(
										"en" => "Wechat JSON data is not valid",
										"zh" => "Wechat JSON data is not valid"
									);

	$lang["WECHAT_GET_DATA_EMPTY"] = Array(
												"en" => "Wechat data is empty",
												"zh" => "Wechat data is empty"
											);

	$lang["FORM_ERROR_DATA_NOT_FOUND"] = Array(
												"en" => "Unable to get form data",
												"zh" => "获取不到数据"
											);

	$lang["FORM_ERROR_FIELD_EMPTY"] = Array(
											"en" => "is empty",
											"zh" => "不能为空"
										);

	$lang["FORM_ERROR_FIELD_NOT_CHOSEN"] = Array(
											"en" => "has not been chosen",
											"zh" => "未选择"
										);


	$lang["FORM_ERROR_FIELD_TOO_SHORT"] = Array(
												"en" => "is too short",
												"zh" => "过短"
											);


	$lang["FORM_ERROR_FIELD_TOO_LONG"] = Array(
												"en" => "is too long",
												"zh" => "过长"
											);


	$lang["FORM_ERROR_FIELD_ILLEGAL_CHARACTERS"] = Array(
															"en" => "is not valid",
															"zh" => "输入有误"
														);


	$lang["FORM_ERROR_GENDER_UNKNOWN"] = Array(
											"en" => "Gender is unknown",
											"zh" => "数据丢失，请重试"
										);

	$lang["WAIT_PLEASE"] = Array(
									"en" => "Wait, please...",
									"zh" => "请耐心，马上就好..."
								);

	$lang["CONTROLLER_OPERATION_UNKNOWN"] = Array(
														"en" => "Operation is unknown",
														"zh" => "Operation is unknown"
													);


	$lang["CONTROLLER_OPERATION_KNOWN_NOT_ACCEPTED"] = Array(
																"en" => "Operation known but not accepted",
																"zh" => "Operation known but not accepted"
															);

	
	$lang["LOGIN_REGISTER_USER_FAILED"] = Array(
												"en" => "Login or register user failed",
												"zh" => "登陆/注册失败"
											);


	$lang["START_GAME_FAILED"] = Array(
										"en" => "Failed starting game",
										"zh" => "打开游戏失败"
									);


	$lang["ERROR_JUMPING_TO_LOTTERY_ROOM"] = Array(
													"en" => "Error jumping to the lottery room",
													"zh" => "Error jumping to the lottery room"
												);


	$lang["GET_DATA_FAILED"] = Array(
										"en" => "Failed retrieving data",
										"zh" => "Failed retrieving data"
									);


	$lang["ERROR_PARSING_DATA"] = Array(
										"en" => "Failed parsing JSON data",
										"zh" => "Failed parsing JSON data"
									);

	$lang["DATA_IS_NOT_VALID"] = Array(
										"en" => "JSON data is not valid",
										"zh" => "JSON data is not valid"
									);

	$lang["GET_DATA_EMPTY"] = Array(
										"en" => "Data is empty",
										"zh" => "Data is empty"
									);


	$lang["YOU_ARE_HERE"] = Array(
										"en" => "You are here!",
										"zh" => "你在这里！"
									);

	$lang["GAME_FINISHED"] = Array(
										"en" => "This game has finished. Thanks for playing!",
										"zh" => "游戏结束了！谢谢！"
									);

	$lang["WINNERS_LIST_TITLE"] = Array(
										"en" => "Some winners",
										"zh" => "恭喜获奖者"
									);

	$lang["WINNERS_LIST_LOADING"] = Array(
										"en" => "Loading winners list...",
										"zh" => "加载获奖名单中..."
									);

	$lang["WINNERS_LIST_EMPTY"] = Array(
										"en" => "No winners found",
										"zh" => "快来赢奖"
									);


	$lang["CLOSE_BUTTON_TITLE"] = Array(
										"en" => "Close",
										"zh" => "关闭"
									);

	$lang["QUEUE_INSTRUCTIONS"] = Array(
										"en" => "Invite other players to advance " . PLACES_JUMP_ON_GET_GUEST . " positions if they enter the game.<br />When you enter through the door you will be able to win a prize!",
										//"zh" => "游戏规则：只要能够邀请两个新朋友参与游戏，你就可以进入房间， 100%中奖哦！"
										//"zh" => "点击右上角，分享给好友，每邀请一个新朋友进入游戏，就可以往前移动" . PLACES_JUMP_ON_GET_GUEST . "个位置哦<br />进入房间后，惊喜大奖等你拿！"
										"zh" => "点击右上角，分享给好友，邀请好友参与游戏即可进入房间进行抽奖，惊喜大奖等你拿！"
									);

	$lang["SHARE_LINK"] = Array(
										"en" => "Share link",
										"zh" => "分享链接给朋友"
									);


	$lang["LOTTERY_ROOM_INSTRUCTIONS"] = Array(
												"en" => "Congratulations, you got enough guests to enter in the lottery room!<br />Get the same result as your partner to get a prize.<br />Good luck!",
												//"zh" => "恭喜你，进入房间了！<br />和有缘人转到相同图案就有机会拿大奖哦<br />祝你好运！"
												"zh" => "点击右上角，分享给好友，邀请越多朋友参加，得到大奖的可能性就越大哦，快来房间拿奖吧！"
											);

	$lang["LOTTERY_ROOM_WAITING_PARTNER"] = Array(
												"en" => "Waiting for your partner to join the room...",
												"zh" => "等一下你的有缘人吧！"
											);


	$lang["LOTTERY_ROOM_RESULT_WAITING"] = Array(
												"en" => "Waiting for a result...",
												"zh" => "等待转动..."
											);

	$lang["LOTTERY_ROOM_PLAY"] = Array(
												"en" => "Play!",
												"zh" => "Play!"
											);

	$lang["LOTTERY_ROOM_RESULT"] = Array(
												"en" => "Result",
												"zh" => "图案"
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_1"] = Array(
												'en' => '<img src="img/lottery/aries.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/aries.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_2"] = Array(
												'en' => '<img src="img/lottery/taurus.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/taurus.png" class="lottery_result_icon_image" />'
											);



	$lang["LOTTERY_ROOM_RESULT_ICON_3"] = Array(
												"en" => '<img src="img/lottery/gemini.png" class="lottery_result_icon_image" />',
												"zh" => '<img src="img/lottery/gemini.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_4"] = Array(
												'en' => '<img src="img/lottery/cancer.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/cancer.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_5"] = Array(
												'en' => '<img src="img/lottery/leo.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/leo.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_6"] = Array(
												'en' => '<img src="img/lottery/virgo.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/virgo.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_7"] = Array(
												"en" => '<img src="img/lottery/libra.png" class="lottery_result_icon_image" />',
												"zh" => '<img src="img/lottery/libra.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_8"] = Array(
												'en' => '<img src="img/lottery/scorpio.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/scorpio.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_9"] = Array(
												'en' => '<img src="img/lottery/sagittarius.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/sagittarius.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_10"] = Array(
												'en' => '<img src="img/lottery/capricorn.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/capricorn.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_11"] = Array(
												'en' => '<img src="img/lottery/aquarius.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/aquarius.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_ICON_12"] = Array(
												'en' => '<img src="img/lottery/pisces.png" class="lottery_result_icon_image" />',
												'zh' => '<img src="img/lottery/pisces.png" class="lottery_result_icon_image" />'
											);


	$lang["LOTTERY_ROOM_RESULT_1"] = Array(
												'en' => 'Aries',
												'zh' => '白羊座'
											);


	$lang["LOTTERY_ROOM_RESULT_2"] = Array(
												'en' => 'Taurus',
												'zh' => '金牛座'
											);


	$lang["LOTTERY_ROOM_RESULT_3"] = Array(
												"en" => 'Gemini',
												"zh" => '双子座'
											);


	$lang["LOTTERY_ROOM_RESULT_4"] = Array(
												'en' => 'Cancer',
												'zh' => '巨蟹座'
											);


	$lang["LOTTERY_ROOM_RESULT_5"] = Array(
												'en' => 'Leo',
												'zh' => '狮子座'
											);


	$lang["LOTTERY_ROOM_RESULT_6"] = Array(
												'en' => 'Virgo',
												'zh' => '处女座'
											);


	$lang["LOTTERY_ROOM_RESULT_7"] = Array(
												"en" => 'Libra',
												"zh" => '天秤座'
											);


	$lang["LOTTERY_ROOM_RESULT_8"] = Array(
												'en' => 'Scorpio',
												'zh' => '天蝎座'
											);


	$lang["LOTTERY_ROOM_RESULT_9"] = Array(
												'en' => 'Sagittarius',
												'zh' => '射手座'
											);


	$lang["LOTTERY_ROOM_RESULT_10"] = Array(
												'en' => 'Capricorn',
												'zh' => '摩羯座'
											);


	$lang["LOTTERY_ROOM_RESULT_11"] = Array(
												'en' => 'Aquarius',
												'zh' => '水瓶座'
											);


	$lang["LOTTERY_ROOM_RESULT_12"] = Array(
												'en' => 'Pisces',
												'zh' => '双鱼座'
											);


	$lang["LOSER_TEXT"] = Array(
									'en' => 'You and your partner didn\'t get the same result in the lottery. You lost.<br />Thanks for playing!',
									'zh' => '很遗憾，图案相同才有缘一起赢钻戒<br />谢谢参与！'
								);

	$lang["WINNER_TEXT"] = Array(
									'en' => 'You and your partner got the same result in the lottery! You won.<br />Congratulations!',
									'zh' => '' //你们抽中了相同的图案，太有缘了！<br />恭喜！
								);

	$lang["DEBUG_SERVER_CHEATING_REQUEST_ERROR"] = Array(
															'en' => 'Error requesting debug cheating to the server',
															'zh' => 'Error requesting debug cheating to the server'
														);


	$lang["LOADING_PRIZE_INFORMATION"] = Array(
												'en' => 'Loading prize information...',
												'zh' => '正在努力加载奖品信息...'
											);

	$lang["YOU_WON_THIS_PRIZE"] = Array(
												'en' => 'You got this prize',
												'zh' => '你赢得了该奖品'
											);

	$lang["WHERE_WILL_YOU_PICK_UP_THE_PRIZE"] = Array(
														'en' => 'Where will you pick up your prize?',
														'zh' => '选择领奖地址'
													);

	$lang["THESE_ARE_SHOPS_ACCEPT_COUPONS"] = Array(
																'en' => 'These are the shops that accept the coupon',
																'zh' => '消费地址'
															);

	$lang["CHOOSE_A_CITY"] = Array(
									'en' => 'Choose a city',
									'zh' => '选择城市'
								);

	$lang["CHOOSE_A_SHOP"] = Array(
									'en' => 'Select a shop',
									'zh' => ' 消费店铺'
								);

	$lang["SELECT_SHOP_BUTTON"] = Array(
									'en' => 'Ok',
									'zh' => '确认'
								);


	$lang["SELECTING_SHOP"] = Array(
									'en' => 'Selecting shop...',
									'zh' => '正在选择店铺...'
								);


	$lang["AJAX_ERROR_SELECTING_SHOP"] = Array(
									'en' => 'Error selecting shop',
									'zh' => 'Error selecting shop'
								);


	$lang["ERROR_SELECTING_SHOP"] = Array(
									'en' => 'Failed selecting the shop. Please, try again!',
									'zh' => '选择店铺失败，再试一次！'
								);


	$lang["SHOP_SELECTED_VISIT"] = Array( //For the sentence: Visit [SHOP_NAME] at [SHOP_ADDRESS] (SHOP_TELEPHONE) and show this screen to them!
											'en' => 'Visit',
											'zh' => '请至'
										);

	$lang["SHOP_SELECTED_AT"] = Array( //For the sentence: Visit [SHOP_NAME] at [SHOP_ADDRESS] (SHOP_TELEPHONE) and show this screen to them!
										'en' => 'at',
										'zh' => '位于'
									);

	$lang["SHOP_SELECTED_AND_SHOW_SCREEN"] = Array( //For the sentence: Visit [SHOP_NAME] at [SHOP_ADDRESS] (SHOP_TELEPHONE) and show this screen to them!
													'en' => 'and show this screen to them!',
													'zh' => '凭此界面领奖'
												);

	$lang["SELLER_CODE"] = Array(
										'en' => 'Seller code',
										'zh' => '工作人员密码'
								);


	$lang["MARK_AS_GIVEN"] = Array(
									'en' => 'Mark as given',
									'zh' => '确认'
								);

	$lang["APPLYING_CODE"] = Array(
									'en' => 'Applying code...',
									'zh' => '正在处理...'
								);


	$lang["AJAX_ERROR_APPLYING_CODE"] = Array(
												'en' => 'Code could not be applied',
												'zh' => 'Code could not be applied'
											);


	$lang["CODE_NOT_APPLIED"] = Array(
									'en' => 'Code could not be applied. Please, try again!',
									'zh' => '密码未识别，请重试'
								);


	$lang["PRIZE_GIVEN"] = Array(
									'en' => 'This prize has already been taken',
									'zh' => '奖品已领取'
								);



	//Prizes:
	$lang["PRIZE_1"] = Array(
								'en' => 'White Gold Ring',
								'zh' => '钻石对戒'
							);


	$lang["PRIZE_2"] = Array(
								'en' => 'Silver Stars Pendant',
								'zh' => '星星银挂坠'
							);


	$lang["PRIZE_3"] = Array(
								'en' => 'Whisper Sweet Words Earrings',
								'zh' => '甜言耳语耳钉'
							);


	$lang["PRIZE_4"] = Array(
								'en' => 'Happiness Campanula Pendant',
								'zh' => '幸福风铃挂坠'
							);


	$lang["PRIZE_5"] = Array(
								'en' => 'Silver Angel Pendant',
								'zh' => '天使银挂坠'
							);


	$lang["PRIZE_6"] = Array(
								'en' => 'Half-price Card',
								'zh' => '半价抵用券'
							);