<?php

if (!function_exists('vueTrans')) {
    function vueTrans($strings = [])
    {
        $translations = [];
        foreach ($strings as $key => $string) {
            /* Translations with parameters */
            if (is_array($string)) {
                foreach ($string as $stringPart) {
                    if (is_string($stringPart)) {
                        $transKey = $stringPart;
                    }
                    if (is_array($stringPart)) {
                        $transParams = $stringPart;
                    }
                }
                if (!isset($transKey) || !isset($transParams)) {
                    continue;
                }
                $index = explode(".", $transKey);
                $translations[is_string($key) ? $key : $index[count($index) - 1]] = trans($transKey, $transParams);
                continue;
            }
            /* Translations without parameters */
            $index = explode(".", $string);
            try {
                $translations[is_string($key) ? $key : $index[count($index) - 1]] = trans($string);
            } catch (Exception $e) {
                \Log::info($e->getMessage());
            }

        }

        return json_encode($translations, JSON_UNESCAPED_UNICODE);
    }
}