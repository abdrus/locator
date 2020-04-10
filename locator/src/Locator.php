<?php

namespace Locator;

use GuzzleHttp\Client;

class Locator {

	private $phoneBook = [
		"353" => [
			'rostelecom' => [
				'start' => 2310000,
				'end' => 2369999
			],
			'megafon' => [
				'start' => 2309000,
				'end' => 2309999
			]
		]
	];

	public function getByIp($ipAddress){
		$client = new Client([
			'base_uri' => 'http://free.ipwhois.io/json/'
		]);

		$response = $client->request('GET',$ipAddress);

		return json_decode($response->getBody()->getContents());
	}

	public function getByPhone($phone){
		$code = substr($phone, 0, 3);
		$number = substr($phone, 3, strlen($phone));

		if (array_key_exists($code, $this->phoneBook)){
			$region = $this->phoneBook[$code];
			
			foreach ($region as $operator => $ranges) {
				if($ranges['start'] <= $number && $number <= $ranges['end']){
					return $operator;
				}
			}

			return false;
		}
	}

}