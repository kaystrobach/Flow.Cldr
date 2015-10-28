<?php

namespace KayStrobach\Cldr\Command;

use KayStrobach\Cldr\Domain\Model\Language;
use KayStrobach\Cldr\Domain\Repository\LanguageRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Debugger;


class LanguageCommandController extends \TYPO3\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\I18n\Detector
     */
    protected $detector;

    /**
     * @param string $locale
     */
    public function showCommand($locale = 'en_EN') {
        ini_set('memory_limit', '340M');
        /** @var Language $language */
        $languages = $this->languageRepository->findAll();

        $localeObject = $this->detector->detectLocaleFromLocaleTag($locale);


        foreach($languages as $language) {
            #echo $language->getLocalizedName() . PHP_EOL;
            echo $language->getNameForLocale($localeObject) . PHP_EOL;
        }
    }
}
