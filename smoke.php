<?php
namespace Gignite\StateMachine\Mocks;

define('ROOT_PATH', __DIR__.'/');

spl_autoload_register(function ($class)
{
    $class = ltrim($class, '\\');

    $fileName  = '';
    $namespace = '';

    if ($lastNsPos = strripos($class, '\\'))
    {
        $namespace = substr($class, 0, $lastNsPos);
        $class     = substr($class, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

    $fileGlobs = array(
        ROOT_PATH.'classes/'.$fileName,
        ROOT_PATH.'test/unit/'.$fileName,
    );

    // Load from module classes first and foremost
    foreach ($fileGlobs as $_glob)
    {
        $filePaths = glob($_glob);

        if (count($filePaths) > 0)
        {
            require current($filePaths);
            break;
        }
    }
});

use Gignite\StateMachine\State;

class HumanStateMachine extends \Gignite\StateMachine\StateMachine {

    public static function state_definitions()
    {
        //                               -> Undead -> 
        //                              /            \
        // ->  Unborn  ->  Born  ->  Dead     ->      -> Super Dead
        // \                          /
        //  -          <-            -
        return array(
            new State('unborn', array(
                'start' => TRUE,
                'to'    => array('born'),
            )),

            new State('born', array(
                'to' => array('dead'),
            )),

            new State('dead', array(
                'to' => array('unborn', 'undead', 'super_dead'),
            )),

            new State('undead', array(
                'to' => array('super_dead'),
            )),

            new State('super_dead', array(
                'final' => TRUE,
            )),
        );
    }

}

$humanStateMachine = new HumanStateMachine;
$humanStateMachine->update('born');
$humanStateMachine->assert(array('super_dead', 'born'));