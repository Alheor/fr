<?php   
    namespace Ap\Core;
    
    use Ap\Ap;
    
/**
 * Загрузчик классов
 * @author Мокряк Владислав
 */    
    class ClassLoader
    {  
        /**
         * @var object Хранилище объекта
         */
        private static $object = null;
        
        /**
         * @var array Массив с именами класов
         */
        private $registry = array();
                
        /**
         * @var string syspath Корень приложения
         */
        private $syspath;
        
        /**
         * @var string ns Базовое пространство имен
         */
        private $ns;
        
        /**
         * @var string app_ns Пространство имен приложения
         */
        private $app_ns;
        
        public function __construct($path, $ns, $app_ns)
        {     
            $this->ns = $ns;
            $this->app_ns = $app_ns;
            $this->syspath = $path;
            
            $this->autoload($this->registry);
        }
        
        /**
         * Преобразование пути в правильный
         * @var string $name_space Пространтво имен
         */
        private function nsclean($name_space)
        {
            $name_space =  preg_replace(
                '/[^a-z0-9_\\\\\/]*/iu',
                '',
                $name_space
            );
            
            $name_space = trim( trim($name_space, '\\'), '/' );
                
            $name_space = str_replace(array('/', '\\\\'), '\\', $name_space);

            return $name_space;
        }
        
        /**
         * Преобразование пути в правильный
         * @var string $name_space Пространтво имен
         */
        private function pathclean($path)
        {
            if (!empty($path)) {
                $path =  preg_replace(
                    '/[^a-z0-9:_\/\\\]*/iu',
                    '',
                    $path
                );
                
                $path = str_replace(array('/', '\\', '\\\\'), _DS, $path);
                
                $path = trim($path, _DS) . _DS;
            }
            
            return $path;
        }
        
        /**
         * Региситрация классов
         * @var string | array $alias Пространтво имен
         * @var int $flag Флаг подключенности файла
         */
        public function register($alias, $path, $checkns = 0, $included = 0)
        {     
            $type = (substr($alias, -1) == '*')? true : false; //true - каталог, false - файл
                  
            if(strpos($alias, '%') !== null)
                $alias =  Ap::_get('pp')->getNsFromAlias($alias);
                 
            $this->name_space = '\\'. $this->nsclean($alias);
            
            if(strpos($path, '%') !== false)
                $path = $this->pathclean(Ap::_get('pp')->getPathFromAlias($path));
            else
                $path = $this->syspath . $this->pathclean($path);

            if (!$type) { // файл
                if($checkns && isset($this->registry[$this->name_space]))
                    throw new SystemException(17, $this->name_space);
                    
                $path .= substr($this->name_space, strrpos($this->name_space, _DS) + 1) .'.php';

                if(!file_exists($path) || !is_readable($path))
                    throw new SystemException(1, $path);
   
                //Первый элемент путь к файлу, второй - флаг подлючения файла
                $this->registry[$this->name_space] = array($path, (int)$included);    
            } else { // каталог
                if(!is_dir($path) || !is_readable($path))
                    throw new SystemException(18, $path);
                        
                $this->readFiles($path, $checkns, $included);
            }
        }
        
        /**
         * Чтение файлов
         * @var string $path Путь к файлу или каталоку
         */
        private function readFiles($path, $checkns, $included)
        {
            $dir = opendir($path);
            while (false !== ($item = readdir($dir))) {  
                if ($item != '.' && $item != '..') {
                     
                    $p = $path . $item;
                   
                    if (is_file($p) && is_readable($p) && substr($p, -3) == 'php') {
                        
                        $name_space = '\\'. $this->nsclean(
                            $this->name_space . 
                            _DS . 
                            substr($item, 0, strpos($item, '.'))
                        );

                        if($checkns && isset($this->registry[$name_space]))
                            throw new SystemException(17, $name_space);
                        
                        $this->registry[$name_space] = array($p, (int)$included);
                    }
                }
            }
            closedir($dir);
        }
        
        /**
         * Автозагрузчик классов
         */
        private function autoload(&$registry)
        {
            spl_autoload_register(function ($class) use(&$registry) {
            
                foreach($registry as $key => $val) {   
                    if(strpos($key, $class) !== false && $val[1] == 0) {
                        
                        $registry[$key][1] = 1;
                        require $val[0];
                    }
                }
            });
        }
        
        /**
         * Список загруженных классов
         */
        public function trace()
        {
            foreach($this->registry as $key => $val) {
                $ress = array(0);
                $ns = substr($key, 0, strrpos($key, _DS) + 1); 
                $res[$ns][substr($key, strrpos($key, _DS) + 1)] = $val;
            }
            
            $str = '';
            foreach($res as $key => $val) {
                
                $str .= "\n".$key ."\n";
                foreach($val as $key => $val1)
                    $str .= '    '. $key .': '. $val1[0] ." - ". $val1[1] ."\n";
            }
            
            return $str;
        }
        
        /**
         * Зарегистрирован ли класс
         * @var string $name_space Пространтво имен
         */
        public function isReg($name_space)
        {
            $name_space = $this->nsclean($name_space);
            
            foreach($this->registry as $key => $val)
                if(strpos($key, $name_space) !== false)
                    return true;
                    
            return false;
        }
        
        /**
         * Подключен ли файл класса
         * @var string $name_space Пространтво имен
         */
        public function isReq($name_space)
        {
            $name_space = $this->nsclean($name_space);
            
            foreach($this->registry as $key => $val)
                if(strpos($key, $name_space) !== false)
                    return $this->registry[$key][1] == 1? true : false;
            
            return false;
        }
        
        /**
         * Возвращает полное имя класс с учетом пространства имен
         * @var string $path Пространтво имен
         */
        public function getFullClassName($path)
        {
            $path = $this->nsclean($path);
            
            $counter = 0;
            
            foreach ($this->registry as $key => $val)
                if (strpos($key, $path) !== false) {
                    $class_name = $key;
                    $counter++;
                }
            
            if ($counter == 0) // Класс не найден
                return null;
            else if ($counter > 1) // Ошибка: Найдено больше одного класса
                throw new SystemException(19, $path);
            else
                return $class_name;
        }
        
        /**
         * Вызов и создание экземпляра класса.
         * Метод производит поиск в массиве registry элемента, имя которого полностью
         * или частично совпадает с переданной искомой строкой path. Если элементов не найден,
         * метод возвращает null, если найдень больше одного элемента - это ошибка.
         * Либо загетистрировано больше одного класса с одинаковым именем, либо необходимо 
         * уточнить искомую строку(искомая строка содержится в пути несколькоих классов).
         * @var string $path путь класса в пространстве имен
         * @var string $method Имя статического метода
         * @return mixed
         */
        public function call($path, $method = null)
        {
             if(strpos($path, '%') !== null)
                $path =  Ap::_get('pp')->getNsFromAlias($path);
            
            $path = '\\'. $this->nsclean($path);

            $counter = 0;
            
            foreach ($this->registry as $key => $val)
                if ($key == $path) {
                    $class_name = $key;
                    $counter++;
                }
            
            if ($counter == 0) // Класс не найден
                throw new SystemException(2, $path);
            else if ($counter > 1) // Ошибка: Найдено больше одного класса
                throw new SystemException(19, $path);
            else { // Найден один класс
           
                return function() use ($class_name, $method) {
                    if(!class_exists($class_name))
                        throw new SystemException(2, $class_name);
                            
                    if ($method === null) {
                        $class = new \ReflectionClass($class_name);
                        
                        if ($class->getConstructor() == null)
                            return new $class_name;
                        else
                            return $class->newInstanceArgs(func_get_args());
                    } else {
                        $static = new \ReflectionMethod($class_name, $method);
                        return $static->invokeArgs(null, func_get_args());
                    } 
                };  
            } 
        }
    }
    