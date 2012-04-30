<?php

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
			if ($state->name() === $state)
			{
				return $state;
			}
		}

		throw new UnknownStateException;
	}

	protected function valid_transition($current_state, $new_state)
	{
		return $current_state->can_change_to($new_state)
			OR $new_state->can_change_from($current_state);
	}

	public function current()
	{
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

	public function assert($state)
	{
		if ($this->state($state) !== $state)
		{
			throw new InvalidStateException;
		}
	}

	public function assertNot($state)
	{
		if ($this->state($state) === $state)
		{
			throw new InvalidStateException;
		}
	}

	public function log()
	{
		return $this->log;
	}

}