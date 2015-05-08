<?php
    namespace Ap\Apps\Test\Libs;
    
    use Ap\Ap;
    
/**
 * Конструктор ссылок
 * @author Мокряк Владислав
 */
    class LinkBuilder
    {
        /**
        * @var object Хранилище объекта
        */
        private static $obj = null;
        
        /**
        * @var bool флаг вкл./откл. mod_rewrite
        */
        private $rewrite;
               
        /**
        * @var string Хост от корня веб сервера
        */
        private $host;
        
        /**
        * @var string Пусть к изображениями от корня веб сервера
        */
        private $img;
        
        /**
        * @var string Пусть к css файлам от корня веб сервера
        */
        private $css;
        
        /**
        * @var string Css кэш от корня веб сервера
        */
        private $csscache;
        
        /**
        * @var string Пусть к JS скриптам от корня веб сервера
        */
        private $js;
        
        /**
        * @var string Пусть к текущей странице от корня веб сервера
        */
        private $page;
        
        /**
         * @var array Параметры конфигурации
         */
        private $config;
        
        function __construct()
        {
            $this->config = Ap::confData();
            
            $this->rewrite  = $this->config['general']['rewrite'];
            $this->host     = $this->config['hosts']['host'];
            $this->img      = $this->config['hosts']['imghost'];
            $this->mimg     = $this->config['hosts']['mimghost'];
            $this->css      = $this->config['hosts']['csshost'];
            $this->csscache = $this->config['hosts']['csshost'] .
                $this->config['general']['csscache'];
            $this->js       = $this->config['hosts']['jshost'];
            $this->page     = $this->config['hosts']['pagehost'];
        }
        
        /**
         * Создатель класса
         */
        public static function init()
        {
           if (self::$obj === null)
                self::$obj = new self();
                
            return self::$obj;
        }
        
        /**
         * Доступ к приватным свойствам
         */
        public function __get($name)
        {                       
            return $this->$name;    
        }
        
        /**
         * Формирование ссылок
         */
        public function __call($name, $path)
        {     
            // Вернуть адрес текущей страницы
            if ($name == 'page')
                return $this->$name;
            
            // Вернуть требуемый адрес (ничего не передано)
            if (!isset($path[0]))
                return $this->$name;
                
            $path = str_replace(array('/', '\\'), '/', $path[0]);
            
            // Если путь начинается со /, убераем его
            if(substr($path, 0, 1) == '/')
                $path = substr($path, 1);
            
            // Вернуть переданный путь
            if ($name != 'host')
                return $this->$name . $path;
            
            // Если в конце пути нет слеша, добавить его
            if(substr($path, -1) != '/')
                $path = $path . '/';
                
            // Вернуть переданный путь
            if ($this->rewrite)
                return $this->$name . $path;
            
            // Если путь не ЧПУ подобный(обычный), вернуть его как передан
            if(!strpos($path, '/'))
                return $this->$name . '?'. $path;
 
            $parts = explode('/', $path);
            
            // Если что-то есть, это контроллер
            if (!empty($parts))   
                $path = '?'. $this->config['general']['pnamewmr'] .
                    '='. array_shift($parts);

            // Обработка оставшихся параметров
            while(($part = array_shift($parts)) !== null) {
                if (!empty($part))
                $path .= '/'. $part;
            }
            
            return $this->$name . $path;
        }
    }
