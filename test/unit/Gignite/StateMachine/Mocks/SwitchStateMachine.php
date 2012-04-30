<?php
/**
 * A switch state machine
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

class SwitchStateMachine extends \Gignite\StateMachine\StateMachine {
	
	public static function state_definitions()
	{
		return array(
			new State('off', array(
				'start' => TRUE,
				'from'  => array('on'),
			)),

			new State('on', array(
				'from' => array('off'),
			)),

			new State('destroyed', array(
				'final' => TRUE,
			)),
		);
	}

	public $state;

}