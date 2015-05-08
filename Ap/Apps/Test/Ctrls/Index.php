<?php
    namespace Ap\Apps\Test\Ctrls;
    
    use Ap\Ap;
    
    class Index
    {   
        public function index1($data)
        {  
            print_r( Ap::mdl('s23s\Test', 'index', array(1, 2, 3)));
            
            Ap::ctrl('Wd\Test', 'index1');
            
            $DefLayout = call_user_func(Ap::import('%AppLayouts%\DefLayout'));
            
            $DefLayout->centerBody = function() {
                $Template = Ap::import('%AppLibs%\Tpl\Template');
                return $Template('body.tpl')->body();
            };
            
            echo $DefLayout->_render(array('title' => 'body'));
        }
    }