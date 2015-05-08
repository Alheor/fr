<?php
    namespace Ap\Apps\Test\Libs\MenuBuilder;
    
    use Ap\Ap;
    
/**
 * Вертикальное меню
 * @author Мокряк Владислав
 */
 class MyMenu extends BMenu 
    {
        public function getMenuData()
        {
            return array(
                array(
                    'title' => 'FOOD',
                    'href'  => 'index/index1',
                ),
                array(
                    'title' => 'DRINKS',
                    'href'  => 'index/index2',
                    'margin'=> '20'
                ),
                array(
                    'title' => 'PROJECTS',
                    'href'  => '#',
                    'attr'  => 'class="nb"',
                    'child' => array(
                        array(
                            'title' => 'Metropol',
                            'href'  => 'Metropol'
                        ),
                        array(
                            'title' => 'AMARSI',
                            'href'  => 'AMARSI'
                        ),
                        array(
                            'title' => 'FOOD HUNTER MAGAZINE 1',
                            'href'  => 'FOOD HUNTER MAGAZINE 1'
                        ),
                        array(
                            'title' => 'FOOD HUNTER MAGAZINE 2',
                            'href'  => 'FOOD HUNTER MAGAZINE 2'
                        ),
                    )
                ),
                array(
                    'title' => 'COOKBOOKS',
                    'href'  => 'index/index3',
                ),
                array(
                    'title' => 'PUBLISHED',
                    'href'  => 'PUBLISHED',
                ),
                array(
                    'title' => 'INTERIOR',
                    'href'  => 'INTERIOR',
                    'child' => array(
                        array(
                            'title' => 'NOVIKOV',
                            'href' => 'NOVIKOV'
                        ),
                        array(
                            'title' => 'VERRASSEND',
                            'href' => 'VERRASSEND'
                        ),
                        array(
                            'title' => 'METROPOL',
                            'href' => 'METROPOL'
                        ),
                        array(
                            'title' => 'PURE HOME COLLECTIONS',
                            'href' => 'PURE HOME COLLECTIONS'
                        )
                    )
                )
            );
        }
    }
    