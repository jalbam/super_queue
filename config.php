<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	/*
		NOTES:

		1)
			To use visitor's Wechat ID or others as user keyword (should be unique) to register or login,
			we need to access this game passing the variable "user_keyword" through GET in the URL.
			Example:
				http://www.example.com/this_game/index.php?keyword=KEYWORD_OF_THE_USER
					i.e.: http://www.example.com/this_game/index.php?keyword=joanjalbam
		
		2)
			To invite others users, we need to pass the variable "user_host" filled with our user ID
			through GET method (it has to be numeric and greater than zero!). Example:
				http://www.example.com/this_game/index.php?user_host=MY_USER_ID
					i.e.: http://www.example.com/this_game/index.php?user_host=123456
		
		3)
			We can mix the first two methods. Example:
				http://www.example.com/this_game/index.php?keyword=weixin_id_of_guest&user_host=123456

	*/


	
	//Includes required files:
	if (file_exists("functions.php")) { require_once "functions.php"; }
	else { require_once "../functions.php"; }



	//General constants:
	define("DEBUG_MODE_DEFAULT", FALSE); //Defines debug mode default value (mainly for JavaScript).
	define("DEBUG_MODE_PASSWORD", "mydebugpassword"); //Defines debug mode default value (mainly for JavaScript).
	define("DEBUG_MODE", DEBUG_MODE_DEFAULT || (getVar("debug_mode", true) === "yes" && getVar("debug_password", true) === DEBUG_MODE_PASSWORD)); //Defines debug mode (mainly for JavaScript).
	if (DEBUG_MODE) { error_reporting(E_ALL); ini_set('display_errors','On'); }

	define("SITE_URL", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

	define("USE_COOKIES_DEFAULT", TRUE); //Defines whether use cookies to remember the user by default.
	define("USE_COOKIES", USE_COOKIES_DEFAULT || (getGet("use_cookies") === "" || getGet("use_cookies") !== "no")); //Defines whether use cookies to remember the user.

	define("USE_SIMPLE_LOGIN", TRUE); //Just asks for password to login (using password as username too).
	define("USE_DEFINED_NAMES_ON_LOGIN", FALSE); //Defines whether use defined user names on login or not.
	define("PASSWORD_IS_PHONE", TRUE); //Defines whether the password only allows phone numbers or not.

	define("USER_NAME_CHARACTERS_MINIMUM", 2); //Defines minimum characters for user name.
	define("USER_NAME_CHARACTERS_MAXIMUM", 150); //Defines maximum characters for user name.
	define("PASSWORD_CHARACTERS_MINIMUM", 11); //Defines minimum characters for password.
	define("PASSWORD_CHARACTERS_MAXIMUM", 11); //Defines maximum characters for password.

	define("AVOID_QUEUE", TRUE); //Defines whether the players enter the lottery room directly (avoiding the queue) or with a given number of guests or not.
	define("AVOID_QUEUE_GUESTS_NEEDED", 2); //Guests needed to enter the lottery room (in the case the option of avoid queue is enabled).

	define("MAXIMUM_PEOPLE_PER_QUEUE", 19); //Maximum people allowed per queue.
	define("MINIMUM_MALE_USERS", 5); //Minimum users per queue (will be filled with fake users).
	define("MINIMUM_FEMALE_USERS", 5); //Minimum users per queue (will be filled with fake users).

	define ("WINNERS_LIST_MINIMUM_PEOPLE", 20); //Minimum of winners shown in the list (fake winners will be shown if needed).

	define("FAKE_USERS_PREFIX_MALE", "fake_m_"); //Prefix for fake male users (user name and password).
	define("FAKE_USERS_PREFIX_FEMALE", "fake_f_"); //Prefix for fake female users (user name and password).

	define("PLACES_JUMP_ON_GET_GUEST", 2); //Number of places to advance when an invitation is accepted.



	//Database configuration:
	define("DB_NAME", "my_wechat_queue"); //Database name.
	define("DB_HOST", "localhost"); //Database host.
	define("DB_USER", "root"); //Database username.
	define("DB_PASSWORD", "123456"); //Database password.



	//Includes localization files:
	if (file_exists("config_localization.php")) { require_once "config_localization.php"; }
	else { require_once "../config_localization.php"; }



	//Wechat configuration:
	define("WECHAT_INFO_DETECTOR_ENABLED", FALSE); //Defines whether get Wechat user information via JavaScript.
	//define("WECHAT_APPID", "wx531d792b055b194c"); //Wechat AppID.
	define("WECHAT_APPID", "");
	define("WECHAT_APPSECRET", "1f45a02a951eff0fc894f3a97cb9c775"); //Wechat AppSecret.
	define("WECHAT_CODE", trim(getGet("code"))); //Wechat code (passed by URL after user authorization).

	define("WECHAT_SHOW_SHARE_LINK", FALSE); //Address of the thumb image when the site is shared in Wechat.	
	define("WECHAT_SHARE_IMAGE_URL", "img/wechat_share_icon.gif"); //Address of the thumb image when the site is shared in Wechat.
	define("WECHAT_SHARE_TITLE", localize("GAME_TITLE")); //Title of the site when it is shared.
	define("WECHAT_SHARE_DESCRIPTION", localize("WECHAT_SHARE_DESCRIPTION")); //Description of the site when it is shared


	//Password to set a prize as given when the user goes to the shop and the seller introduces it:
	define("PRIZE_GIVEN_PASSWORD", "hola123");
	

	//Shops:
	$shops = Array(
					"shop_1" => Array(
										"city" => "杭州",
										"name" => "杭州延安店",
										"address" => "延安路571号",
										"telephone" => "0571-85107999",
										"accepts_coupons" => TRUE
									),
					"shop_2" => Array(
										"city" => "杭州",
										"name" => "杭州凤起店",
										"address" => "延安路451号",
										"telephone" => "0571-85060399",
										"accepts_coupons" => TRUE
									),
					"shop_3" => Array(
										"city" => "杭州",
										"name" => "杭州解放店",
										"address" => "延安路234号",
										"telephone" => "0571-87036199",
										"accepts_coupons" => TRUE
									),
					"shop_4" => Array(
										"city" => "杭州",
										"name" => "杭州大厦专柜",
										"address" => "杭州大厦D座2F",
										"telephone" => "0571-85108518",
										"accepts_coupons" => FALSE
									),
					"shop_5" => Array(
										"city" => "杭州",
										"name" => "杭州武林银泰专柜",
										"address" => "延安路530号银泰百货2F",
										"telephone" => "0571-85836276",
										"accepts_coupons" => FALSE
									),
					"shop_6" => Array(
										"city" => "杭州",
										"name" => "杭州庆春银泰专柜",
										"address" => "景昙路18号银泰百货1F",
										"telephone" => "0571-86533339",
										"accepts_coupons" => FALSE
									),
					"shop_7" => Array(
										"city" => "杭州",
										"name" => "杭州西湖银泰专柜",
										"address" => "延安南路98号银泰百货1F",
										"telephone" => "0571-87002825",
										"accepts_coupons" => FALSE
									),
					"shop_8" => Array(
										"city" => "杭州",
										"name" => "杭州城西银泰专柜",
										"address" => "丰谭路380号城西银泰1F",
										"telephone" => "0571-87616065",
										"accepts_coupons" => FALSE
									),
					"shop_9" => Array(
										"city" => "杭州",
										"name" => "杭州解百专柜",
										"address" => "解放路251号解百新世纪商厦1F",
										"telephone" => "0571-87033873",
										"accepts_coupons" => FALSE
									),
					"shop_10" => Array(
										"city" => "杭州",
										"name" => "杭州百大专柜",
										"address" => "延安路546号银泰武林总店C馆1F",
										"telephone" => "0571-81383370",
										"accepts_coupons" => FALSE
									),
					"shop_11" => Array(
										"city" => "杭州",
										"name" => "萧山店",
										"address" => "萧山区体育路189号",
										"telephone" => "0571-82637876",
										"accepts_coupons" => TRUE
									),
					"shop_12" => Array(
										"city" => "杭州",
										"name" => "萧山银隆专柜",
										"address" => "萧山市心中路298号银隆百货1F",
										"telephone" => "0571-82828106",
										"accepts_coupons" => TRUE
									),
					"shop_13" => Array(
										"city" => "杭州",
										"name" => "临平店",
										"address" => "余杭区临平北大街89号",
										"telephone" => "0571-89262899",
										"accepts_coupons" => TRUE
									),
					"shop_14" => Array(
										"city" => "杭州",
										"name" => "临平银泰专柜",
										"address" => "余杭区临平北大街132号银泰百货1F",
										"telephone" => "0571-89169991",
										"accepts_coupons" => FALSE
									),
					"shop_15" => Array(
										"city" => "杭州",
										"name" => "富阳银泰专柜",
										"address" => "富阳市春秋北路271号银泰百货1F",
										"telephone" => "0571-61713728",
										"accepts_coupons" => FALSE
									),
					"shop_16" => Array(
										"city" => "绍兴",
										"name" => "绍兴店",
										"address" => "绍兴市解放北路449号轩亭口",
										"telephone" => "0575-85087993",
										"accepts_coupons" => TRUE
									),
					"shop_17" => Array(
										"city" => "绍兴",
										"name" => "绍兴柯桥银泰专柜",
										"address" => "绍兴市柯桥笛扬路银泰百货1F",
										"telephone" => "0575-81112428",
										"accepts_coupons" => TRUE
									),
					"shop_18" => Array(
										"city" => "绍兴",
										"name" => "上虞店",
										"address" => "上虞市人民中路258号",
										"telephone" => "0575-82128928",
										"accepts_coupons" => TRUE
									),
					"shop_19" => Array(
										"city" => "绍兴",
										"name" => "上虞老大通专柜",
										"address" => "上虞市人民中路193号大通商城1F",
										"telephone" => "0575-80270095",
										"accepts_coupons" => FALSE
									),
					"shop_20" => Array(
										"city" => "绍兴",
										"name" => "上虞新大通专柜",
										"address" => "上虞市市民大道689号新大通商场1F",
										"telephone" => "0575-81228600",
										"accepts_coupons" => FALSE
									),
					"shop_21" => Array(
										"city" => "绍兴",
										"name" => "上虞万和城专柜",
										"address" => "上虞市市民大道688号上百万和城1F",
										"telephone" => "0575-82021099",
										"accepts_coupons" => FALSE
									),
					"shop_22" => Array(
										"city" => "绍兴",
										"name" => "诸暨店",
										"address" => "诸暨市暨阳路177号",
										"telephone" => "0575-87018879",
										"accepts_coupons" => TRUE
									),
					"shop_23" => Array(
										"city" => "绍兴",
										"name" => "诸暨一百专柜",
										"address" => "诸暨市暨阳路7号一百商店1F",
										"telephone" => "0575-80705186",
										"accepts_coupons" => FALSE
									),
					"shop_24" => Array(
										"city" => "宁波",
										"name" => "宁波天一店",
										"address" => "天一广场碶闸街155号",
										"telephone" => "0574-87367099",
										"accepts_coupons" => TRUE
									),
					"shop_25" => Array(
										"city" => "宁波",
										"name" => "宁波天一国购店",
										"address" => "天一广场碶闸街182号",
										"telephone" => "0574-87376235",
										"accepts_coupons" => TRUE
									),
					"shop_26" => Array(
										"city" => "宁波",
										"name" => "宁波水晶街店",
										"address" => "天一广场水晶街35－37号",
										"telephone" => "0574-87361362",
										"accepts_coupons" => TRUE
									),
					"shop_27" => Array(
										"city" => "宁波",
										"name" => "宁波江东银泰专柜",
										"address" => "中山东路1111号银泰百货1F",
										"telephone" => "0574-87816159",
										"accepts_coupons" => FALSE
									),
					"shop_28" => Array(
										"city" => "宁波",
										"name" => "宁波万达银泰专柜",
										"address" => "四明中路999号银泰百货1F",
										"telephone" => "0574-83057632",
										"accepts_coupons" => FALSE
									),
					"shop_29" => Array(
										"city" => "宁波",
										"name" => "宁波东门银泰专柜",
										"address" => "中山东路238号银泰百货1F",
										"telephone" => "0574-87092605",
										"accepts_coupons" => FALSE
									),
					"shop_30" => Array(
										"city" => "宁波",
										"name" => "慈溪店",
										"address" => "慈溪环城南路57号",
										"telephone" => "0574-63898999",
										"accepts_coupons" => TRUE
									),
					"shop_31" => Array(
										"city" => "宁波",
										"name" => "慈溪银泰专柜",
										"address" => "慈溪青少年宫南路99号",
										"telephone" => "0574-63907116",
										"accepts_coupons" => FALSE
									),
					"shop_32" => Array(
										"city" => "宁波",
										"name" => "余姚店",
										"address" => "余姚新建路6－8号",
										"telephone" => "0574-62627897",
										"accepts_coupons" => TRUE
									),
					"shop_33" => Array(
										"city" => "宁波",
										"name" => "宁海太平洋专柜",
										"address" => "宁海县人民路15号",
										"telephone" => "0574-83558667",
										"accepts_coupons" => FALSE
									),
					"shop_34" => Array(
										"city" => "宁波",
										"name" => "奉化银泰专柜",
										"address" => "奉化市南山路150号银泰百货1F",
										"telephone" => "0574-88683126",
										"accepts_coupons" => FALSE
									),
					"shop_35" => Array(
										"city" => "湖州",
										"name" => "湖州店",
										"address" => "红旗路58号新天地一楼",
										"telephone" => "0572-2198299",
										"accepts_coupons" => FALSE
									),
					"shop_36" => Array(
										"city" => "湖州",
										"name" => "湖州爱山银泰专柜",
										"address" => "吴兴区南街558-590号1F",
										"telephone" => "0572-2778168",
										"accepts_coupons" => FALSE
									),
					"shop_37" => Array(
										"city" => "湖州",
										"name" => "长兴八佰伴专柜",
										"address" => "长兴县解放西路2号八佰伴1F",
										"telephone" => "0572-6860246",
										"accepts_coupons" => FALSE
									),
					"shop_38" => Array(
										"city" => "嘉兴",
										"name" => "嘉兴店",
										"address" => "嘉兴禾兴南路670号",
										"telephone" => "0573-82096829",
										"accepts_coupons" => TRUE
									),
					"shop_39" => Array(
										"city" => "嘉兴",
										"name" => "嘉善店",
										"address" => "嘉善县解放西路45号",
										"telephone" => "0573-84035589",
										"accepts_coupons" => TRUE
									),
					"shop_40" => Array(
										"city" => "嘉兴",
										"name" => "桐乡店",
										"address" => "桐乡时代广场东兴街29号",
										"telephone" => "0573-88098729",
										"accepts_coupons" => TRUE
									),
					"shop_41" => Array(
										"city" => "嘉兴",
										"name" => "桐乡东兴专柜",
										"address" => "桐乡庆丰中路18号东兴商厦",
										"telephone" => "0573-88180831",
										"accepts_coupons" => FALSE
									),
					"shop_42" => Array(
										"city" => "嘉兴",
										"name" => "海宁店",
										"address" => "海宁市工人路157号",
										"telephone" => "0573-87023669",
										"accepts_coupons" => TRUE
									),
					"shop_43" => Array(
										"city" => "嘉兴",
										"name" => "海宁银泰城专柜",
										"address" => "海宁市海州街道海昌南路363号1F",
										"telephone" => "0573-80778525",
										"accepts_coupons" => FALSE
									),
					"shop_44" => Array(
										"city" => "金华",
										"name" => "金华银泰城专柜",
										"address" => "金华市解放东路168号银泰城A馆1F",
										"telephone" => "0579-82326585",
										"accepts_coupons" => TRUE
									),
					"shop_45" => Array(
										"city" => "金华",
										"name" => "义乌店",
										"address" => "义乌城中中路52号",
										"telephone" => "0579-85521859",
										"accepts_coupons" => TRUE
									),
					"shop_46" => Array(
										"city" => "金华",
										"name" => "义乌工人路店",
										"address" => "义乌工人西路50号",
										"telephone" => "0579-85323737",
										"accepts_coupons" => TRUE
									),
					"shop_47" => Array(
										"city" => "金华",
										"name" => "义乌伊美银泰专柜",
										"address" => "义乌市工人西路15号伊美银泰2F",
										"telephone" => "0579-85935653",
										"accepts_coupons" => TRUE
									),
					"shop_48" => Array(
										"city" => "温州",
										"name" => "温州世贸银泰专柜",
										"address" => "解放南路荷花路口地下1F",
										"telephone" => "0577-88008128",
										"accepts_coupons" => FALSE
									),
					"shop_49" => Array(
										"city" => "上海",
										"name" => "上海南东专柜",
										"address" => "南京东路800号东方商厦1F",
										"telephone" => "021-33666297",
										"accepts_coupons" => FALSE
									),
					"shop_50" => Array(
										"city" => "上海",
										"name" => "上海中环专柜",
										"address" => "真光路1288号百联中环购物广场1F",
										"telephone" => "021-61392373",
										"accepts_coupons" => FALSE
									),
					"shop_51" => Array(
										"city" => "济南",
										"name" => "济南玉函专柜",
										"address" => "经十路19288号银座玉函店1F",
										"telephone" => "0531-67989654",
										"accepts_coupons" => FALSE
									)
				);


	//Prizes configuration (with maximum number of them that can be given):
	define("PRIZE_CHANCES_TO_WIN", 50); //Chances to get a prize if there is one available (0 = never, 100 = always).

	//Note: Don't set any as 0 (zero)!
	$prizeTypes = Array(
							"prize_1" => Array( //White Gold Ring:
											"image_male" => "img/prizes/prize_1.jpg",
											"image_female" => "img/prizes/prize_1.jpg",
											"maximum" => 6,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											),
							"prize_2" => Array( //Silver Stars Pendant:
											"image_male" => "img/prizes/prize_2.jpg",
											"image_female" => "img/prizes/prize_2.jpg",
											"maximum" => 600,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											),
							"prize_3" => Array( //Whisper Sweet Words Earrings:
											"image_male" => "img/prizes/prize_3.jpg",
											"image_female" => "img/prizes/prize_3.jpg",
											"maximum" => 300,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											),
							"prize_4" => Array( //Happiness Campanula Pendant:
											"image_male" => "img/prizes/prize_4.jpg",
											"image_female" => "img/prizes/prize_4.jpg",
											"maximum" => 100,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											),
							"prize_5" => Array( //Silver Angel Pendant:
											"image_male" => "img/prizes/prize_5.jpg",
											"image_female" => "img/prizes/prize_5.jpg",
											"maximum" => 50,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											),
							"prize_6" => Array( //Half-price Card:
											"image_male" => "img/prizes/prize_6.jpg",
											"image_female" => "img/prizes/prize_6.jpg",
											"maximum" => 20000,
											"codes" => Array(),
											"code_position_left" => "",
											"code_position_top" => ""
											)
						);

	//Defines the prizes for the winners:
	$prizeTypesForWinners = Array("prize_1", "prize_2", "prize_3", "prize_4", "prize_5");
	
	//Defines the prizes for the losers:
	$prizeTypesForLosers = Array("prize_6");

	//Defines the codes for the prizes:
	//* Prize 6 (100 rmb cash card), codes from YX20141123404-YX20141128403:
	$codePrefix = "YX201411";
	$codeStart = 28404;
	$codeEnd = $codeStart + $prizeTypes["prize_6"]["maximum"];
	for ($x = $codeStart; $x < $codeEnd; $x++)
	{
		$prizeTypes["prize_6"]["codes"][] = $codePrefix . $x;
	}