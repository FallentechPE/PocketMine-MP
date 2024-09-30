#!/bin/bash

url="https://github.com/pmmp/PHP-Binaries/releases/download/php-8.2-latest/PHP-Windows-x64-PM5.zip"
zipfile="PHP-Windows-x64-PM5.zip"
binfolder="bin"
phpfolder="$binfolder/php"
composerUrl="https://getcomposer.org/download/latest-stable/composer.phar"
composerfile="composer.phar"
vcredist="vc_redist.x64.exe"

if [ -d "$binfolder" ]; then
    echo "Removing existing bin folder..."
    rm -rf "$binfolder"
    echo "Bin folder removed."
fi

echo "Downloading PHP binaries..."
curl -L -o "$zipfile" "$url"

if [ -f "$zipfile" ]; then
    echo "Download complete. Extracting bin folder..."
    unzip -o "$zipfile" -d .

    if [ -d "$binfolder" ]; then
        echo "Extraction successful."

        echo "Downloading Composer..."
        curl -L -o "$binfolder/$composerfile" "$composerUrl"

        if [ -f "$binfolder/$composerfile" ]; then
            echo "Composer downloaded successfully to $binfolder."

            echo "Running composer install..."
            "$phpfolder/php" "$binfolder/$composerfile" install

            if [ $? -eq 0 ]; then
                echo "Composer install completed successfully."
            else
                echo "Error: Composer install failed."
            fi
        else
            echo "Error: Failed to download Composer."
        fi

        if [ -f "$vcredist" ]; then
            echo "Removing $vcredist..."
            rm "$vcredist"
            echo "$vcredist removed."
        else
            echo "$vcredist not found, skipping removal."
        fi

        if [ -f "$zipfile" ]; then
            echo "Removing $zipfile..."
            rm "$zipfile"
            echo "$zipfile removed."
        else
            echo "$zipfile not found, skipping removal."
        fi

        echo "Running git fetch..."
        git fetch
        echo "Checking out stable branch..."
        git checkout stable
    else
        echo "Error: Bin folder not found."
    fi
else
    echo "Error: Failed to download the file."
fi
