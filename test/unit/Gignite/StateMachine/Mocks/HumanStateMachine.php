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
	}

	public $state;

	public function eat_flesh()
	{
		$this->assert('undead');
	}

	public function walk()
	{
		$this->assert(array('born', 'undead'));
	}

	public function share_worldly_experience()
	{
		$this->assertNot('unborn');
	}

	public function beat_heart()
	{
		$this->assertNot(array('dead', 'undead', 'super_dead'));
	}

}