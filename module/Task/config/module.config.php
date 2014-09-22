<?php
return array(
        'controllers' => array (
                'invokables' => array (
                        'Task\Controller\Task' => 'Task\Controller\TaskController'
                )
        ),
		
        // The following section is new and should be added to your file
        'router' => array(
                'routes' => array(
                        'task' => array(
                                'type'    => 'segment',
                                'options' => array(
                                        'route'    => '/task[/][:action][/:id]',
                                        'constraints' => array(
                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'id'     => '[0-9]+',
                                        ),
                                        'defaults' => array(
                                                'controller' => 'Task\Controller\Task',
                                                'action'     => 'index',
                                        ),
                                ),
                        ),
                ),
        ),
        
        'view_manager' => array (
                'template_path_stack' => array (
                        'task' => __DIR__ . '/../view'
                )
        )
);