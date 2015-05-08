<?php
    namespace Ap\Apps\Test\Libs;
    
    use Ap\Ap;
    
/**
 * Рабоа с сессией
 * @author Мокряк Владислав
 */
    class Session
    {
        /**
         * @var string Имя в пространстве имен
         */
        private $namespace;
        
        /**
         * @var array || string Данные пространства имен
         */
        public $data;
        
        /**
         * @var array Внутреннее хранилище(по ссылке) массива сессии
         */
        public $session;
            
        public function __construct($namespace = false)
        {
            self::sessionStatus();
            
            $SystemException = Ap::import('%Core%\SystemException');
            
            if (!$namespace)
                throw $SystemException(11, null, '_500');
                
            $this->namespace = $namespace;
            $this->session = & $_SESSION;
            
            if (!isset($this->session[$namespace]))
                // Базовая структура данных
                $this->session[$namespace] = array(
                    'data' => array(),
                    'lock' => 0
                );
            else
                $this->data = $this->session[$namespace]['data'];
            
            self::start();
        }
        
        /**
         * Проверка сесии
         * @return bool
         */
        private static function sessionStatus()
        {            
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                if (session_status() === PHP_SESSION_DISABLED) {
                    $SystemException = Ap::import('%Core%\SystemException');
                    throw $SystemException(10, null, '_500');
                }
                    
                if(session_status() ===  PHP_SESSION_ACTIVE)
                    return true;
                else
                    return false;
            } else
                return session_id() === ''? false : true;
        }
        
        /**
         * Сохранение данных в сесии
         */
        public function save()
        {
            if ($this->isLock()) {
                $SystemException = Ap::import('%Core%\SystemException');
                
                throw new $SystemException(12, $this->namespace, '_500');
            }
                
            $this->session[$this->namespace]['data'] = $this->data;
        }
        
        /**
         * Инициализация сессии
         */
        public static function init()
        {          
            $config = Config::getdata();
            
            if (version_compare(phpversion(), '5.4.0', '>='))
                if (session_status() === PHP_SESSION_DISABLED) {
                    $SystemException = Ap::import('%Core%\SystemException');
                    throw $SystemException(10, null, '_500');
                }
            else
                if (session_id() === '' && $config['general']['session_auto_start'])
                    self::start();
        }
         
        /**
         * Запуск сесии
         */
        public static function start()
        {
            if(!self::sessionStatus())
                session_start();
        }
        
        /**
         * Завершение сессии
         */    
        public static function end()
        {
            if (self::sessionStatus()) {
                session_destroy();
                session_write_close();
            }
        }
        
        /**
         * Блокировка пространства имен сессии
         */ 
        public function lock()
        {
           $this->session[$this->namespace]['lock'] = 1;
        }
        
        /**
         * Разблокировка пространства имен сессии
         */ 
        public function unLock()
        {
            $this->session[$this->namespace]['lock'] = 0;
        }
        
        /**
         * Проверка блокировки
         * @return bool
         */  
        public function isLock()
        {
            return (bool)$this->session[$this->namespace]['lock'];
        }
        
        /**
         * Возвращает ID сесии
         * @return string
         */
        public static function id()
        {
            return session_id();
        }
    }
        