<?php   
    namespace Ap\Core\Routers;
    
    use Ap\Ap;
    use Ap\Core\Base\BRouter;
    
/**
 * Роутер контроллеров
 * @author Мокряк Владислав
 */    
    class CtrlRouter extends BRouter 
    {  
        /**
         * Обработка запроса
         * @var request array параметры запроса
         */
        public function parsePath(array $request)
        {       
            $ctrlpath = $this->config['paths']['ctrlpath'];
            $cname = '';
            $cpath = '';
            
            if (empty($request)) {
                $parts = explode('/', $this->request);
                
                if (empty($parts[count($parts)-1]))
                    unset($parts[count($parts)-1]);
                    
                while (!empty($parts)) {
                    $part = array_shift($parts);
                        
                    $ctrlpath .= ucfirst($part);
                    
                    $cname .= ucfirst($part) .'\\';
                    
                    if (is_dir($ctrlpath) && !empty($parts)) {
                        strtolower($ctrlpath);
                        $ctrlpath .= _DS;
                        $cpath .= ucfirst($part) .'\\';
                        continue;
                    }
         
                    $action = array_shift($parts);
                    
                    // Заменяем любые слеши на правильные     
                    $cpath = str_replace(array('/', '\\'), _DS, $cpath);
                
                    //Имя контроллера - строка после последнего слеша 
                    if(strrpos($cpath, _DS) !== false)     
                        $cpath = substr($cpath, 0, strrpos($cpath, _DS) + 1);
                    
                    $this->result = array(
                        'path' => $cpath,
                        'ctrlname' => trim($cname, '\\'),
                        'action' => $action === null? 
                            $this->config['general']['baseaction'] : $action,
                        'arg' => $this->parseUrlParams($parts)       
                    );
                        
                    break;
                } 
            } else {
                $SystemException = Ap::import('%Core%\SystemException');
                
                if(!isset($request[0]) || empty($request[0]))
                    throw $SystemException(5, null, '_500');
               
                if(!isset($request[1]) || empty($request[1]))
                    throw $SystemException(8, null, '_500');  
                
                // Заменяем любые слеши на правильные     
                $cpath = ucfirst( str_replace(array('/', '\\'), _DS, $request[0]) );
                
                //Имя контроллера - строка после последнего слеша 
                if(strrpos($cpath, _DS) !== false)     
                    $cpath = substr($cpath, 0, strrpos($cpath, _DS) + 1);
                    
                $this->result = array(
                    'path'      => $cpath,
                    'ctrlname'  => ucfirst($request[0]),
                      'action'  => $request[1],
                         'arg'  => (!isset($request[2]) || empty($request[2]))? 
                                        array() : $request[2]      
                );
            }
        }
        
        /**
         * Обработка параментов запроса
         * Функция добавляет в $this->params остаток данных из запроса, 
         * после того, как из него были удалены данные о контроллере и действии.
         * @var array $params параметры из запроса
         */
        private function parseUrlParams($params)
        {   
            //Удаление последнего пустого элемента 
            if (end($params) === '')
                array_pop($params);
    
            return array_merge($params, $this->params);
        }
    }
    