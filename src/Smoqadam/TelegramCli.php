<?php

namespace Smoqadam;

class TelegramCli
{


    private $f;


    /**
     * TelegramCli constructor.
     * @param $socket
     */
    public function __construct($socket)
    {
        $this->f = stream_socket_client($socket);
        if ($this->f === false) {
            throw new Exception('Connect to remote socket failed.');
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

        $command = str_replace("\n", '', $command);
        $command = $command . ' ' . $args;

        fwrite($this->f, $command . PHP_EOL);
        $fg = fgets($this->f);

        if ($fg) {
            if (strpos($fg, 'ANSWER') !== false) {
                $bytes = intval(str_replace('ANSWER ', '', $fg));
                $bytes_read = 0;
                $jsonString = '';
                while ($bytes_read < $bytes) {
                    $jsonString .= fread($this->f, $bytes - $bytes_read);
                    $bytes_read = strlen($jsonString);
                }
                $result = json_decode($jsonString, true);

                if (isset($result['error'])) {
                    throw new \Exception($result['error'], $result['error_code']);
                }

                return $result;
            }

        } else {
            throw new \Exception('Something went wrong. ' . $fg);
        }
    }
}

