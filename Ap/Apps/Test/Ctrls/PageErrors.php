<?php
    namespace Ap\Apps\Test\Ctrls;
    
    use Ap\Ap;
    
/**
 * Сообщения об ошибках
 * @author Мокряк Владислав
 */    
    class PageErrors 
    {   
        public function _403($data)
        {   
            echo 'Ошибка 403: В доступе отказано<br />';
        }
        
        public function _404($data)
        {   
            echo 'Ошибка 404: Нет такой страницы<br />';
        }
        
        public function _500($data)
        {   
            echo 'Ошибка 500: Внутренняя ошибка сервера<br />';
        }
    }