<?php namespace RnTorm\LaravelMargn;

use Guzzle\Http\Client;
/**

	TODO:
		- CRON

		- get amount, reference_nr, bankpayment_nr
		- get compgroup from reference_nr

		- 1) Ref found
		- 	1) if adv
		- 		calc transaction amount (take away vat)
		- 	2) else
		- 		transaction amount stays the same
		- 	Create transaction to compgroup, Create payment invoice for compgroup
		- 2) Ref not found
		- 	Add amount, bankpayment_nr to invoicepayment - admin can set receiver to link payment with compensationgroup

		- 	id, amount, sender, reference_nr

**/

class Margn {

	// private $apiKey;
	private $baseUrl;
	public $variables;

	public function __construct () {
		$configuration = \Config::get('laravel-margn');
		$this->variables['token'] = $configuration['API_KEY'];
		$this->baseUrl = $configuration['BASE_URL'];
	}

	public function getAccountEntries()
	{
		// $url = self::BASE_URL;
		$get_response = $this->httpGet();
		return $get_response;
	}

	public function getAccounts()
	{
		$requestUrl = '/accounts';
		$get_response = $this->httpGet($requestUrl);
		return $get_response;
	}

	public function getArticles()
	{
		$requestUrl = '/articles';
		$get_response = self::httpGet($requestUrl);
		return $get_response;
	}

	public function getCustomers()
	{
		$requestUrl = '/customers';
		$get_response = self::httpGet($requestUrl);
		return $get_response;
	}

	public function postCustomers()
	{
		// NO API REQUEST
	}

	// Requires id
	public static function getInvoice($id = null)
	{
		$requestUrl = '/invoices';
		if (empty($id))
		{
			throw new HttpException(HttpException::BAD_REQUEST, 'id not specified');
		}
		else
		{
			$requestUrl .= '/' . $id;
		}
		$get_response = $this->httpGet($requestUrl);
		return $get_response;
	}


	public static function postInvoice($data, $additional_params = array('documentType' => 'DOCUMENT_SELL'))
	{
		$url = self::BASE_URL . '/invoices.json';
		if (empty($data))
		{
			throw new HttpException(HttpException::BAD_REQUEST, 'Invoice post data empty');
		}
		foreach ($data as $key => $value)
		{
			self::$variables[$key] = $value;
		}
		$post_response = self::httpPost($url, self::$variables, $data);
		return $post_response;
	}

	public static function getPayments()
	{
		$url = self::BASE_URL . '/payments';
		$get_response = self::httpGet($url, self::$variables);
		return $get_response;
	}

	public static function getProjects()
	{
		$url = self::BASE_URL . '/projects';
		$get_response = self::httpGet($url, self::$variables);
		return $get_response;
	}

	public static function TransactionEntries()
	{
		$url = self::BASE_URL . '/transaction_entries';
		$get_response = self::httpGet($url, self::$variables);
		return $get_response;
	}

	protected function httpGet($requestUrl = null)
	{
		$client = new Client();
		$request = $client->createRequest(
			'GET',
			$this->baseUrl . $requestUrl,
			array(
				'config' => array(
					'curl' => array(
						CURLOPT_CAINFO => base_path() . '\cacert.pem',
						CURLOPT_SSLVERSION => 3
					)
				)
			)
		);
		$query = $request->getQuery();
		if ($this->variables)
		{
			foreach($this->variables as $key => $val)
			{
				$query->set($key, $val);
			}
		}
		$response = $client->send($request);

		try
		{
			$response = $client->send($request);
			if ($response->getStatusCode() == 200)
			{
				return $response->json();
			}
			else
			{
				throw new HttpException(HttpException::BAD_REQUEST);
			}
		}
		catch (\Exception $e)
		{
			throw new HttpException(HttpException::BAD_REQUEST, $e->getMessage());
		}
	}

	protected function httpPost($requestUrl, $requestData)
	{
		$client = new Client();

		$request = $client->post(
			$this->baseUrl . $requestUrl . '.json',
			array(),
			array()
		);

		$request->setHeader('Content-Type', 'application/json');
		$request->setHeader('Accept', 'application/json');

		$query = $request->getQuery();
		$query->set('token', $this->variables['token']);

		if (!empty($this->variables))
		{
			$request->setBody(json_encode($requestData));
		}

		try
		{
			$response = $client->send($request);
			if ($response->getStatusCode() == 200)
			{
				return $response->json();
			}
			else
			{
				throw new HttpException(HttpException::BAD_REQUEST);
			}
		}
		catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	protected function httpPut($requestUrl, $requestData)
	{
		$client = new Client();

		$request = $client->put(
			$this->baseUrl . $requestUrl . '.json',
			array(),
			array()
		);

		$request->setHeader('Content-Type', 'application/json');
		$request->setHeader('Accept', 'application/json');

		$query = $request->getQuery();
		$query->set('token', $this->variables['token']);

		if (!empty($this->variables))
		{
			$request->setBody(json_encode($requestData));
		}

		try
		{
			$response = $client->send($request);
			if ($response->getStatusCode() == 200)
			{
				return $response->json();
			}
			else
			{
				throw new HttpException(HttpException::BAD_REQUEST);
			}
		}
		catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}
}