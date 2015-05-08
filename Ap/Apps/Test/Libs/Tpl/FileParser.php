<?php
    namespace Ap\Apps\Test\Libs\Tpl;
    
    use Ap\Ap;
    
/**
 * Подключение и хранение контента из шаблонов
 * @author Мокряк Владислав
 */
    class FileParser
    {    
        /**
         * @var object Хранилище объекта
         */
        private static $object = null;
        
        /**
         * @var array реестр загруженных файлов
         */
        private $fileregistry = array();
        
        private function __construct(){}
        
        /**
         * Получение содержимого из файла
         * @var string $file Путь к файлу шаблона
         */
        public static function fileGetContents($file) 
        {
            if (self::$object === null)
                self::$object = new self;
            
            if (!isset(self::$object->fileregistry[$file])) {
                
                $SystemException = Ap::import('%Core%\SystemException');

                if (!is_file($file) || !is_readable($file))
                    throw $SystemException(1, $file, '_404');
                    
                $content = file_get_contents($file);
                
                self::$object->fileregistry[$file] = $content;
                
                return $content;
            } else
                 return self::$object->fileregistry[$file];
        }
        
        
        /**
         * Доступ к массиву с файлами шаблонов
         * @return array
         */
        public static function getFilesList()
        {
            return self::$object->fileregistry;
        }
    }
    