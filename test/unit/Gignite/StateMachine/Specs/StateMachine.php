<?php
namespace Gignite\StateMachine\Specs;
/**
 * Specification for Gignite\StateMachine\StateMachine
 * 
 * @package     StateMachine
 * @category    StateMachine
 * @category    Specs
 * @category    Test
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 * @group       statemachine
 */
class StateMachine extends \PHPUnit_Framework_TestCase {

	protected function stateMachine($startState)
	{
		$stateMachine = new \Gignite\StateMachine\Mocks\HumanStateMachine;
		$stateMachine->state = $startState;
		return $stateMachine;
	}

	public function provideStateMachines()
	{
		$states = array('unborn', 'born', 'dead', 'undead');
		
		$stateMachines = array(
			array($this->stateMachine(NULL), 'unborn'),
		);

		foreach ($states as $_state)
		{
			$stateMachine = $this->stateMachine($_state);
			$stateMachine->state = $_state;
			$stateMachines[] = array($stateMachine, $_state);
		}

		return $stateMachines;
	}

	/**
	 * @dataProvider  provideStateMachines
	 */
	public function testItShouldGetCurrent($stateMachine, $expected)
	{
		$currentState = $stateMachine->current();
		$this->assertInstanceOf('Gignite\StateMachine\State', $currentState);
		$this->assertSame($expected, $currentState->name());
	}

	public function provideValidStateMachineUpdateCalls()
	{
		return array(
			array('unborn', 'born'),
			array('born',   'dead'),
			array('dead',   'unborn'),
			array('dead',   'undead'),
			array('dead',   'super_dead'),
			array('undead', 'super_dead'),
		);
	}

	/**
	 * @dataProvider  provideValidStateMachineUpdateCalls
	 */
	public function testItShouldUpdateToValidStates($startState, $updateState)
	{
		$stateMachine = $this->stateMachine($startState);
		$stateMachine->update($updateState);
		$this->assertSame($updateState, $stateMachine->current()->name());
	}

	public function provideExceptionalStateMachineUpdateCalls()
	{
		return array(
			// Invalid transitions
			array('unborn',     'unborn',     'InvalidStateTransition'),
			array('unborn',     'dead',       'InvalidStateTransition'),
			array('unborn',     'undead',     'InvalidStateTransition'),
			array('unborn',     'super_dead', 'InvalidStateTransition'),
			array('born',       'unborn',     'InvalidStateTransition'),
			array('born',       'born',       'InvalidStateTransition'),
			array('born',       'undead',     'InvalidStateTransition'),
			array('born',       'super_dead', 'InvalidStateTransition'),
			array('dead',       'born',       'InvalidStateTransition'),
			array('dead',       'dead',       'InvalidStateTransition'),
			array('undead',     'unborn',     'InvalidStateTransition'),
			array('undead',     'born',       'InvalidStateTransition'),
			array('undead',     'dead',       'InvalidStateTransition'),
			array('undead',     'undead',     'InvalidStateTransition'),
			array('super_dead', 'unborn',     'InvalidStateTransition'),
			array('super_dead', 'born',       'InvalidStateTransition'),
			array('super_dead', 'dead',       'InvalidStateTransition'),
			array('super_dead', 'undead',     'InvalidStateTransition'),
			array('super_dead', 'super_dead', 'InvalidStateTransition'),
			
			// Invalid states
			array('invalid', 'super_dead', 'InvalidState'),
			array('unborn',  'invalid',    'InvalidState'),
			array('invalid', 'invalid',    'InvalidState'),
		);
	}

	/**
	 * @dataProvider  provideExceptionalStateMachineUpdateCalls
	 */
	public function testItShouldThrowExceptionWhenUpdatingToInvalidStates(
		$startState,
		$updateState,
		$expectedException)
	{
		$this->setExpectedException(
			'Gignite\StateMachine\\'.$expectedException.'Exception');
		$stateMachine = $this->stateMachine($startState);
		$stateMachine->update($updateState);
	}

	public function testItShouldUseFirstStateAsDefault()
	{
		$stateMachine = new \Gignite\StateMachine\Mocks\NoDefaultStateMachine;
		$this->assertSame('default', $stateMachine->current()->name());
	}

	public function provideValidSwitchStateChanges()
	{
		return array(
			array('on',  'off'),
			array('off', 'on'),
			array('off', 'destroyed'),
			array('on',  'destroyed'),
		);
	}

	/**
	 * @dataProvider  provideValidSwitchStateChanges
	 */
	public function testItShouldChangeStateWhenCanChangeFromValid(
		$startState,
		$updateState)
	{
		$stateMachine = new \Gignite\StateMachine\Mocks\SwitchStateMachine;
		$stateMachine->state = $startState;
		$stateMachine->update($updateState);
		$this->assertSame($updateState, $stateMachine->current()->name());
	}

	public function provideInvalidSwitchStateChanges()
	{
		return array(
			array('destroyed', 'off'),
			array('destroyed', 'on'),
			array('destroyed', 'destroyed'),
			array('off', 'off'),
			array('on',  'on'),
		);
	}

	/**
	 * @dataProvider       provideInvalidSwitchStateChanges
	 * @expectedException  Gignite\StateMachine\InvalidStateTransitionException
	 */
	public function testItShouldThrowExceptionWhenCannotChangeFromValid(
		$startState,
		$updateState)
	{
		$stateMachine = new \Gignite\StateMachine\Mocks\SwitchStateMachine;
		$stateMachine->state = $startState;
		$stateMachine->update($updateState);
	}

	public function provideValidMethodCalls()
	{
		return array(
			array('undead',     'eat_flesh'),
			array('born',       'walk'),
			array('undead',     'walk'),
			array('born',       'share_worldly_experience'),
			array('dead',       'share_worldly_experience'),
			array('undead',     'share_worldly_experience'),
			array('super_dead', 'share_worldly_experience'),
			array('unborn',     'beat_heart'),
			array('born',       'beat_heart'),
		);
	}

	/**
	 * @dataProvider  provideValidMethodCalls
	 */
	public function testItShouldNotThrowExceptionDuringValidStates(
		$startState,
		$method)
	{
		$stateMachine = $this->stateMachine($startState);
		$this->assertNull($stateMachine->{$method}());
	}

	public function provideInvalidMethodCalls()
	{
		return array(
			array('unborn',  'eat_flesh'),
			array('unborn',  'walk'),
			array('unborn',  'share_worldly_experience'),
			array('dead',    'beat_heart'),
		);
	}

	/**
	 * @dataProvider       provideInvalidMethodCalls
	 * @expectedException  Gignite\StateMachine\InvalidStateException
	 */
	public function testItShouldThrowExceptionDuringInvalidStates(
		$startState,
		$method)
	{
		$stateMachine = $this->stateMachine($startState);
		$this->assertNull($stateMachine->{$method}());
	}

}