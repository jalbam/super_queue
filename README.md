Super Queue 
============ 
by Joan Alba Maldonado (joanalbamaldonadoNO_SPAM_PLEASE AT gmail DOT com, without NO_SPAM_PLEASE)

Queue and lotery game totally written in PHP and DHTML.

Version: final version 
- Date: 7th November 2014 (approximately).


![ScreenShots](screenshots.jpg)


## Description

I made this game for a Chinese software company whose final client was a chain of jewelry stores that wanted a Weixin (aka WeChat) game to promote themselves by giving the players some prizes and coupons just during a week (to celebrate the Chinese Single's day, the 11th of November). Weixin (WeChat) is the most used instant messaging client for mobile devices in China, made by Tencent (the same company that also owns QQ).

The graphics are made by 乔安 (Qiao An) and Chinese translation is done by 董双丽 (Dong Shuangli). The development took almost a month.

The game consists of two levels. In the first level, you are waiting in a queue and your mission is to jump the queue until you can enter the door. Once you enter through the door, the second level consists in waiting for a partner of the opposite gender (it can be either a real player or a fake one if the game is configured to use fake players or the admin force it) and, when you get a partner, then each of you have to spin a roulette wheel with zodiac symbols and both will win a prize if you both get the same zodiac symbol. If you get a different symbol, you get a discount coupon instead.

It is a viral game because players have to share the game to others in order to jump the queue they are waiting in. Every time someone registers through your invitation, you will jump two places. But others will also be able to jump and move in front of you so it becomes a kind of fight.

Players do not need to have the game open all the time. So they can close the game and open it again later whenever they want to. If any progress has been made or any thing new has happened, the game will show it.

There is a maximum number of people per queue, so the game creates a "new world" with a new queue every time a person joins the game for the first time if all other queues are full. The game also fills the queue with fake people in order to increase level difficulty and to avoid looking empty. All of that is configurable through variables.

The way to log in, prizes, maximum people per queue, chances to win, and many other things can be configured through the configuration file.

The game already includes Chinese and English languages. There is also a localization file that allows to translate the game into many more languages easily. The game will try to detect automatically the user's language.

The company wanted to run the game just for a week. During this week, the game was a complete success and had more than 50,000 players. All prizes were given out.

The game was running on Weixin (WeChat) and uses some of its functions, but it can be configured without effort to be used in any other app or platform (as for example in QQ, Facebook or any web browser with JavaScript and CSS enabled).

There is a debug mode which can be accessed through a password. That debug mode shows useful information about the game in real time and it also provides an admin control panel (similar to a God mode panel) with many options to cheat, control other players, etc.

It also includes an overload test page to check server limits.

This game was made using technologies as HTML, CSS / CSS 3, JavaScript, JSON, XHR (AJAX), PHP, mySQL, RPC (Remote Procedure Call), OpenID, OAuth, Weixin API (WeChat API) / WeixinJSBridge, etc.

You can try the game by yourself just creating the database needed (a SQL file with the required tables is included) and editing the configuration file to use that database. Basically, you just need a web server that supports PHP and mySQL.


## License

Forbidden to use without keeping the authors' name and copyright clauses. For non-commercial purposes only (unless you contact me and pay for a license).