<?php
/**
 * An interface to a state machine consumer
 * 
 * @package     StateMachine
 * @category    StateMachine
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 */
namespace Gignite\StateMachine;

interface Stateful {
	
	public function state(StateMachine $state_machine = NULL);

}