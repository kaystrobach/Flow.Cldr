<?php

namespace KayStrobach\Cldr\Domain\Repository;

use KayStrobach\Cldr\Utility\CldrDataUtility;
use TYPO3\Flow\Annotations as Flow;

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
     * @var \TYPO3\Flow\I18n\Service
     * @Flow\Inject
     */
    protected $i18nService;

    /**
     * @var \TYPO3\Flow\I18n\Detector
     * @Flow\Inject
     */
    protected $detector;

    /**
     * @return \TYPO3\Flow\I18n\Locale
     */
    public function getDefaultLocaleLanguage() {
        return $this->i18nService->getConfiguration()->getDefaultLocale();
    }

    /**
     * @return \TYPO3\Flow\I18n\Locale
     */
    public function getLocale() {
        return $this->i18nService->getConfiguration()->getCurrentLocale();
    }

    /**
     * Get a locale matching the identifier string
     * @param string $identifier
     * @return \TYPO3\Flow\I18n\Locale
     */
    public function getLocaleByIdentifier($identifier) {
        return $this->detector->detectLocaleFromLocaleTag($identifier);
    }

    public function findAll() {
        return $this->cldrUtility->getLanguages();
    }
}