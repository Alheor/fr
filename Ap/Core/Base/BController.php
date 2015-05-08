<?php
    namespace Ap\Core\Base;
    
    use Ap\Ap;
      
/**
 * Создатель экземпляра контроллера
 * @author Мокряк Владислав
 */
    class BController
    {         
        /**
         * Инициализация класса
         * @var array $request данные запроса
         * @return mixed
         */
        static public function call(array $request)
        {
            $SystemException = Ap::import('%Core%\SystemException');
            
            $ctrl_name = '%AppCtrls%\\'. $request['ctrlname'];

            Ap::register($ctrl_name, '%AppCtrls%\\'. $request['path']);

            $ctrl = call_user_func(Ap::import($ctrl_name));

            if (!method_exists($ctrl, $request['action']))
                throw $SystemException(3, array($request['action'], $ctrl_name), '_404');

            return $ctrl->$request['action']($request['arg']);
        }
    }
    