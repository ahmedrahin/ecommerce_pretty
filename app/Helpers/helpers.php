<?php

use Illuminate\Support\Str;

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $words = ucfirst($formatter->format($number));

        return $words . '  only';
    }
}

if (!function_exists('theme')) {
    function theme()
    {
        return app(App\Core\Theme::class);
    }
}


if (!function_exists('formatCurrencyShort')) {
    function formatCurrencyShort($amount): string
    {
        if ($amount >= 1000000) {
            return '$' . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return '$' . number_format($amount / 1000, 1) . 'K';
        } else {
            return '$' . number_format($amount, 2);
        }
    }
}


function updateEnv($key, $value)
{
    $path = base_path('.env');

    if (file_exists($path)) {
        // Ensure the value is properly quoted if it contains spaces
        $formattedValue = strpos($value, ' ') !== false ? "\"{$value}\"" : $value;

        // Read the existing .env file content
        $env = file_get_contents($path);

        // Match only the line containing APP_NAME and update its value
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $env)) {
            // Replace the line that matches the APP_NAME key
            $env = preg_replace($pattern, "{$key}={$formattedValue}", $env);
        } else {
            // If APP_NAME doesn't exist, add it at the end of the file
            $env .= "\n{$key}={$formattedValue}";
        }

        // Write the updated content back to the .env file
        file_put_contents($path, $env);
    }
}


if (!function_exists('getName')) {
    /**
     * Get product name
     *
     * @return void
     */
    function getName()
    {
        return config('settings.KT_THEME');
    }
}


if (!function_exists('addHtmlAttribute')) {
    /**
     * Add HTML attributes by scope
     *
     * @param $scope
     * @param $name
     * @param $value
     *
     * @return void
     */
    function addHtmlAttribute($scope, $name, $value)
    {
        theme()->addHtmlAttribute($scope, $name, $value);
    }
}


if (!function_exists('addHtmlAttributes')) {
    /**
     * Add multiple HTML attributes by scope
     *
     * @param $scope
     * @param $attributes
     *
     * @return void
     */
    function addHtmlAttributes($scope, $attributes)
    {
        theme()->addHtmlAttributes($scope, $attributes);
    }
}


if (!function_exists('addHtmlClass')) {
    /**
     * Add HTML class by scope
     *
     * @param $scope
     * @param $value
     *
     * @return void
     */
    function addHtmlClass($scope, $value)
    {
        theme()->addHtmlClass($scope, $value);
    }
}


if (!function_exists('printHtmlAttributes')) {
    /**
     * Print HTML attributes for the HTML template
     *
     * @param $scope
     *
     * @return string
     */
    function printHtmlAttributes($scope)
    {
        return theme()->printHtmlAttributes($scope);
    }
}


if (!function_exists('printHtmlClasses')) {
    /**
     * Print HTML classes for the HTML template
     *
     * @param $scope
     * @param $full
     *
     * @return string
     */
    function printHtmlClasses($scope, $full = true)
    {
        return theme()->printHtmlClasses($scope, $full);
    }
}


if (!function_exists('getSvgIcon')) {
    /**
     * Get SVG icon content
     *
     * @param $path
     * @param $classNames
     * @param $folder
     *
     * @return string
     */
    function getSvgIcon($path, $classNames = 'svg-icon', $folder = 'assets/media/icons/')
    {
        return theme()->getSvgIcon($path, $classNames, $folder);
    }
}


if (!function_exists('setModeSwitch')) {
    /**
     * Set dark mode enabled status
     *
     * @param $flag
     *
     * @return void
     */
    function setModeSwitch($flag)
    {
        theme()->setModeSwitch($flag);
    }
}


if (!function_exists('isModeSwitchEnabled')) {
    /**
     * Check dark mode status
     *
     * @return void
     */
    function isModeSwitchEnabled()
    {
        return theme()->isModeSwitchEnabled();
    }
}


if (!function_exists('setModeDefault')) {
    /**
     * Set the mode to dark or light
     *
     * @param $mode
     *
     * @return void
     */
    function setModeDefault($mode)
    {
        theme()->setModeDefault($mode);
    }
}


if (!function_exists('getModeDefault')) {
    /**
     * Get current mode
     *
     * @return void
     */
    function getModeDefault()
    {
        return theme()->getModeDefault();
    }
}


if (!function_exists('setDirection')) {
    /**
     * Set style direction
     *
     * @param $direction
     *
     * @return void
     */
    function setDirection($direction)
    {
        theme()->setDirection($direction);
    }
}


if (!function_exists('getDirection')) {
    /**
     * Get style direction
     *
     * @return void
     */
    function getDirection()
    {
        return theme()->getDirection();
    }
}


if (!function_exists('isRtlDirection')) {
    /**
     * Check if style direction is RTL
     *
     * @return void
     */
    function isRtlDirection()
    {
        return theme()->isRtlDirection();
    }
}


if (!function_exists('extendCssFilename')) {
    /**
     * Extend CSS file name with RTL or dark mode
     *
     * @param $path
     *
     * @return void
     */
    function extendCssFilename($path)
    {
        return theme()->extendCssFilename($path);
    }
}


