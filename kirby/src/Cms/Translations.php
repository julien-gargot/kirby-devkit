<?php

namespace Kirby\Cms;

use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;

/**
 * A collection of all available Translations.
 * Provides a factory method to convert an array
 * to a collection of Translation objects and load
 * method to load all translations from disk
 */
class Translations extends Collection
{
    public static function factory(array $translations)
    {
        $collection = new static;

        foreach ($translations as $code => $props) {
            $translation = new Translation($code, $props);
            $collection->data[$translation->code()] = $translation;
        }

        return $collection;
    }

    public static function load(string $root, array $inject = [])
    {
        $collection = new static;

        foreach (Dir::read($root) as $filename) {
            if (F::extension($filename) !== 'json') {
                continue;
            }

            $locale      = F::name($filename);
            $translation = Translation::load($locale, $root . '/' . $filename, $inject[$locale] ?? []);

            $collection->data[$locale] = $translation;
        }

        return $collection;
    }
}
