<?php

namespace Gignite\StateMachine;

interface Stateful {
	
	protected function state(StateMachine $state_machine = NULL);

}