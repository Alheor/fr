<?php
    namespace Ap\Apps\Test;
    
    use Ap\Ap;
    
/**
 * Инициализатор приложения
 * @author    Мокряк Владислав
 */
    class Initializer
    {
        /**
         * @var array Массив c конфигурацией
         */
        private $config;
        
        function __construct()
        {
            Ap::register('%App%\Config', '%App%');

            $this->config = call_user_func(
                Ap::import('%App%\Config'), 
                Ap::_get('app_ns')
            )->getConfData();
        }
        
        public function excute()
        {
            $this->libRegister($this->config['libs']);

            Ap::register('%AppLayouts%*', '%AppLayouts%');
            
            call_user_func(Ap::import('%AppLibs%\CssBuilder', 'cssUpdate'));
        }
        
        /**
         * Регистратор библиотек
         * @var string $name Имя свойства
         * @return void
         */
        private function libRegister($libarr)
        {
            foreach($libarr as $key => $val)
                Ap::register($key, $val);  
        }
        
        /**
         * Доступ к приватным свойствам
         * @var string $name Имя свойства
         * @return mixed
         */
        public function __get($name)
        {
            return $this->$name;
        }
    }
    