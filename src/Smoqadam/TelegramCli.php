<?php

namespace Smoqadam;

class TelegramCli
{
	private $f;
	protected $_errorMessage = null;
	protected $_errorCode = null;

	/**
	 * TelegramCli constructor.
	 * @param $socket
	 */
	public function __construct($socket)
	{
		$this->f = stream_socket_client($socket);
		if ($this->f === false) {
			throw new \Exception('Connect to remote socket failed.');
		}
	}

	public function __call($command, $arguments)
	{
		return $this->run($command, $arguments);
	}

	private function run($command, $args = array())
	{
		if (!empty($args)) {
			$args = implode(' ', $args);
		} else {
			$args = '';
		}

		$command = $command . ' ' . $args;
		fwrite($this->f, str_replace("\n", '\n', $command) . PHP_EOL);
		$answer = fgets($this->f); //"ANSWER $bytes" or false if an error occurred
		if (is_string($answer)) {
			if (substr($answer, 0, 7) === 'ANSWER ') {
				$bytes = ((int) substr($answer, 7)) + 1; //+1 because the json-return seems to miss one byte

				if ($bytes > 0) {
					$bytesRead = 0;
					$jsonString = '';
					//Run fread() till we have all the bytes we want
					//(as fread() can only read a maximum of 8192 bytes from a read-buffered stream at once)
					do {
						$jsonString .= fread($this->f, $bytes - $bytesRead);
						$bytesRead = strlen($jsonString);
					} while ($bytesRead < $bytes);
					$json = json_decode($jsonString, true);
					if (!isset($json->error)) {
						//Reset error-message and error-code
						$this->_errorMessage = null;
						$this->_errorCode = null;
						//For "status_online" and "status_offline"
						if (isset($json->result) && $json->result === 'SUCCESS') {
							return true;
						}
						//Return json-array
						return $json;
					} else {
						$this->_errorMessage = $json->error;
						$this->_errorCode = $json->error_code;
					}
				}
			}
		}
		return false;
	}

	public function getErrorMessage()
	{
		return $this->_errorMessage;
	}

	public function getErrorCode()
	{
		return $this->_errorCode;
	}
}

