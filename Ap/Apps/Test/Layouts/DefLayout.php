<?php
    namespace Ap\Apps\Test\Layouts;
    
    use Ap\Ap;
    use Ap\Core\Base\BLayout;

/**
 * Стандартный шаблон
 */
    class DefLayout extends BLayout
    {  
        /**
         * Определение файла шаблона для вида
         * @var array $data Данные для шаблона
         * @var bool $escape Флаг экранирования HTML символов
         */
        public function _useTplFile($data)
        {
            $tpl = Ap::import('%AppLibs%\Tpl\Template');
            return $tpl('index.tpl')->site($data);
        }
        
        /**
         * Блок заголовка страницы
         */
        public function header()
        {
            return 1;
        }
        
        /**
         * Блок левой колонки страницы
         */
        public function leftBody()
        {
            return call_user_func(Ap::import('%AppLibs%\MenuBuilder\MyMenu'));
        }
        
        /**
         * Блок тела страницы
         */
        public function centerBody()
        {
            return 3;
        }
        
        /**
         * Блок правой колонки страницы
         */
        public function rightBody()
        {
            return 4;
        }
        
        /**
         * Подвал страницы
         */
        public function footer()
        {
            return 5;
        }
    } 