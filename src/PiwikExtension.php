<?php

namespace Bolt\Extension\Postitief\Piwik;

use Bolt\Extension\SimpleExtension;

class PiwikExtension extends SimpleExtension
{
    /**
     * @var PiwikTracker
     */
    protected $piwikTracker;

    protected function registerTwigFunctions()
    {
        $piwikTracker = new PiwikTracker(
            $this->getConfig()['site_id'],
            $this->getConfig()['api_url']
        );

        if(isset($this->getConfig()['auth_token'])) {
            $piwikTracker->setTokenAuth($this->getConfig()['auth_token']);
        }

        $this->piwikTracker = $piwikTracker;

        return [
            'track' => 'twig_track',
        ];
    }

    public function twig_track($title = "")
    {
        $request = $this->getContainer()->offsetGet('request');

        if(strlen($uri = $request->getRequestUri()) > 0 && strlen($title) <= 0) {
            $title = $uri;
        }

        $this->piwikTracker->doTrackPageView($title);
    }
}