<?php
    namespace Ap\Apps\Test\Libs;
    
    use Ap\Ap;
    
/**
 * Ксс конструктор
 * @author Мокряк Владислав
 */
    class CssBuilder
    {
        /**
         * @var object Хранилище объекта
         */
        private static $object = null;
        
        /**
         * @var string Каталог к ксс файлам
         */
        private $csspath;
        
        /**
         * @var string Файл с CSS кэшем
         */
        private $cachefile;
        
        /**
         * @var string CSS кэш
         */
        private $cache;
        
        /**
         * @var array Параметры конфигурации
         */
        private $config;
        
        function __construct()
        {
            $this->config = Ap::confData();
            
            $this->csspath = $this->config['paths']['csspath'];
            $this->cachefile = $this->config['paths']['csspath'] . 
                $this->config['general']['csscache'];
        }

        /**
         * Обновление ксс кода в кэше
         */        
        public static function cssUpdate()
        {   
            if (self::$object === null)
                self::$object = new self();
            
            self::$object->cache = '';
            $file = null;
                
            if (func_num_args()) {
                
                $args = func_get_args();
                
                $file = array_shift($args);
                $path = '';
                
                while(($part = array_shift($args)) != null)
                    $path .= $part . _DS;
                
                $file = strtolower(self::$object->csspath . $path . $file);
                
                $SystemException = Ap::import('%Core%\SystemException');
                
                if( !is_file($file) || !is_readable($file))
                    throw $SystemException(1, $file, '_500');
                    
                self::$object->contentParser($file);
                
                //Имя ксс файла от корня стиля
                $csstitle = preg_replace('/^(.+)+/u', '', $file);
                
                $content = file_get_contents(self::$object->cachefile);
                
                //Проверка наличия ксс кода в кэше
                $cssexist = preg_match(
                    '/\/*start '. str_replace('\\', '\\\\', $csstitle) .'*\//u',
                    $content
                );

                if ($cssexist) {
                    self::$object->cache = preg_replace(
                        '/start '. str_replace('\\', '\\\\', $csstitle) .'*\/(.*)\/*end '. str_replace('\\', '\\\\', __NAMESPACE__ . $csstitle) ."*\/\\n?/iu",
                        str_replace('\\', '\\\\', self::$object->cache), 
                        $content
                    );
                    
                } else
                    self::$object->cache = $content . "\n". self::$object->cache;

                self::$object->saveCach();
                
            } else {
                self::$object->readFiles(self::$object->csspath);
                self::$object->readDir(self::$object->csspath);
                self::$object->saveCach();
            }
        }
        
        /**
         * Чтение каталога
         * @var string $path Путь каталога
         */ 
        private function readDir($path)
        { 
            $dir = opendir($path);
                
            while (false !== ($item = readdir($dir))) {    
                if (is_dir($path . $item) && ($item != '.') && ($item != '..')) {
                    $this->readFiles($path. $item . _DS);
                    $this->readDir($path . $item . _DS);
                }
            }
                
            closedir($dir);
        }
        
        /**
         * Чтение файла
         * @var string $path Путь файла
         */
        private function readFiles($path)
        {
            $dir = opendir($path);
            $arr = array();
            
            while (false !== ($item = readdir($dir))) {    
                if (is_file($path . $item) && 
                    substr($item, -3) == 'css' && 
                    $item !== 'cache.css'
                )
                    $arr[] = $path . $item;
            }
             
            closedir($dir);
            
            natsort($arr);
            
            foreach($arr as $val)
                $this->contentParser($val);
        }

        /**
         * Обработчик контента
         * @var string $cssfile Путь к файлу
         */        
        private function contentParser($cssfile)
        {    
            $content = file_get_contents($cssfile);
            
            //Замена несколькоих пробельных симоволов подряд на один пробел
            $content = preg_replace('/(\s){2,}/u', ' ', $content);
                               
            //Удаление пробельныйх символов до и после символов { } :
            $content = preg_replace('/\s*({|}|:)\s*/u', '$1', $content);
                
            //Удаление ; перед }
            $content = preg_replace('/;+(})/u', '$1', $content);
            
            //Имя ксс файла от корня стиля
            preg_match('/css(.*)/u', $cssfile, $csstitle);
            
            //Обрамляем код именем и адресом файла
            $this->cache .= '/*start '. $csstitle[1] .
                '*/'. $content .'/*end '. $csstitle[1] ."*/\n";
        }
 
        /**
         * Сохранение в файл
         */ 
        private function saveCach()
        {            
            $SystemException = Ap::import('%Core%\SystemException');
            
            if (!file_put_contents($this->cachefile, $this->cache))
                throw $SystemException(9, $this->cachefile, '_500');
        }
    }
    