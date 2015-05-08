<?php
    namespace Ap\Core\Base;
    
    use Ap\Ap;
    
/**
 * Конфигуратор системы
 * @author    Мокряк Владислав
 */
    abstract class BConfig
    {    
        /**
         * @var string syspath Каталог системы
         */
        public $syspath;
        
        /**
         * @var int debug Флаг дебага
         */
        public $debug;
        
        /**
         * @var array Массив конфигурации
         */
        private $params = array();
        
        public function __construct($app_ns)
        {
            $this->syspath  = Ap::_get('syspath');
            $this->debug    = Ap::_get('debug');
            $this->app_ns   = $app_ns;
            
            $this->params   = $this->prepareParams();
        }
        
        public function getConfData(){
            return $this->params;
        }
        
        abstract protected function prepareParams();
    }
    