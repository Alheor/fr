<?php
    namespace Ap\Apps\Test;
    
    use Ap\Core\Base\BConfig;
    
/**
 * Стандартная конфигурация
 * @author    Мокряк Владислав
 */
    class Config extends BConfig
    {
        /**
         * Параметры конфигурации
         */
        protected function prepareParams()
        {                                  
            // Каталог стиля
            $viewpath = pathinfo($this->syspath, PATHINFO_DIRNAME) . _DS .'views'. _DS . $this->app_ns. _DS; 
                
            //Хость стиля
            $viewhost = _HOST .'views/'. $this->app_ns .'/'; 

            $app_path = __DIR__ . _DS; // Каталог приложения

            return array (
                // Основные параметры
                'general' => array (
                    'apns'       => $this->app_ns,
                    'debug'      => $this->debug, // флаг бедага
                    'rewrite'    => true,        // флаг вкл./откл. mod_rewrite
                    // Параметр с GET данными, должен совпадать
                    // с настройками .htaccess(mod_rewrite) 
                    'pnamewmr'   => 'route',
                    '!deflayout'  => '%AppLayouts%\DefLayout', // Дефолтовый класс вида
                    'csscache'   => 'cache.css',       // Кэш ксс
                    'basectrl'   => 'index',          // Базовый контоллер
                    'baseaction' => 'index1',         // Базовое действие
                    'session_auto_start' => true,     // Старт сессии на каждой странице
                ),

                // Параметры путей от корня диска 
                'paths' => array (
                    // Каталог контроллеров
                    'ctrlpath'   => $app_path .'Ctrls'. _DS,
                    
                    // Каталог моделей
                    'mdlspath'  => $app_path .'Mdls'. _DS,

                    // Каталог шаблонов
                    'tplpath'    => $viewpath .'tpl'. _DS,
                    
                    // Каталог css
                    'csspath'    => $viewpath .'css'. _DS,
                    
                    // Каталог js
                    'jspath'     => $viewpath .'js'. _DS,
                    
                    // Каталог изображений
                    'imgpath'    => $viewpath .'img'. _DS,
                    
                    // Каталог шрифтов
                    'fontspath'  => $viewpath .'fonts'. _DS,
                    
                    // Каталог пользовательских файлов
                    'userfilespath' => $viewpath .'userfiles'. _DS
                ),
                
                // Параметры путей от веб сервера
                'hosts' => array (
                    'host'      => _HOST,     // HTTP путь к системе
                    'viewhost'  => $viewhost, // HTTP путь к стилю
                    'csshost'   => $viewhost .'css/', // HTTP путь к css
                    'jshost'    => $viewhost .'js/',  // HTTP путь к js
                    'imghost'   => $viewhost .'img/d/', // HTTP путь к изображениям
                    'mimghost'  => $viewhost .'img/m/', // HTTP путь к изображениям(моб. версия)
                    'userfihost' => $viewhost .'userfiles/', // HTTP путь к изображениям
                    
                    // HTTP путь к текущей странице(безопасный для веб форм)
                    'pagehost'  => _HOST . htmlspecialchars(substr($_SERVER['REQUEST_URI'], 1)),
                ),
                
                // Библиотеки 
                'libs' => array (
                    '%AppLibs%\MenuBuilder*'    => '%AppLibs%/MenuBuilder', // Шаблонизатор
                    '%AppLibs%\Tpl*'            => '%AppLibs%/Tpl', // Шаблонизатор
                    '%AppLibs%\CssBuilder'      => '%AppLibs%', // Построение css
                    '%AppLibs%\LinkBuilder'     => '%AppLibs%', // Построение ссылок
                    '%AppLibs%\Session'         => '%AppLibs%', // Менеджер сесий
                    '%AppLibs%\Cookies'         => '%AppLibs%' // Куки
                )
            );
        }
    }
    