<?php
    namespace Ap\Apps\Test\Libs\Tpl;
    
    use Ap\Ap;
    
/**
 * Подключение и обработка tpl файлов
 * @author Мокряк Владислав
 */
    class Template
    {    
        /**
         * @var string Содержание шаблона
         */
        private $content;
        
        /**
         * @var object Экзэмпляр генератора ссылок
         */
        private $link;
        
        /**
         * @var string Путь к каталогу с шаблонами
         */     
        private $tplpath;
        
        /**
         * @var array Параметры конфигурации
         */
        private $config;
        
        /**
         * @var string Файл шаблона
         */     
        private $file;
        
        public function __construct($file)
        {   
            $this->config = Ap::confData();
            $this->link = call_user_func(Ap::import('%AppLibs%\LinkBuilder', 'init'));
            
            $this->file = $this->config['paths']['tplpath'] . 
                str_replace(array('/', '\\'), _DS, strtolower($file));
                    
            $this->parseTpl(FileParser::fileGetContents($this->file));        
        }
        
        /**
         * Создатель класса
         * @var string $file Путь к файлу шаблона
         */
        private function parseTpl($content) 
        {
            $SystemException = Ap::import('%Core%\SystemException');
            
            preg_match_all('/<!--\s?#([^#]+)#\s?-->(.*?)<!--\s?#\1_end#\s?-->/ius', $content, $arr);

            if(empty($arr[0]))
                throw $SystemException(15, $this->file, '_404');
                
            $this->content = array_combine($arr[1], $arr[2]);
        }
        
        /**
         * Экранирование данных
         * @var string $val Данные для экранирования
         */
        public function escape($val)
        {            
            return htmlspecialchars($val);
        }
        
        /**
         * Список загруженных классов
         */
        public static function trace()
        {
            foreach(FileParser::getFilesList() as $key => $val)
                @$res .= $key."\n";
 
            return $res;
        }
        
        /**
         * Получение частей шаблона
         * @var string $val Данные для экранирования
         */
        public function __call($name, $value)
        {
            if(!empty($value[0]))
                foreach($value[0] as $key => $val)
                    $$key = $val;

            $_link = $this->link;
            
            ob_start();
            echo eval('?>'. $this->content[$name] .'<?php ');
            $content = ob_get_contents();
            ob_end_clean();
            
            return $content;
        }
    }
    