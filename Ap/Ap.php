<?php
    namespace Ap;
    
    use Ap\Core\SystemException;
    use Ap\Core\ClassLoader;
    use Ap\Core\PathsPreparer;
    
/**
* Инициализатор приложения
* @author    Мокряк Владислав
*/
    class Ap 
    {    
        /**
         * @var object Хранилище объекта
         */
        private static $object = null;
        
        /**
         * @var string Рабочий каталог системы
         */
        private $syspath;
        
        /**
         * @var bool Флаг дебага
         */
        private $debug = false;
                
        /**
         * @var string Пространство имен приложения
         */
        private $app_ns;
        
        /**
         * @var string Пространство имен ядра
         */
        private $ns;
        
        /**
         * @var array Массив c конфигурацией
         */
        private $config;
        
        /**
         * @var object Экземпляр загрузчика классов
         */
        private $cl;
        
        /**
         * @var object Экземпляр парсера путей
         */
        private $pp;
        
        /**
         * @var object Экземпляр пользовательского приложения
         */
        private $app;
        
        /**
         * @var array Информация о текущем запросе
         */
        private $request;
        
        
        private function __construct($debug, $ns, $app_ns)
        {    
            require 'Core'. _DS .'ClassLoader.php';
            require 'Core'. _DS .'PathsPreparer.php';
            require 'Core'. _DS .'SystemException.php';
            
            $this->syspath  = __DIR__. _DS; // Каталог системы
           
            $this->debug    = $debug;
            $this->app_ns   = $app_ns;
            $this->ns       = $ns;
            
            $this->cl       = new ClassLoader($this->syspath, $ns, $app_ns);
            $this->pp       = new PathsPreparer($this->syspath, $ns, $app_ns); 
        }
        
        /**
         * Создатель класса
         * @var bool $debug Флаг дебага
         * @var string $app_ns Пространство имен приложения
         * @var string $ns Пространтсво имен ядра
         */
        public static function run($debug = 0, $ns, $app_ns)
        {
            if (self::$object === null) {
                 
                error_reporting($debug * -1); //Вывод ошибок
                
                self::$object = new self($debug, $ns, $app_ns);
                self::$object->init();
            }
        }
        
        /**
         * Возвращает массив с конфигурацией
         * @return array
         */
        public static function confData()
        {
            return self::$object->app->config;
        }
        
        /**
         * Импорт класса
         * @var string $path Путь класс в пространстве имен
         * @var string $method Имя статического метода
         * @return mixed
         */
        public static function import($path, $method = null)
        {
            try{
                return self::$object->cl->call($path, $method);
            } catch(SystemException $e) {
                echo $e->log();
            }                 
        }
        
        /**
         * Регистрация классов
         * @var string $alias Простаноство имен
         * * @var string $path Путь к классу
         * @return void
         */
        public static function register($alias, $path)
        {
            try{
                return self::$object->cl->register($alias, $path);
            } catch(SystemException $e) {
                echo $e->log();
            }   
        }
        
        /**
         * Доступ к приватным свойствам
         * @var string $param Имя свойства
         * @return object
         */
        public static function _get($param)
        {
            return self::$object->$param;
        }

        /**
         * Подключениe необходимых файлов и создание объектов
         */
        private function init()
        {    
            try {
                // Регистрируем классы, как уже подключенные  
                $this->cl->register('Ap\Ap', '', 0, 1);
                
                $this->cl->register('%Core%\PathsPreparer', '%Core%', 0, 1);
                $this->cl->register('%Core%\SystemException', '%Core%', 0, 1);
                            
                $this->cl->register('%Core%*', 'Core');
                $this->cl->register('%Core%\Routers*', '%Core%/Routers');
                $this->cl->register('%Core%\Base*', '%Core%/Base');
                
                $this->cl->register('%App%\Initializer', '%App%');
                
                $this->app = call_user_func($this->cl->call('%App%\Initializer'));
                
                $this->app->excute();
                
            } catch(SystemException $e) {
                echo $e->log();
            }
        }
        
        /**
         * Вызов контроллера
         * @var array $request массив с данными запроса
         */
        public static function ctrl()        
        {  
            $CtrlRouter = self::import('%Core%\Routers\CtrlRouter');
            $BController = self::import('%Core%\Base\BController', 'call');
            
            try {
                self::$object->request = $CtrlRouter(func_get_args())->result;
                return $BController(self::$object->request);
            } catch(SystemException $e) {
                $e->log();
            }
        }
        
         /**
         * Вызов модели
         * @var array $request массив с данными запроса
         */
        public static function mdl()        
        {  
            $MdlRouter= self::import('%Core%\Routers\MdlRouter');
            $BModel = self::import('%Core%\Base\BModel', 'call');
            
            try {
                $a = $MdlRouter(func_get_args())->result;
                return $BModel($a);
            } catch(SystemException $e) {
                $e->log();
            }
        }
    }
    