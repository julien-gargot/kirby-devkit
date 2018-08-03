<?php

namespace Kirby\Toolkit;

use Closure;
use Exception;

/**
 * Localization class, roughly inspired by VueI18n
 */
class I18n
{

    /**
     * Custom loader function
     *
     * @var Closure
     */
    public static $load = null;

    /**
     * Current locale
     *
     * @var string
     */
    public static $locale = 'en';

    /**
     * All registered translations
     *
     * @var array
     */
    public static $translations = [];

    /**
     * The fallback locale
     *
     * @var string
     */
    public static $fallback = 'en';

    /**
     * Translates a given message
     * according to the currently set locale
     *
     * @param string|array $key
     * @param string|array|null $fallback
     * @return string|array|null
     */
    public static function translate($key, $fallback = null, string $locale = null)
    {
        $locale = $locale ?? static::$locale;

        if (is_array($key) === true) {
            if (isset($key[$locale])) {
                return $key[$locale];
            }
            if (is_array($fallback)) {
                return $fallback[$locale] ?? null;
            }
            return $fallback;
        }

        if ($translation = static::translation($locale)[$key] ?? null) {
            return $translation;
        }

        if ($fallback !== null) {
            return $fallback;
        }

        if ($locale !== static::$fallback) {
            return static::translation(static::$fallback)[$key] ?? null;
        }

        return null;
    }

    /**
     * Returns the current or any other translation
     * by locale. If the translation does not exist
     * yet, the loader will try to load it, if defined.
     *
     * @param string|null $locale
     * @return array
     */
    public static function translation(string $locale = null): array
    {
        $locale = $locale ?? static::$locale;

        if (isset(static::$translations[$locale]) === true) {
            return static::$translations[$locale];
        }

        if (is_a(static::$load, 'Closure') === true) {
            return static::$translations[$locale] = (static::$load)($locale);
        }

        return static::$translations[$locale] = [];
    }

    /**
     * Returns all loaded or defined translations
     *
     * @return array
     */
    public static function translations(): array
    {
        return static::$translations;
    }

    /**
     * Translate amounts
     *
     * @param string $key
     * @param integer $count
     * @return mixed
     */
    public static function translateCount(string $key, int $count, string $locale = null)
    {
        $translation = static::translate($key, null, $locale);

        if ($translation === null) {
            return null;
        }

        if (is_string($translation) === true) {
            return $translation;
        }

        if (count($translation) !== 3) {
            throw new Exception('Please provide 3 translations');
        }

        switch ($count) {
            case 0:
                $message = $translation[0];
                break;
            case 1:
                $message = $translation[1];
                break;
            default:
                $message = $translation[2];
        }

        return str_replace('{{ count }}', $count, $message);
    }
}
