<?php

namespace Luma\Framework\Debug;

use Luma\Framework\Luma;
use Tracy\IBarPanel;

class AuthenticatedPanel implements IBarPanel
{
    /**
     * @return string
     */
    function getTab(): string
    {
        $svg = file_get_contents(dirname(__DIR__, 2) . '/assets/authenticated.svg');
        $user = Luma::getLoggedInUser();
        $username = $user?->getUsername();

        return sprintf('<span title="Authenticated">%s %s</span>', $svg, strtolower($username) ?? 'Not Authenticated');
    }

    /**
     * @return string
     */
    function getPanel(): string
    {
        return '';
    }
}