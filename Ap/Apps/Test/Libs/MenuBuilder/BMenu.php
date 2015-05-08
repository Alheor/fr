<?php
    namespace Ap\Apps\Test\Libs\MenuBuilder;
    
    use Ap\Ap;
    
/**
 * Конструктор меню
 * @author Мокряк Владислав
 */
    abstract class BMenu
    {
        /**
         * @var array Меню
         */
        private $menu;
        
        /**
         * @var obj Экземпляр шаблонизатора
         */
        private $tpl;
        
        /**
         * @var object Экзэмпляр генератора ссылок
         */
        public $link;
        
        /**
         * @var array Информация о текущем запросе
         */
        private $request;
            
        public function __construct()
        {
            $this->tpl = call_user_func(Ap::import('%AppLibs%\Tpl\Template'), 'menu.tpl'); 
            $this->link = call_user_func(Ap::import('%AppLibs%\LinkBuilder', 'init'));
            
            $this->CtrlRouter = Ap::import('%Core%\Routers\CtrlRouter');
            $this->request = Ap::_get('request');
        }
        
        /**
         * Построение меню
         * @var array $arr Массив в меню
         * @return string
         */
        public function build($arr)
        {            
            $menuli = '';
            foreach($arr as $val) {  
                
                $tmphref = $val['href'];
                $val['href'] = $this->link->host($val['href']);
                
                $menuli .= $this->tpl->menuli(
                    array_merge(
                        $val,
                        array('curflag' => $this->isCurPage($tmphref)),
                        array('child' => (isset($val['child']))? $this->build($val['child']) : '')
                    )
                );
            }
            
            return $this->tpl->menuul(array(
                'li' => $menuli
            ));
        }
        
        /**
         * Определение текущей станицы, для выделения эл. меню
         * @var string $href Массив в меню
         * @return bool
         */
        public function isCurPage($href)
        {
            $arr = explode('/',$href);
            
            if(
                (isset($arr[0]) && !empty($arr[0])) &&
                (isset($arr[1]) && !empty($arr[1]))
            ) {
                $CtrlRouter = $this->CtrlRouter;
                $result = $CtrlRouter($arr)->result;
                
                if($result['ctrlname'] == $this->request['ctrlname'] && 
                    $result['action'] == $this->request['action'])
                    return true;
                else
                    return false;
            } else
                return false;
        }
        
        public function __toString(){
            return $this->build($this->getMenuData());
        }
        
        abstract public function getMenuData();
    }