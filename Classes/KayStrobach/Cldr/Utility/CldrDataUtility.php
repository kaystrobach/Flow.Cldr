<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 28.10.15
 * Time: 07:37
 */

namespace KayStrobach\Cldr\Utility;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Error\Debugger;
use Neos\Flow\I18n\Cldr\CldrModel;
use KayStrobach\Cldr\Domain\Model\Language;
use Neos\Flow\I18n\Locale;

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
     * @var \Neos\Cache\Frontend\VariableFrontend
     */
    protected $languageCache;

    /**
     * @var \Neos\Flow\Log\SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @var \Neos\Flow\I18n\Cldr\CldrRepository
     * @Flow\Inject
     */
    protected $cldrRepository;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\I18n\Detector
     */
    protected $detector;

    /**
     * @param \Neos\Cache\Frontend\VariableFrontend $cache
     */
    public function setLanguageCache(\Neos\Cache\Frontend\VariableFrontend $cache) {
        $this->languageCache = $cache;
    }

    /**
     * Get an array of all language names
     * @return array|boolean
     */
    public function getLanguages() {
        $languagesFromCache = $this->languageCache->get('languages');
        if($languagesFromCache) {
            return $languagesFromCache;
        } else {
            $buffer = $this->getKeyValues('localeDisplayNames/languages');

        }
        $languages = array();
        foreach($buffer as $key=>$name) {
            $languages[$key] = new Language($key);
        }

        $this->languageCache->set('languages', $languages);
        return $languages;
    }

    /**
     * @param Locale $locale
     * @param Language $language
     * @return string
     */
    public function getLanguageLocalizedName(Locale $locale, Language $language) {
        $cacheIdentifier = 'language-labels-' . $locale->getLanguage();
        $labelsFromCache = $this->languageCache->get($cacheIdentifier);
        if($labelsFromCache) {
            return $labelsFromCache[$language->getKey()];
        }

        try {
            $raw = $this->cldrRepository->getModelForLocale($locale)->getRawArray('localeDisplayNames/languages');
        } catch(\Exception $e) {
            return 'Problem reading data for ' . $language->getKey();
        }

        $languages = $this->getLanguages();
        $labels = array();
        /** @var Language $currentLanguage */

        foreach($languages as $currentLanguage) {
            try {
                $key = 'language[@type="' . $currentLanguage->getKey() . '"]';
                if(is_array($raw)) {
                    if(array_key_exists($key, $raw)) {
                        $labels[$currentLanguage->getKey()] = $raw[$key];
                    } else {
                        $labels[$currentLanguage->getKey()] = $currentLanguage->getName();
                    }
                } else {
                    $labels[$currentLanguage->getKey()] = 'Nothing found for ' . $currentLanguage->getKey();
                }

            } catch(\Exception $e) {
                // not found
                // $labels[$key] = 'Nothing found for ' . $language->getKey();
            }
        }
        $this->languageCache->set($cacheIdentifier, $labels);
        return $labels[$language->getKey()];
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