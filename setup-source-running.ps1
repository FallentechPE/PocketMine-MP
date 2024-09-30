$url = "https://github.com/pmmp/PHP-Binaries/releases/download/php-8.2-latest/PHP-Windows-x64-PM5.zip"
$zipfile = "PHP-Windows-x64-PM5.zip"
$binfolder = "bin"
$phpfolder = "$binfolder\php"
$composerUrl = "https://getcomposer.org/download/latest-stable/composer.phar"
$composerfile = "composer.phar"
$vcredist = "vc_redist.x64.exe"

if (Test-Path $binfolder) {
    Write-Host "Removing existing bin folder..."
    Remove-Item -Recurse -Force $binfolder
    Write-Host "Bin folder removed."
}

Write-Host "Downloading PHP binaries..."
Invoke-WebRequest -Uri $url -OutFile $zipfile

if (Test-Path $zipfile) {
    Write-Host "Download complete. Extracting bin folder..."
    Expand-Archive -Path $zipfile -DestinationPath $PWD

    if (Test-Path $binfolder) {
        Write-Host "Extraction successful."

        Write-Host "Downloading Composer..."
        Invoke-WebRequest -Uri $composerUrl -OutFile "$binfolder\$composerfile"

        if (Test-Path "$binfolder\$composerfile") {
            Write-Host "Composer downloaded successfully to $binfolder."

            Write-Host "Running composer install..."
            & "$phpfolder\php.exe" "$binfolder\$composerfile" install

            if ($LASTEXITCODE -eq 0) {
                Write-Host "Composer install completed successfully."
            } else {
                Write-Host "Error: Composer install failed."
            }
        } else {
            Write-Host "Error: Failed to download Composer."
        }

        if (Test-Path $vcredist) {
            Write-Host "Removing $vcredist..."
            Remove-Item $vcredist
            Write-Host "$vcredist removed."
        } else {
            Write-Host "$vcredist not found, skipping removal."
        }

        if (Test-Path $zipfile) {
            Write-Host "Removing $zipfile..."
            Remove-Item $zipfile
            Write-Host "$zipfile removed."
        } else {
            Write-Host "$zipfile not found, skipping removal."
        }

        Write-Host "Running git fetch..."
        git fetch
        Write-Host "Checking out stable branch..."
        git checkout stable
    } else {
        Write-Host "Error: Bin folder not found."
    }
} else {
    Write-Host "Error: Failed to download the file."
}

pause
