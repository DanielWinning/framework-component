<?php

namespace Luma\Framework\Install;

class Installer
{
    /**
     * @return void
     */
    public static function install(): void
    {
        self::updateGitignore();
        self::updateComposerJson();
        self::updatePackageJson();
        self::copyEnv();
        self::installAssets();
    }

    /**
     * @return void
     */
    private static function updateGitignore(): void
    {
        $gitignorePath = sprintf('%s/.gitignore', self::projectDirectory());
        $gitignoreContent = file_get_contents($gitignorePath);
        $gitignoreContent = str_replace('/public/assets/*', '', $gitignoreContent);

        file_put_contents($gitignorePath, $gitignoreContent);
    }

    /**
     * @return void
     */
    private static function updateComposerJson(): void
    {
        $composerJsonPath = self::getJsonFilepath('composer');
        $composerText = file_get_contents($composerJsonPath);
        $composerJson = json_decode($composerText);

        $composerText = str_replace($composerJson->name, 'vendor/luma-project', $composerText);
        $composerText = str_replace($composerJson->authors[0]->name, 'Your Name', $composerText);
        $composerText = str_replace($composerJson->authors[0]->email, 'yourname@email.com', $composerText);

        file_put_contents($composerJsonPath, $composerText);
    }

    /**
     * @return void
     */
    private static function updatePackageJson(): void
    {
        $packageJsonPath = self::getJsonFilepath('package');
        $packageText = file_get_contents($packageJsonPath);
        $packageJson = json_decode($packageText);

        $packageText = str_replace($packageJson->repository->url, 'https://github.com/YourName/Repo', $packageText);

        file_put_contents($packageJsonPath, $packageText);
    }

    /**
     * @return void
     */
    private static function installAssets(): void
    {
        exec(sprintf('cd %s && npm install && npm run build', self::projectDirectory()));
    }

    /**
     * @return void
     */
    private static function copyEnv(): void
    {
        $configDirectory = sprintf('%s/config', self::projectDirectory());
        $source = sprintf('%s/.env.example', $configDirectory);
        $target = sprintf('%s/.env', $configDirectory);

        if (!file_exists($target) && file_exists($source)) {
            copy($source, $target);
        }
    }

    /**
     * @return string
     */
    private static function projectDirectory(): string
    {
        return dirname(__DIR__, 5);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private static function getJsonFilepath(string $filename): string
    {
        return sprintf('%s/%s.json', self::projectDirectory(), $filename);
    }
}