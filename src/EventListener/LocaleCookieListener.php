<?php

namespace Surfnet\StepupBundle\EventListener;

use Psr\Log\LoggerInterface;
use Surfnet\StepupBundle\Service\LocaleProviderService;
use Surfnet\StepupBundle\Value\CookieSettings;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

final class LocaleCookieListener
{
    /**
     * @var CookieSettings
     */
    private $cookieSettings;

    /**
     * @var LocaleProviderService
     */
    private $localeProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        CookieSettings $cookieSettings,
        LocaleProviderService $localeProvider,
        LoggerInterface $logger
    ) {
        $this->cookieSettings = $cookieSettings;
        $this->localeProvider = $localeProvider;
        $this->logger = $logger;
    }

    /**
     * If there is a logged in user with a preferred language, set it as a cookie.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $locale = $this->localeProvider->determinePreferredLocale();

        // Unable to determine preferred locale.
        if (!$locale) {
            return;
        }

        $valueFromCookie = $this->cookieSettings->value($event->getRequest());
        if ($valueFromCookie === $locale) {
            $this->logger->info(sprintf(
                'Locale cookie already set to "%s", nothing to do here',
                $locale
            ));
            return;
        }

        $event->getResponse()->headers->setCookie($this->cookieSettings->toCookie($locale));

        $this->logger->info(sprintf("Set locale cookie to %s", $locale));
    }
}
