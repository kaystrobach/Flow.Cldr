<?php

namespace KayStrobach\Cldr\Domain\Repository;

use KayStrobach\Cldr\Utility\CldrDataUtility;
use Neos\Flow\Annotations as Flow;

/**
 * Class LanguageRepository
 * @package KayStrobach\Custom\Domain\Repository
 */
class LanguageRepository
{

    /**
     * @Flow\Inject
     * @var CldrDataUtility
     */
    protected $cldrUtility;

    /**
     * @var \Neos\Flow\I18n\Service
     * @Flow\Inject
     */
    protected $i18nService;

    /**
     * @var \Neos\Flow\I18n\Detector
     * @Flow\Inject
     */
    protected $detector;

    /**
     * @return \Neos\Flow\I18n\Locale
     */
    public function getDefaultLocaleLanguage() {
        return $this->i18nService->getConfiguration()->getDefaultLocale();
    }

    /**
     * @return \Neos\Flow\I18n\Locale
     */
    public function getLocale() {
        return $this->i18nService->getConfiguration()->getCurrentLocale();
    }

    /**
     * Get a locale matching the identifier string
     * @param string $identifier
     * @return \Neos\Flow\I18n\Locale
     */
    public function getLocaleByIdentifier($identifier) {
        return $this->detector->detectLocaleFromLocaleTag($identifier);
    }

    public function findAll() {
        return $this->cldrUtility->getLanguages();
    }
}