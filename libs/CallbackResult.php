<?php

namespace CatPKT\ThirdPayReceiver;

use CatPKT\ThirdPayReceiver\CallbackResult;

////////////////////////////////////////////////////////////////

class CallbackResult
{

	/**
	 * Var status
	 *
	 * @access protected
	 *
	 * @var    int
	 */
	protected $status;

	/**
	 * Var payload
	 *
	 * @access protected
	 *
	 * @var    array
	 */
	protected $payload;

	/**
	 * Constructor
	 *
	 * @access private
	 *
	 * @param  int $status
	 * @param  mixed $payload
	 */
	private function __construct( int$status, $payload )
	{
		$this->status= $status;
		$this->payload= is_array( $payload )? $payload : [ 'message'=>"$payload", ];
	}

	/**
	 * Method success
	 *
	 * @static
	 *
	 * @access public
	 *
	 * @return self
	 */
	public static function success():self
	{
		return new self( 200, 'OK' );
	}

	/**
	 * Method repeated
	 *
	 * @static
	 *
	 * @access public
	 *
	 * @return self
	 */
	public static function repeated():self
	{
		return new self( 200, 'Repeated' );
	}

	/**
	 * Method failed
	 *
	 * @static
	 *
	 * @access public
	 *
	 * @param  string $message
	 * @param  array $extensions
	 *
	 * @return self
	 */
	public static function failed( string$message, array$extensions=[] ):self
	{
		$extensions['message']= $message;

		return new self( 500, $extensions );
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

	/**
	 * Method getPayload
	 *
	 * @access public
	 *
	 * @return array
	 */
	public function getPayload():array
	{
		return $this->payload;
	}

}
