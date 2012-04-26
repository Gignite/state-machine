<?php

namespace Gignite\StateMachine;

abstract class StateMachine {

	protected $states;

	protected $log;

	protected $state;

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

	public function current()
	{
		return $this->state($this->state);
	}

	public function log()
	{
		return $this->log;
	}

	protected function valid_transition()
	{
		return $current_state->can_change_to($new_state)
			OR $new_state->can_change_from($current_state);
	}

	public function update($new_state)
	{
		$new_state     = $this->state($new_state);
		$current_state = $this->current();
		
		if ( ! $this->valid_transition())
		{
			throw new InvalidStateChangeException;
		}

		$this->state = $new_state->name();
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

}