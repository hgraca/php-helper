<?php
namespace Hgraca\Helper;

use Hgraca\Helper\Concept\HelperAbstract;

final class StringHelper extends HelperAbstract
{
    public static function isEmpty(string $value): bool
    {
        return $value === '';
    }

    /**
     * Returns true if this string starts with the given needle.
     *
     * @param string $needle
     * @param string $haystack
     *
     * @return bool
     */
    public static function has(string $needle, string $haystack): bool
    {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }

    /**
     * Returns true if this string starts with the given needle.
     *
     * @param string $needle
     * @param string $haystack
     *
     * @return bool
     */
    public static function hasBeginning(string $needle, string $haystack): bool
    {
        return $needle === '' || strpos($haystack, $needle) === 0;
    }

    /**
     * Returns true if this string ends with the given needle.
     *
     * @param string $needle
     * @param string $haystack
     *
     * @return bool
     */
    public static function hasEnding(string $needle, string $haystack): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }

    public static function toCamel(string $string, array $wordSeparators = ['-', '_', ' ']): string
    {
        return lcfirst(static::toStudly($string, $wordSeparators));
    }

    public static function toSnake(string $string, string $delimiter = '_', array $wordSeparators = ['-', '_', ' ']): string
    {
        $string = static::toStudly($string, $wordSeparators);

        $replace = '$1' . $delimiter . '$2';

        return (ctype_lower($string) ? $string : strtolower(preg_replace('/(.)([A-Z])/', $replace, $string)));
    }

    public static function toStudly(string $string, array $wordSeparators = ['-', '_', ' ']): string
    {
        return str_replace(' ', '', ucwords(str_replace($wordSeparators, ' ', $string)));
    }

    public static function isJson(string $string): bool
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function remove(string $search, string $haystack): string
    {
        return static::replace($search, '', $haystack);
    }

    public static function removeFromBeginning(string $search, string $haystack): string
    {
        return $search === '' ? $haystack : static::replaceFromBeginning($search, '', $haystack);
    }

    public static function removeFromEnd(string $search, string $haystack): string
    {
        return static::replaceFromEnd($search, '', $haystack);
    }

    public static function replace(string $search, string $replacement, string $haystack): string
    {
        return str_replace($search, $replacement, $haystack);
    }

    public static function replaceFromBeginning(string $search, string $replacement, string $haystack): string
    {
        if ($search === '' || !self::hasBeginning($search, $haystack)) {
            return $haystack;
        }

        $search = '/' . preg_quote($search, '/') . '/';

        return preg_replace($search, $replacement, $haystack, 1);
    }

    public static function replaceFromEnd(string $search, string $replacement, string $haystack): string
    {
        return strrev(self::replaceFromBeginning(strrev($search), strrev($replacement), strrev($haystack)));
    }

    /**
     * Given a string, it returns an SEO friendly slug.
     * IE:
     * echo slug('`~#this_is_%%a!{(title)}[]_of<>_p@o&$st*;,.\/-~!'); // this-is-a-title-of-post
     *
     * @param string $string
     * @param bool   $resultWithDashes   set this to false if you want output with spaces as a separator
     * @param bool   $inputIsEnglishOnly set this to false if your input contains non english words
     *
     * @return  string
     */
    public static function toSlug(string $string, bool $resultWithDashes = true, bool $inputIsEnglishOnly = true): string
    {
        $string = str_replace(['"', '+', "'"], ['', ' ', ''], urldecode($string));

        $string = preg_replace('/[\@\$\&]/', '', $string);
        if ($inputIsEnglishOnly === true) {
            $string = preg_replace('/[~`\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/', ' ', $string);
        } else {
            $string = preg_replace('/[^A-Za-z0-9\s\s+\.\)\(\{\}\-]/', ' ', $string);
        }

        $bad_brackets = ['(', ')', '{', '}'];
        $string       = str_replace($bad_brackets, ' ', $string);
        $string       = preg_replace('/\s+/', ' ', $string);
        $string       = trim($string, ' .-');

        if ($resultWithDashes === true) {
            $string = str_replace(' ', '-', $string);
        }

        return strtolower(preg_replace('/-+/', '-', $string));
    }
}
