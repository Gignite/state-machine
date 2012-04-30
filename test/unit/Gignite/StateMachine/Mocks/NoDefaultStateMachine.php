<?php
/**
 * A state machine with no default
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

class NoDefaultStateMachine extends \Gignite\StateMachine\StateMachine {
	
	public static function state_definitions()
	{
		return array(
			new State('default'),
			new State('other'),
		);
	}

}