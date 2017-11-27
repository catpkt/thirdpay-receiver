<?php

namespace CatPKT\ThirdPayReceiver;

use CatPKT\Encryptor\{  Encryptor,  DecryptException  };
use CatPKT\HttpServer\TServer;
use FenzHTTP\{  HTTP,  Response as ClientResponse  };
use Symfony\Component\HttpFoundation\{  Request,  Response  };

////////////////////////////////////////////////////////////////

trait TThirdPayReceiver
{
	use TServer;

	/**
	 * Method createPay
	 *
	 * @access public
	 *
	 * @param  string $code
	 * @param  int $amount
	 * @param  string $comment
	 * @param  string $thirdId
	 * @param  array $extensions
	 *
	 * @return array
	 */
	public function createPay( string$code, int$amount, string$comment, string$thirdId=null, array$extensions=[] ):array
	{
		$encryptor= $this->getEncryptor();

		$response= HTTP::url( $this->getApiUri() )->post(
			$encryptor->encrypt( [
				'code'=> $code,
				'amount'=> $amount,
				'comment'=> $comment,
				'thirdId'=> $thirdId,
				'extensions'=> $extensions,
			] )
		);

		$this->checkPayCreatingResponse( $response );

		return $encryptor->decrypt( $response->body );
	}

	/**
	 * Method getApiUri
	 *
	 * @abstract
	 *
	 * @access protected
	 *
	 * @return string
	 */
	abstract protected function getApiUri():string;

	/**
	 * Method checkPayCreatingResponse
	 *
	 * @access private
	 *
	 * @param  ClientResponse $response
	 *
	 * @return viod
	 */
	private function checkPayCreatingResponse( ClientResponse$response )
	{
		if( $response->status>=200 && $response->status<300 )
		{
			return;
		}
		if( $response->status==418 )
		{
			throw new CreatePayException( 418 );
		}
		if( $response->status>=400 && $response->status<500 )
		{
			try{
				$exception= $this->getEncryptor()->decrypt( $response->body );
			}
			catch( DecryptException$e )
			{
				$exception= $response->body;
			}

			throw new CreatePayException( 400, $exception );
		}else{
			throw new CreatePayException( 507 );
		}
	}

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

		$result= $this->asyncCallback(...[
			$payload['code'],
			$payload['tradeId'],
			$payload['time'],
			$payload['amount'],
			$payload['payerId'],
			$payload['extensions'],
		]);

		return new Response( $encryptor->encrypt( $result->getPayload() ), $result->getStatus() );
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
	 * @param  int $amount
	 * @param  int $payerId
	 * @param  array $extensions
	 *
	 * @return CallbackResult
	 */
	abstract protected function asyncCallback( string$code, string$thirdTradeId, int$time, int$amount, int$payerId=null, array$extensions=[] ):CallbackResult;

}
