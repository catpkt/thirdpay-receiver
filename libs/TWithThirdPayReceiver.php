<?php

namespace CatPKT\ThirdPayReceiver;

////////////////////////////////////////////////////////////////

trait TThirdPayReceiver
{

	/**
	 * Var thirdPayReceiver
	 *
	 * @access private
	 *
	 * @var    ThirdPayReceiver
	 */
	private $thirdPayReceiver;

	/**
	 * Method getThirdPayReceiver
	 *
	 * @access protected
	 *
	 * @return ThirdPayReceiver
	 */
	final protected function getThirdPayReceiver():ThirdPayReceiver
	{
		return $this->thirdPayReceiver??($this->thirdPayReceiver= $this->createThirdPayReceiver());
	}

	/**
	 * Method createThirdPayReceiver
	 *
	 * @abstract
	 *
	 * @access protected
	 *
	 * @return ThirdPayReceiver
	 */
	abstract protected function createThirdPayReceiver():ThirdPayReceiver;

}
