# GuessMyTracks

This is a Game where a Song is played and the players have to guess its name and release year.  
If a player guesses right, they get a point. The player that first reaches 10 points wins.
The selection of tracks is based off the players spotify libraries. 
An extra point can be gained, if the player guesses correctly, from which players library the current track is from.


## Orientation:
https://github.com/nielsdrost7/spotify-laravel

## How to Install Composer Packages

The Repo doesn't ship with the vendor folder, you'll have to install it with composer.

Install it from within a temporary Docker Container:

```bash
docker run --rm -v ${PWD}:/app composer install --ignore-platform-req=ext-pcntl
```

Now you can start the Dev Container with

```bash
./vendor/bin/sail up
```

## Add CLI XDEBUG

Add an Alias to debug artisan commands:

```bash
alias artisan-debug="php -dxdebug.mode=debug -dxdebug.client_host=host.docker.internal -dxdebug.client_port=9003 -dxdebug.start_with_request=yes artisan"
```

## Common Problems

### No CSS after Deployment

Solution: Restart PHP FPM

```sh
sudo /etc/init.d/php8.3-fpm restart
```
