<?php
    namespace Ap\Apps\Test\Libs;
    
    use Ap\Ap;
    
/**
 * Рабоа с сессией
 * @author Мокряк Владислав
 */
    class Cookies
    {
        /**
         * @var string Имя в пространстве имен
         */
        private $namespace;
        
        /**
         * @var int || string Время жизни куки
         */
        private $exp;
        
        /**
         * @var int Флаг блокировки пространства имен
         */
        private $lock;
        
        /**
         * @var array Данные для записи в пространства имен
         */
        public $data;
        
        /**
         * @var array Массив с данными пространства имен
         */
        private $array;
        
        /**
         * @var array Внутреннее хранилище(по ссылке) кук
         */
        private $cookie;
            
        /**
         * @var array Параметры конфигурации
         */
        private $config;
        
        /**
         * @var string Хост веб сервера
         */
        private $basehost;
        
        /**
         * @var string Протокол веб сервера
         */
        private $protocol;
        
        public function __construct($namespace = null, $exp = null)
        {   
            if (empty($namespace)) {
                $SystemException = Ap::import('%Core%\SystemException');
                throw $SystemException(11, null, '_500');
            }
            
            if (empty($exp)){
                $SystemException = Ap::import('%Core%\SystemException');
                throw $SystemException(13, null, '_500');
            }
            
            $this->config = Ap::confData();
                        
            $this->namespace = $namespace;
            $this->cookie = & $_COOKIE;
            $this->exp = $exp;  
           
            $this->basehost = $this->config['hosts']['basehost'];
            $this->protocol = $this->config['general']['protocol'];
                
            if (!isset($this->cookie[$namespace])) {               
                $this->array = array(
                    'data' => array(),
                    'lock' => 0
                );
                
                $this->lock = 0;
            } else {
                $ser = unserialize($this->cookie[$namespace]);
                $this->array = $ser;
                $this->data = $ser['data'];
                $this->lock = $ser['lock'];
            }
        }
        
        /**
         * Формирование времени в UNIX-time
         */
        private function parseTime()
        {
            if (is_integer($this->exp))
                return time() + $this->exp;
            
            if (is_string($this->exp)) {
                $ttry = strtotime($this->exp);
                if (!$ttry) {
                    $SystemException = Ap::import('%Core%\SystemException');
                    throw $SystemException(14, null, '_500');
                }
                    
                return $ttry;
            }
        }
        
        /**
         * Запись данных в куку
         * @var int || bool $lock флаг блокировки пространства имен
         */
        public function save($lock = 0)
        {      
            if ($this->lock){
                $SystemException = Ap::import('%Core%\SystemException');
                throw $SystemException(12, $this->namespace, '_500');
            }
            
            $this->array['data'] = $this->data;    
          
            if ((bool)$lock)
                $this->array['lock'] = 1;
            else 
                $this->array['lock'] = 0;
                
            $this->lock = $this->array['lock'];
            
            setcookie(
                $this->namespace, 
                serialize($this->array), 
                $this->parseTime(), 
                $this->basehost,
                $this->protocol == 'https'? true : false
            );
        }
        
        /**
         * Уничтожение куки
         */
        public function destroy()
        {          
            setcookie(
                $this->namespace, 
                '', 
                -86400, //сутки
                $this->basehost,
                $this->protocol == 'https'? true : false
            );
        }
        
        /**
         * Разблокировка куки
         */    
        public function unLock()
        {
            $this->array['lock'] = 0;
            $this->lock = 0;
        }
         
        /**
         * Проверка блокировки куки
         */   
        public function isLock()
        {               
            return (bool)$this->lock;
        }
    }
        