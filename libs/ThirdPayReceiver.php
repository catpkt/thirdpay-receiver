<?php

namespace CatPKT\ThirdPayReceiver;

use CatPKT\Encryptor\Encryptor;
use CatPKT\HttpServer\TServer;
use Symfony\Component\HttpFoundation\{  Request,  Response  };

////////////////////////////////////////////////////////////////

trait TThirdPayReceiver
{
	use TServer;

	/**
	 * Method getRoutes
	 *
	 * @abstract
	 *
	 * @access protected
	 *
	 * @return array
	 */
	protected function getRoutes():array
	{
		return [
			'POST:/callback'=> 'AsyncCallback',
		];
	}

	/**
	 * Method actionAsyncCallback
	 *
	 * @access protected
	 *
	 * @param  Request $request
	 *
	 * @return Response
	 */
	protected function actionAsyncCallback( Request$request ):Response
	{
		$encryptor= $this->getEncryptor();

		$payload= $encryptor->decrypt( $request->getContent() );

		$result= $request->asyncCallback(...[
			$payload['code'],
			$payload['tradeId'],
			$payload['time'],
			$payload['payerId'],
			$payload['extensions'],
		]);

		return new Response( $encryptor->encryptor( $result->getPayload() ), $result->getStatus() );
	}

	/**
	 * Method asyncCallback
	 *
	 * @abstract
	 *
	 * @access protected
	 *
	 * @param  string $code
	 * @param  string $thirdTradeId
	 * @param  int $time
	 * @param  int $payerId
	 * @param  array $extensions
	 *
	 * @return CallbackResult
	 */
	abstract protected function asyncCallback( string$code, string$thirdTradeId, int$time, int$payerId=null, array$extensions=[] ):CallbackResult;

}