if (!function_exists('includeFavicon')) {
    /**
     * Include favicon from settings
     *
     * @return string
     */
    function includeFavicon()
    {
        return theme()->includeFavicon();
    }
}


if (!function_exists('includeFonts')) {
    /**
     * Include the fonts from settings
     *
     * @return string
     */
    function includeFonts()
    {
        return theme()->includeFonts();
    }
}


if (!function_exists('getGlobalAssets')) {
    /**
     * Get the global assets
     *
     * @param $type
     *
     * @return array
     */
    function getGlobalAssets($type = 'js')
    {
        return theme()->getGlobalAssets($type);
    }
}


if (!function_exists('addVendors')) {
    /**
     * Add multiple vendors to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendors
     *
     * @return void
     */
    function addVendors($vendors)
    {
        theme()->addVendors($vendors);
    }
}


if (!function_exists('addVendor')) {
    /**
     * Add single vendor to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendor
     *
     * @return void
     */
    function addVendor($vendor)
    {
        theme()->addVendor($vendor);
    }
}


if (!function_exists('addJavascriptFile')) {
    /**
     * Add custom javascript file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addJavascriptFile($file)
    {
        theme()->addJavascriptFile($file);
    }
}


if (!function_exists('addCssFile')) {
    /**
     * Add custom CSS file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addCssFile($file)
    {
        theme()->addCssFile($file);
    }
}


if (!function_exists('getVendors')) {
    /**
     * Get vendor files from settings. Refer to settings KT_THEME_VENDORS
     *
     * @param $type
     *
     * @return array
     */
    function getVendors($type)
    {
        return theme()->getVendors($type);
    }
}


if (!function_exists('getCustomJs')) {
    /**
     * Get custom js files from the settings
     *
     * @return array
     */
    function getCustomJs()
    {
        return theme()->getCustomJs();
    }
}


if (!function_exists('getCustomCss')) {
    /**
     * Get custom css files from the settings
     *
     * @return array
     */
    function getCustomCss()
    {
        return theme()->getCustomCss();
    }
}


if (!function_exists('getHtmlAttribute')) {
    /**
     * Get HTML attribute based on the scope
     *
     * @param $scope
     * @param $attribute
     *
     * @return array
     */
    function getHtmlAttribute($scope, $attribute)
    {
        return theme()->getHtmlAttribute($scope, $attribute);
    }
}


if (!function_exists('isUrl')) {
    /**
     * Get HTML attribute based on the scope
     *
     * @param $url
     *
     * @return mixed
     */
    function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}


if (!function_exists('image')) {
    /**
     * Get image url by path
     *
     * @param $path
     *
     * @return string
     */
    function image($path)
    {
        return asset('assets/media/'.$path);
    }
}


if (!function_exists('getIcon')) {
    /**
     * Get icon
     *
     * @param $path
     *
     * @return string
     */
    function getIcon($name, $class = '', $type = '', $tag = 'span')
    {
        return theme()->getIcon($name, $class, $type, $tag);
    }
}


if (!function_exists('formatPrice')) {
    /**
     * Get icon
     *
     * @param $path
     *
     * @return string
     */
    function formatPrice($price)
    {
        if (fmod($price, 1) === 0.00) {
            return number_format($price, 0);
        } else {
            return number_format($price, 2);
        }
    }
}


if (!function_exists('getDiscountType')) {
    /**
     * Get icon
     *
     * @param $path
     *
     * @return string
     */
    function getDiscountType($type)
    {
        if ($type==2) {
            return "Percentage";
        } elseif($type==3) {
            return "Flat";
        }else{
            return "No Discount";
        }
    }


    if (!function_exists('uploadFile')) {
        function fileUpload($file, $folder, $name)
        {
            // Check if file is not null
            if ($file) {
                $imageName = Str::slug($name) . '._' . rand(1,9999) . '.' . $file->extension();
                $file->move(public_path('uploads/' . $folder), $imageName);
                $path = 'uploads/' . $folder . '/' . $imageName;
                return $path;
            }
            return null;
        }
    }

    if (!function_exists('format_price')) {
        function format_price($price)
        {
            if ($price === null || $price === '') {
                return $price;
            }

            // Clean commas if string
            if (!is_numeric($price)) {
                $clean = str_replace(',', '', $price);
                if (!is_numeric($clean)) {
                    return $price; // not numeric, return as-is
                }
                $price = $clean;
            }

            // Prefer PHP Intl NumberFormatter (gives correct lakh format)
            if (class_exists(\NumberFormatter::class)) {
                $fmt = new \NumberFormatter('en_IN', \NumberFormatter::DECIMAL);
                $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
                return $fmt->format((float)$price);
            }

            // Fallback if Intl is not available
            $num = (string) round((float) $price);
            $len = strlen($num);
            if ($len <= 3) {
                return $num;
            }
            $last3 = substr($num, -3);
            $rest = substr($num, 0, -3);
            $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
            return $rest . ',' . $last3;
        }
    }

}
