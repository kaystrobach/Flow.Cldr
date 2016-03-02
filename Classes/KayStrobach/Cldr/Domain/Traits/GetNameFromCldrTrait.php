<?php

namespace KayStrobach\Cldr\Domain\Traits;

use KayStrobach\Cldr\Utility\CldrDataUtility;
use TYPO3\Flow\I18n\Locale;

trait GetNameFromCldrTrait
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @Flow\Inject
     * @Flow\Transient()
     * @var \TYPO3\Flow\I18n\Detector
     */
    protected $detector;

    /**
     * @Flow\Inject
     * @Flow\Transient()
     * @var CldrDataUtility
     */
    protected $cldrUtility;

    /**
     * @Flow\Inject
     * @Flow\Transient()
     * @var \TYPO3\Flow\I18n\Service
     */
    protected $i18nService;

    /**
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the name for the entity for locale en
     *
     * @return string
     */
    public function getName()
    {
        $locale = $this->detector->detectLocaleFromLocaleTag('en');
        return $this->getNameForLocale($locale);
    }

    /**
     * Returns the name of the entity currently set in i18n Service
     *
     * @return string
     */
    public function getNameByCurrentLocale() {
        $locale = $this->i18nService->getConfiguration()->getCurrentLocale();
        return $this->getNameForLocale($locale);
    }

    /**
     * Returns name of the entity in the native language
     * --> just works for languages
     * @todo
     *
     * @return string
     */
    public function getLocalizedName()
    {
        $locale = $this->detector->detectLocaleFromLocaleTag($this->key);
        return $this->getNameForLocale($locale);
    }

    /**
     * @param Locale $locale
     * @return string
     */
    public function getNameForLocale(Locale $locale) {
        return $this->cldrUtility->getLanguageLocalizedName($locale, $this);
    }
}