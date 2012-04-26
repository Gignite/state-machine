<?php

namespace Gignite\StateMachine;

class State {

	protected $name;

	protected $config;
	
	public function __construct($name, array $config = array())
	{
		$this->name   = $name;

		if (isset($config['to']))
		{
			$this->can_change_to = $config['to'];
		}
		
		if (isset($config['from']))
		{
			$this->can_change_from = $config['from'];
		}
		
		if (isset($config['final']))
		{
			$this->final = (bool) $config['final'];
		}
		
		if (isset($config['start']))
		{
			$this->start = (bool) $config['start'];
		}
	}

	public function name()
	{
		return $this->name;
	}

	public function is_final()
	{
		return $this->final;
	}

	public function can_change_to(State $state)
	{
		$state_name = $state->name();

		if ($this->is_final())
		{
			return FALSE;
		}
		
		return ! empty($this->can_change_to)
			AND ! in_array($state_name, $this->can_change_to);
	}

	public function can_change_from(State $state)
	{
		if ($state->is_final())
		{
			return FALSE;
		}

		return ! empty($this->can_change_from)
			AND ! in_array($state->name(), $this->can_change_from));
	}

}