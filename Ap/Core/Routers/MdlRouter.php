<?php   
    namespace Ap\Core\Routers;
    
    use Ap\Ap;
    use Ap\Core\Base\BRouter;
        
/**
 * Роутер модели
 * @author Мокряк Владислав
 */       
    class MdlRouter extends BRouter
    {
        /**
         * Обработка запроса
         * @var array $request параметры запроса
         */
        public function parsePath(array $request)
        {
            $SystemException = Ap::import('%Core%\SystemException');
            
            if(!isset($request[0]) || empty($request[0]))
                throw $SystemException(6, null, '_500');
               
            if(!isset($request[1]) || empty($request[1]))
                throw $SystemException(7, null, '_500');  
            
            // Заменяем любые слеши на правильные     
            $mdlpath =  ucfirst( str_replace(array('/', '\\'), _DS, $request[0]) );
                
            // Путь до модели - строка до последнего слеша 
            if(strrpos($mdlpath, _DS) !== false)     
                $mdlpath = substr($mdlpath, 0, strrpos($mdlpath, _DS) + 1);

            $this->result = array(
                'path' => $mdlpath,
                'mdlns' => $request[0],
                'action' => $request[1],
                'arg' => (!isset($request[2]) || empty($request[2]))? 
                            array() : $request[2]       
            );
        }
    }
