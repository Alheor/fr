<?php   
    namespace Ap\Core;
    
/**
 * Загрузчик классов
 * @author Мокряк Владислав
 */    
    class PathsPreparer
    {  
        /**
         * @var string syspath Рабочий каталог системы
         */
        private $syspath;
        
        /**
         * @var string ns Пространство имен приложения
         */
        private $ns;
        
        /**
         * @var string app_ns Пространство имен приложения
         */
        private $app_ns;
        
        /**
         * @var array $aliases Псевдонимы путей
         */
        private $aliases;
        
        public function __construct($syspath, $ns, $app_ns)
        {     
            $this->syspath  = $syspath;
            $this->ns       = $ns;
            $this->app_ns   = $app_ns;
            
            $this->aliasesPrepare();
        }
        
        /**
         * Формирование псевдонимов пути
         */
        private function aliasesPrepare()
        {
            //Ядро
            $this->aliases['Core'] = array(
                'ns'    => $this->ns .'\\Core\\',
                'path'  => $this->syspath .'Core'. _DS
            );
            
            //Приложение
            $this->aliases['App'] = array(
                'ns'    => $this->ns .'\\Apps\\'. $this->app_ns .'\\',
                'path'  => $this->syspath .'Apps'. _DS . $this->app_ns. _DS
            );
            
            //Библиотеки приложения
            $this->aliases['AppLibs'] = array(
                'ns'    => $this->ns .'\\Apps\\'. $this->app_ns .'\\Libs\\',
                'path'  => $this->syspath .'Apps'. _DS . $this->app_ns. _DS. 'Libs'. _DS
            );
            
            //Контроллеры приложения
            $this->aliases['AppCtrls'] = array(
                'ns'    => $this->ns .'\\Apps\\'. $this->app_ns .'\\Ctrls\\',
                'path'  => $this->syspath .'Apps'. _DS . $this->app_ns. _DS. 'Ctrls'. _DS
            );
            
            //Модели приложения
            $this->aliases['AppMdls'] = array(
                'ns'    => $this->ns .'\\Apps\\'. $this->app_ns .'\\Mdls\\',
                'path'  => $this->syspath .'Apps'. _DS . $this->app_ns. _DS. 'Mdls'. _DS
            );
            
            //Макеты приложения
            $this->aliases['AppLayouts'] = array(
                'ns'    => $this->ns .'\\Apps\\'. $this->app_ns .'\\Layouts\\',
                'path'  => $this->syspath .'Apps'. _DS . $this->app_ns. _DS. 'Layouts'. _DS
            );
        }
        
        /**
         * Поиск посевдонима в пути
         * @var array $alias Пространтво имен
         */
        private function serch($alias)
        {
            $serch = preg_match('/^([^%]*)%([^%]*)%(.*)$/i', $alias, $arr);
            
            if ($serch) {
                return array($arr[1], $arr[2], $arr[3]);
            } else
                return null;
        }
        
        /**
         * Формирование псевдонимов пути
         * @var string $alias Пространтво имен
         */
        public function getPathFromAlias($alias)
        {
            $serch = $this->serch($alias);
            
            if (is_array($serch) && isset($this->aliases[$serch[1]]))
                return $serch[0] . $this->aliases[$serch[1]]['path'] . $serch[2];
            else
                return $alias;
        }
        
        /**
         * Формирование псевдонимов пути
         * @var string $alias Пространтво имен
         */
        public function getNsFromAlias($alias)
        {
            $serch = $this->serch($alias);
            
            if (is_array($serch) && isset($this->aliases[$serch[1]]))
                return  $serch[0] . $this->aliases[$serch[1]]['ns'] . $serch[2];
            else
                return $alias;
        }
    }
