<?php
    namespace Ap\Core;
    
    use Ap\Ap;

/**
 * Урл менеджер
 * @author    Мокряк Владислав
 */
    
    class UrlManager
    {
        /**
         * @var object Хранилище объекта
         */
        static private $object = null;
        
        /**
         * @var string Запрос
         */
        private $request;
        
         /**
         * @var array Параметры конфигурации
         */
        private $config;
        
        /**
         * @var array Параметны из GET и POST запросов
         */
        private $params;
        
        static public function init()
        {
            if (self::$object === null) {
                self::$object = new self();
            
                self::$object->getRequestFromUrl();
                self::$object->parseUrlParams();
            }
            
            return self::$object;
        }
        
        private function __construct(){
            $this->config = Ap::confData();
        }
    
        /**
         * Доступ к приватным полям
         * @var string $name имя поля
         */
        public function __get($name)
        {
            return $this->$name;
        }
    
        /**
         * Формирование данных из URL
         */
        private function getRequestFromUrl()
        {
            if (!isset($_GET[$this->config['general']['pnamewmr']]) || 
                empty($_GET[$this->config['general']['pnamewmr']])
            )    
                $this->request = $this->config['general']['basectrl'] .'/'.
                    $this->config['general']['baseaction'];
            else
                $this->request = preg_replace('/[^a-zа-яё0-9_\-\/]*/iu',
                    '', $_GET[$this->config['general']['pnamewmr']]);
        }

        /**
         * Дополнение параметров запроса
         * Если в URL присутсвутю данные после ? (a=b&c=d)
         */
        private function parseUrlParams()
        {
            $GET = $_GET;
            
            if (isset($GET[$this->config['general']['pnamewmr']]))
                unset($GET[$this->config['general']['pnamewmr']]);
             
            $this->params = array_merge($GET, $_POST);
        }
    }
    