<?php

namespace Gignite\StateMachine;

class State {

	protected $name;

	protected $config;
	
	protected $can_change_to;

	protected $can_change_from;

	protected $start;

	protected $final;
	
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

	public function is_start()
	{
		return $this->start;
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

		if ( ! empty($this->can_change_to))
		{
			return in_array($state_name, $this->can_change_to); 
		}
		
		return TRUE;
	}

	public function can_change_from(State $state)
	{
		if ($state->is_final())
		{
			return FALSE;
		}

		if ( ! empty($this->can_change_from))
		{
			return in_array($state->name(), $this->can_change_from);
		}
		
		return TRUE;
	}

}