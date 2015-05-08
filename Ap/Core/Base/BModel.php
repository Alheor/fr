<?php
    namespace Ap\Core\Base;
    
    use Ap\Ap;
      
/**
 * Создатель экземпляра модели
 * @author Мокряк Владислав
 */
    class BModel
    {         
        /**
         * Инициализация класса
         * @var array $request данные запроса
         * @return mixed
         */
        static public function call(array $request)
        {
            $SystemException = Ap::import('%Core%\SystemException');
            
            $mname = '%AppMdls%\\'. $request['mdlns'];

            Ap::register($mname, '%AppMdls%/'. $request['path']);

            $mdl = call_user_func(Ap::import($mname));

            if (!method_exists($mdl, $request['action']))
                throw $SystemException(3, array($request['action'], $request['mdlns']), '_500');

            return $mdl->$request['action']($request['arg']);
        }
    }
    