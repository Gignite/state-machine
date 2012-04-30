<?php
/**
 * A finite state machine
 * 
 * @package     StateMachine
 * @category    StateMachine
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 */
namespace Gignite\StateMachine;

abstract class StateMachine {

	protected $states;

	protected $state;

	protected $log = array();

	protected function states()
	{
		if ($this->states === NULL)
		{
			$this->states = static::state_definitions();
		}

		return $this->states;
	}

	protected function state($state)
	{
		foreach ($this->states() as $_state)
		{
			if ($_state->name() === $state)
			{
				return $_state;
			}
		}

		throw new InvalidStateException;
	}

	protected function valid_transition($current_state, $new_state)
	{
		return $current_state->can_change_to($new_state)
			AND $new_state->can_change_from($current_state);
	}

	protected function start_state()
	{
		$states = $this->states();

		foreach ($states as $_state)
		{
			if ($_state->is_start())
			{
				return $_state;
			}
		}

		return $states[0];
	}

	public function current()
	{
		if ($this->state === NULL)
		{
			$this->state = $this->start_state()->name();
		}

		return $this->state($this->state);
	}

	public function update($new_state)
	{
		$new_state     = $this->state($new_state);
		$current_state = $this->current();
		
		if ( ! $this->valid_transition($current_state, $new_state))
		{
			throw new InvalidStateTransitionException;
		}

		$this->state = $name = $new_state->name();
		$this->log[] = array(time(), $name);
	}

	protected function is($state)
	{
		if (is_array($state))
		{
			$current_state = $this->current()->name();

			foreach ($state as $_state)
			{
				if ($current_state === $_state)
				{
					return TRUE;
				}
			}

			return FALSE;
		}

		return $this->current() === $this->state($state);
	}

	public function assert($state)
	{
		if ( ! $this->is($state))
		{
			throw new InvalidStateException;
		}
	}

	public function assertNot($state)
	{
		if ($this->is($state))
		{
			throw new InvalidStateException;
		}
	}

	public function log()
	{
		return $this->log;
	}

}