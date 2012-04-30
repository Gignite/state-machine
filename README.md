# StateMachine

Define a finite state machine like so:

``` php
<?php
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

}
?>
```