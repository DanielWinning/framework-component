<?php

namespace Luma\Framework\Install;

namespace Luma\Framework\Install;

class Installer
{
    public static function install(): void
    {
        $composerJsonPath = sprintf('%s/composer.json', dirname(__DIR__, 5));
        $composerText = file_get_contents($composerJsonPath);
        $composerJson = json_decode($composerText);

        $composerText = str_replace($composerJson->name, 'vendor/luma-project', $composerText);
        $composerText = str_replace($composerJson->authors[0]->name, 'Your Name', $composerText);
        $composerText = str_replace($composerJson->authors[0]->email, 'yourname@email.com', $composerText);

        file_put_contents($composerJsonPath, $composerText);
    }
}