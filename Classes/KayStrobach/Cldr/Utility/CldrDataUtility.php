<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 28.10.15
 * Time: 07:37
 */

namespace KayStrobach\Cldr\Utility;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Cldr\CldrModel;
use KayStrobach\Cldr\Domain\Model\Language;
use TYPO3\Flow\I18n\Locale;

/**
 * Class CldrDataUtility
 *
 * @Flow\Scope("singleton")
 *
 * @package KayStrobach\Cldr\Utility
 */
class CldrDataUtility
{
    /**
     * @var \TYPO3\Flow\Cache\Frontend\VariableFrontend
     */
    protected $languageCache;

    /**
     * @var \TYPO3\Flow\Log\SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @var \TYPO3\Flow\I18n\Cldr\CldrRepository
     * @Flow\Inject
     */
    protected $cldrRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\I18n\Detector
     */
    protected $detector;

    /**
     * @param \TYPO3\Flow\Cache\Frontend\VariableFrontend $cache
     */
    public function setLanguageCache(\TYPO3\Flow\Cache\Frontend\VariableFrontend $cache) {
        $this->languageCache = $cache;
    }

    /**
     * Get an array of all language names
     * @return array|boolean
     */
    public function getLanguages() {
        if($this->languageCache->get('languages')) {
            $buffer = $this->languageCache->get('languages');
        } else {
            $buffer = $this->getKeyValues('localeDisplayNames/languages');
            $this->languageCache->set('languages', $buffer);
        }
        $languages = array();
        foreach($buffer as $key=>$name) {
            $languages[] = new Language($key, $name);
        }
        return $languages;
    }

    /**
     * @param Locale $locale
     * @param Language $language
     * @return string
     */
    public function getLanguageLocalizedName(Locale $locale, Language $language) {
        $cacheIdentifier = 'label-' . $language->getKey() . '-in-' . $locale->getLanguage();
        if($this->languageCache->get($cacheIdentifier)) {
            return $this->languageCache->get($cacheIdentifier);
        }
        try {
            $key = 'language[@type="' . $language->getKey() . '"]';
            $raw = $this->cldrRepository->getModelForLocale($locale)->getRawArray('localeDisplayNames/languages');

            unset($locale);
            if(is_array($raw)) {
                if(array_key_exists($key, $raw)) {
                    $label = $raw[$key];
                } else {
                    $label = $language->getName();
                }
            } else {
                $label = 'Nothing found for ' . $language->getKey();
            }
            $this->languageCache->set($cacheIdentifier, $label);
            return $label;
        } catch(\Exception $e) {
            // do nothing
        }
        return 'Nothing found for ' . $language->getKey();
    }
    /**
     * Get an array of all values in the CLDR where the key is the type attribute
     *
     * @param string $path The xpath to select values from
     * @return array|boolean
     */
    protected function getKeyValues($path) {
        $defaultLocale = $this->detector->detectLocaleFromLocaleTag('en');
        $model = $this->cldrRepository->getModelForLocale($defaultLocale);
        $data = $model->getRawArray($path);
        if ($data === FALSE) {
            return FALSE;
        }
        $filteredData = array();
        foreach ($data as $nodeString => $children) {
            if (CldrModel::getAttributeValue($nodeString, 'alt') === FALSE) {
                $key = CldrModel::getAttributeValue($nodeString, 'type');
                $filteredData[$key] = $children;
            }
        }
        return $filteredData;
    }
}