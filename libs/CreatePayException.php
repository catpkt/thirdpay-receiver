<?php

namespace CatPKT\ThirdPayReceiver;

////////////////////////////////////////////////////////////////

class CreatePayException extends \Exception
{

	/**
	 * Var payload
	 *
	 * @access protected
	 *
	 * @var    mixed
	 */
	protected $payload;

	/**
	 * Var status
	 *
	 * @access protected
	 *
	 * @var    int
	 */
	protected $status;

	/**
	 * Constructor
	 *
	 * @access public
	 *
	 * @param  int $status
	 * @param  mixed $payload
	 */
	public function __construct( $status, $payload=null )
	{
		$this->status= $status;
		$this->payload= $payload;
	}

	/**
	 * Method getPayload
	 *
	 * @access public
	 *
	 * @return mixed
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	/**
	 * Method getStatus
	 *
	 * @access public
	 *
	 * @return int
	 */
	public function getStatus():int
	{
		return $this->status;
	}

}
