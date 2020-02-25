<?php

namespace Studio;

class Studio extends Setup
{
    /**
     * The Sass files that need to be exported.
     *
     * @const array
     */
    private const SASS = [
        '_fonts',
        '_nprogress',
        '_variables',
        'app',
    ];

    /**
     * The Vue components that need to be exported.
     *
     * @const array
     */
    private const COMPONENTS = [
        'PageHeader',
        'PostList',
        'TagList',
        'TopicBar',
    ];

    /**
     * The mixins that need to be exported.
     *
     * @const array
     */
    private const MIXINS = [
        'HelperMixin',
        'RequestMixin',
    ];

    /**
     * The Vue screens that need to be exported.
     *
     * @const array
     */
    private const SCREENS = [
        'HomeScreen',
        'PostScreen',
        'TagScreen',
        'TopicScreen',
        'UserScreen',
    ];

    /**
     * The base files that need to be exported.
     *
     * @const array
     */
    private const BASE = [
        'app',
        'routes',
    ];

    /**
     * Install the package assets.
     *
     * @return void
     */
    public static function install()
    {
        static::ensureDirectoriesExist();
        static::updatePackages();
        static::updateSass();
        static::updateComponents();
        static::updateMixins();
        static::updateScreens();
        static::updateBase();
        static::updateWebpackConfiguration();
        static::removeNodeModules();
    }

    /**
     * Update the given package array.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
                'bootstrap' => '^4.0.0',
                'jquery' => '^3.2',
                'popper.js' => '^1.12',
                'resolve-url-loader' => '^2.3.1',
                'sass' => '^1.20.1',
                'sass-loader' => '^8.0.0',
                'vue' => '^2.5.17',
                'vue-template-compiler' => '^2.6.10',
            ] + $packages;
    }

    /**
     * Update the Sass files for the application.
     *
     * @return void
     */
    protected static function updateSass()
    {
        foreach (self::SASS as $file) {
            copy(__DIR__ . "/../resources/sass/{$file}.scss", resource_path("sass/studio/{$file}.scss"));
        }
    }

    /**
     * Update the Vue components.
     *
     * @return void
     */
    protected static function updateComponents()
    {
        foreach (self::COMPONENTS as $file) {
            copy(__DIR__ . "/../resources/js/components/{$file}.vue", resource_path("js/studio/components/{$file}.vue"));
        }
    }

    /**
     * Update the Vue mixins.
     *
     * @return void
     */
    protected static function updateMixins()
    {
        foreach (self::MIXINS as $file) {
            copy(__DIR__ . "/../resources/js/mixins/{$file}.js", resource_path("js/studio/mixins/{$file}.js"));
        }
    }

    /**
     * Update the Vue screens.
     *
     * @return void
     */
    protected static function updateScreens()
    {
        foreach (self::SCREENS as $file) {
            copy(__DIR__ . "/../resources/js/screens/{$file}.vue", resource_path("js/studio/{$file}.vue"));
        }
    }

    /**
     * Update the base bootstrapping files.
     *
     * @return void
     */
    protected static function updateBase()
    {
        foreach (self::BASE as $file) {
            copy(__DIR__ . "/../resources/js/{$file}.js", resource_path("js/studio/{$file}.js"));
        }
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/../webpack.mix.js', base_path('webpack.mix.js'));
    }
}
