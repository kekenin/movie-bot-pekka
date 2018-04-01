# pekka-movie-bot
PHP Telegram search bot for displaying IMDB score, Metascore and a movie poster for the searched movie.

INSTALLATION INSTRUCTIONS:

1. Create Telegram bot following instructions: https://core.telegram.org/bots
2. Deploy pekka-movie-bot content to your public webserver.
3. Insert your Telegram bot's auth token into bot.php line 8.
4. Insert the channelID into bot.php line 16.
5. Create free account into https://developers.themoviedb.org/3
6. Insert your movieDB auth token into GetPoster.php line 13.
7. Set webhook to your bot using: https://api.telegram.org/bot[YOUR_BOT_TOKEN_HERE]/setwebhook?url=[URL_TO_PEKKA-MOVIE-BOT_FOLDER_ON_YOUR_WEBSERVER]/bot.php]

Done!

Request movie scores and posters by typing !leffa [movie name] to the channel the bot is on.
