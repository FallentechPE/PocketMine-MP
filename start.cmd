@echo off
TITLE PocketMine-MP server software for Minecraft: Bedrock Edition
cd /d %~dp0

set PHP_BINARY=

where /q php.exe
if %ERRORLEVEL%==0 (
	set PHP_BINARY=php
)

if exist bin\php\php.exe (
	rem always use the local PHP binary if it exists
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
)

if "%PHP_BINARY%"=="" (
	echo Couldn't find a PHP binary in system PATH or "%~dp0bin\php"
	echo Please refer to the installation instructions at https://doc.pmmp.io/en/rtfd/installation.html
	pause
	exit 1
)

rem Check if PocketMine-MP.phar exists, otherwise use src/pocketmine.php
if exist PocketMine-MP.phar (
	set POCKETMINE_FILE=PocketMine-MP.phar
	echo Using PocketMine-MP.phar
) else if exist src\pocketmine.php (
	set POCKETMINE_FILE=src\PocketMine.php
	echo PocketMine-MP.phar not found, using src/pocketmine.php
) else (
	echo Neither PocketMine-MP.phar nor src\pocketmine.php found
	echo Please download PocketMine-MP.phar or ensure src/pocketmine.php is available
	pause
	exit 1
)

if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="Consolas" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
) else (
	REM pause on exitcode != 0 so the user can see what went wrong
	%PHP_BINARY% %POCKETMINE_FILE% %* || pause
)
