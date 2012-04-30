<?php
/**
 * A human state machine
 * 
 * @package     StateMachine
 * @category    StateMachine
 * @category    Mocks
 * @category    Test
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 */
namespace Gignite\StateMachine\Mocks;

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
		return array(
			new State('unborn', array('start' => TRUE)),

			new State('born', array(
				'from' => array('unborn'),
			)),

			new State('dead', array(
				'from' => array('born'),
			)),

			new State('undead', array(
				'from' => array('dead'),
			)),

			new State('super_dead', array(
				'from'  => array('dead', 'undead'),
				'final' => TRUE,
			)),
		);
	}

	public $state;



}