<?php
    namespace Ap\Core;
    
    use \Ap\Ap;
    
/**
 * Обработка системных исключений
 * @author    Мокряк Владислав
 */
     class SystemException extends \Exception
     {
        /**
         * @var array config Параметры конфигурации
         */
        private $config;
        
        /**
         * @var string ca Имя действия контроллера ошибок
         */
        private $ca;
        
       /**
        * @var array Коды и сообщения об ошибках
        */
        private $errors = array(
            0 => 'Что-то пошло не так (%)',
            1 => 'Файл "%" недоступен или не существует',
            2 => 'Класс "%" неопределен',
            3 => 'Метод "%" класса "%" не найден',
            4 => '--',
            5 => 'Не указан контроллер',
            6 => 'Не указана модель',
            7 => 'Не указано действие модели',
            8 => 'Не указано действие контроллера',
            9 => 'Не удалось выполнить операцию записи в файл "%"',
           10 => 'Механизм сессий отключен',
           11 => 'Не указано пространство имен',
           12 => 'Пространстов имен "%", заблокированно',
           13 => 'Не указано время жизни кук',
           14 => 'Не удалось определить время жизни кук',
           15 => 'Не найдено ни одной части шаблона в файле "%"',
           16 => 'Класс "%" не реализует "%" интерфейс',
           17 => 'Пространстов имен "%", уже существует',
           18 => '"%", не каталог',
           19 => 'Необходимо уточнить пространство имен "%"',
        );
        
        /**
         * Конструктор
         * @var integer $code код сообщения
         * @var string $mess сообщения
         * @var object $previous предыдущее исключение
         */
        public function __construct($code = 0, $mess = null, $ca = '_404', $previous = null )
        { 
            $this->prepareMess($code, $mess);
            
            parent::__construct($this->errors[$code], $code, $previous);
            
            $this->config = Ap::confData();
            
            $this->ca = $ca;
        }
        
        /**
         * Формирование сообщения
         * @var integer $code код сообщения
         * @var string $mess сообщения
         */
        private function prepareMess($code, $mess)
        {
            if(is_array($mess))
                while ( ($part = array_shift($mess)) != null ){
                    $this->errors[$code] = preg_replace('/%/u',
                        str_replace('\\', '\\\\', $part), $this->errors[$code], 1);
                }
            else
                $this->errors[$code] = preg_replace('/%/u',
                    str_replace('\\', '\\\\', $mess), $this->errors[$code]);
        }
        
        /**
         * Вывод ошибок
         * @return mixed
         */
        public function log()
        {
            if(Ap::_get('debug')) {
                exit("<pre>Ошибка #{$this->code}. {$this->message}.\n\n".
                    self::getTraceAsString().'</pre>');
            } else
            // Необходимо быть внимательным, т.к. при отсутствии контроллера $this->ca
            // может произайти зацикливание
                exit(Ap::ctrl(array('pageErrors', $this->ca, $this->message)));
        }
    }
    