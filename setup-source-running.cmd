 @echo off
 set "url=https://github.com/pmmp/PHP-Binaries/releases/download/php-8.2-latest/PHP-Windows-x64-PM5.zip"
 set "zipfile=PHP-Windows-x64-PM5.zip"
 set "binfolder=bin"
 set "phpfolder=%binfolder%\php"
 set "composerUrl=https://getcomposer.org/download/latest-stable/composer.phar"
 set "composerfile=composer.phar"
 set "vcredist=vc_redist.x64.exe"

 if exist %binfolder% (
     echo Removing existing bin folder...
     rmdir /S /Q "%binfolder%"
     echo Bin folder removed.
 )

 echo Downloading PHP binaries...
 curl -L -o "%zipfile%" "%url%"

 if exist "%zipfile%" (
     echo Download complete. Extracting bin folder...
     powershell -command "Expand-Archive -Path '%cd%\%zipfile%' -DestinationPath '%cd%'"

     if exist "%binfolder%" (
         echo Extraction successful.

         echo Downloading Composer...
         curl -L -o "%binfolder%\%composerfile%" "%composerUrl%"

         if exist "%binfolder%\%composerfile%" (
             echo Composer downloaded successfully to %binfolder%.

             echo Running composer install...
             "%phpfolder%\php.exe" "%binfolder%\composer.phar" install

             if !errorlevel! equ 0 (
                echo Composer install completed successfully.
             ) else (
                echo Error : Composer install failed.
             )
         ) else (
             echo Error: Failed to download Composer.
         )

         if exist "%vcredist%" (
             echo Removing %vcredist%...
             del "%vcredist%"
             echo %vcredist% removed.
         ) else (
             echo %vcredist% not found, skipping removal.
         )

         if exist "%zipfile%" (
             echo Removing %zipfile%...
             del "%zipfile%"
             echo %zipfile% removed.
         ) else (
             echo %zipfile% not found, skipping removal.
         )

         git fetch

         git checkout stable
     ) else (
         echo Error: Bin folder not found.
     )
 ) else (
     echo Error: Failed to download the file.
 )

 pause
