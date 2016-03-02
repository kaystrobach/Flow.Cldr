<?php
namespace KayStrobach\Cldr\Domain\Model;

use KayStrobach\Cldr\Utility\CldrDataUtility;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Locale;


/**
 * @todo move, to LocaleDisplayName, but set ORM\TableName
 * @Flow\ValueObject
 */
class Language
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

    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $locale = $this->detector->detectLocaleFromLocaleTag('en');
        return $this->getNameForLocale($locale);
    }

    /**
     * @return string
     */
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

    /**
     * @param Locale $locale
     * @return string
     */
    public function getNameForLocale(Locale $locale) {
       return $this->cldrUtility->getLanguageLocalizedName($locale, $this);
    }
}
