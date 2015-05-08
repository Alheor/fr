<?php
    namespace Ap\Core\Base;
    
    use Ap\Ap;
    
/**
 * Базовый роутер
 * @author    Мокряк Владислав
 */
    abstract class BRouter
    {       
        /**
         * @var string Каталог с компонентами
         */
        protected $cpntpath;
        
        /**
         * @var string Запрос
         */
        protected $request;
        
        /**
         * @var array Данные(GET и POST) запроса
         */
        protected $params;
        
        /**
         * @var array Массив с параметрами запроса
         */
        protected $result;
    
        /**
         * @var array Параметры конфигурации
         */
        protected $config;
        
        public function __construct($request)
        {    
            $this->config = Ap::confData();
            $UrlManager = Ap::import('%Core%\UrlManager', 'init');
            
            $this->request = $UrlManager()->request;
            $this->params  = $UrlManager()->params;
            
            $this->parsePath($request);
        }
        
        /**
         * Доступ к свойствам класса
         * @var string $name имя поля
         */
        public function __get($name)
        {
            return $this->$name;
        }
        
        abstract function parsePath(array $request);
    }
 