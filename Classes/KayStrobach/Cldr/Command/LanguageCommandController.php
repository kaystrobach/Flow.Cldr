<?php

namespace KayStrobach\Cldr\Command;

use KayStrobach\Cldr\Domain\Model\Language;
use KayStrobach\Cldr\Domain\Repository\LanguageRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Error\Debugger;


class LanguageCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\I18n\Detector
     */
    protected $detector;

    public function generateAllCommand() {
        $languages = $this->languageRepository->findAll();
        /** @var Language $language */
        foreach($languages as $language) {
            $this->outputFormatted($language->getKey() . ' - ' . $language->getLocalizedName());
        }
    }

    /**
     * @param string $locale
     */
    public function showCommand($locale = 'en_EN') {
        ini_set('memory_limit', '340M');
        /** @var Language $language */
        $languages = $this->languageRepository->findAll();
        $localeObject = $this->detector->detectLocaleFromLocaleTag($locale);

        foreach($languages as $language) {
            $this->outputFormatted($language->getKey() . ' - ' . $language->getNameForLocale($localeObject));
        }
    }
}
