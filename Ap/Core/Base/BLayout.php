<?php
    namespace Ap\Core\Base;
    
    use Ap\Ap;
    
    abstract class BLayout
    {   
        /**
         * Отображение шаблона
         * Вызывает методы вида(кроме методов начинающиеся с _),
         * и пресылает возвращаемые ими занчения шаблону вида.
         * Так же обходит поля класса(кроме полей начинающиеся с _).
         * Если поле содержит объект, пересылает возвращаемый результат шаблону вида.
         * Если поле не содержит объекта, переслыает значение поля шаблону вида.
         * @var array $data Массив с параметрами шаблона
         * @var bool $escape Флаг экранирования данных от HTML символов
         */
        public function _render(array $data = array())
        {
            $darray = array();
            
            //Массив полей объекта
            $object_vars = get_object_vars($this);
            
            //Обработка методов класса
            foreach(get_class_methods($this) as $val) {
                
                //Отбрасываем методы начинающиеся с _
                if (substr($val, 0, 1) != '_') {
                    //Если в объекте определены поля
                    //с именами, идентичные именам методам класса $class_name
                    if (array_key_exists($val, $object_vars)) {
                        $func = $this->$val;
                        $darray[$val] = (gettype($func) == 'object')? $func() : $func;
                        unset($object_vars[$val]); //Удаляем поле из массива
                    } else{
                            $darray[$val] = $this->$val();
                    } 
                }
            }
            
            //Если в массиве полей, что-то осталось
            if (!empty($object_vars)) {
                foreach($object_vars as $key => $val) {
                    if (substr($key, 0, 1) != '_') {
                        $func = $this->$key;
                        $darray[$key] = (gettype($func) == 'object')? $func() : $func;
                    }
                }
            }
            
            if(!empty($data))
                $darray = array_merge($darray, $data);
            
            return $this->_useTplFile($darray);
        }
        
        abstract public function _useTplFile($data);
    }
