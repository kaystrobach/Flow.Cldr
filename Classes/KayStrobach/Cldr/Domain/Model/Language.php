<?php
namespace KayStrobach\Cldr\Domain\Model;

use KayStrobach\Cldr\Utility\CldrDataUtility;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Locale;


class Language
{
    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\I18n\Detector
     */
    protected $detector;

    /**
     * @var string
     */
    protected $key;
    /**
     * @var string
     */
    protected $name;

    /**
     * @var CldrDataUtility
     * @Flow\Inject
     */
    protected $cldrUtility;

    /**
     * @var \TYPO3\Flow\I18n\Service
     * @Flow\Inject
     */
    protected $i18nService;

    /**
     * @param $key
     * @param $name
     */
    public function __construct($key, $name)
    {
        $this->name = $name;
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getNameByForCurrentLocale() {
        $locale = $this->i18nService->getConfiguration()->getCurrentLocale();
        return $this->getNameForLocale($locale);
    }

    /**
     * @return \TYPO3\Flow\I18n\Locale
     */
    public function getLocalizedName()
    {
        $locale = $this->detector->detectLocaleFromLocaleTag($this->key);
        return $this->getNameForLocale($locale);
    }

    public function getNameForLocale(Locale $locale) {
       return $this->cldrUtility->getLanguageLocalizedName($locale, $this);
    }
}
