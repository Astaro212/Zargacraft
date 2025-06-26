<?php
class Rcon
{
    private $host;
    private $port;
    private $password;
    private $timeout;
    private $socket;

    private $authorized = false;
    public $lastResponse = '';

    const PACKET_AUTHORIZE = 5;
    const PACKET_COMMAND = 6;
    const SERVERDATA_AUTH = 3;
    const SERVERDATA_AUTH_RESPONSE = 2;
    const SERVERDATA_EXECOMMAND = 2;
    const SERVERDATA_RESPONSE_VALUE = 0;


    public function __construct($host, $port, $password, $timeout)
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->timeout = $timeout;
    }
    /**
     * Summary of getResponce
     * @return string
     */
    public function getResponce()
    {
        return $this->lastResponse;
    }

    /**
     * @return boolean
     */
    public function connect()
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->socket) {
            $this->lastResponse = $errstr;
            return false;
        }

        stream_set_timeout($this->socket, 3, 0);
        return $this->authorize();
    }


    /**
     * Disconnect
     * @return void
     */

    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
    /**
     * Is Connected&Auth?
     * @return boolean
     */
    public function isConnected()
    {
        return $this->authorized;
    }
    /**
     * 
     * @param string $command
     * 
     * @return boolean | mixed
     */
    public function sendCommand($command)
    {
        if (!$this->isConnected()) {
            return false;
        }

        $this->writePacket(self::PACKET_COMMAND, self::SERVERDATA_EXECOMMAND, $command);

        $response_packet = $this->readPacket();

        if ($response_packet['id'] == self::PACKET_COMMAND && $response_packet['type'] == self::SERVERDATA_RESPONSE_VALUE) {
            $this->lastResponse = $response_packet['body'];
            return $response_packet['body'];
        }
        return false;
    }

    /**
     * Summary of authorize
     * @return boolean
     */
    private function authorize()
    {
        $this->writePacket(self::PACKET_AUTHORIZE, self::SERVERDATA_AUTH, $this->password);
        $response_packet = $this->readPacket();
        if ($response_packet['type'] == self::SERVERDATA_AUTH_RESPONSE && $response_packet['id'] == self::PACKET_AUTHORIZE) {
            $this->authorized = true;
            return true;
        }
        $this->disconnect();
        return false;
    }

    /**
		Size			32-bit little-endian Signed Integer	 	Varies, see below.
		ID				32-bit little-endian Signed Integer		Varies, see below.
		Type	        32-bit little-endian Signed Integer		Varies, see below.
		Body		    Null-terminated ASCII String			Varies, see below.
		Empty String    Null-terminated ASCII String			0x00
     */
    private function writePacket($packetId, $packetType, $packetBody)
    {
        //create packet
        $packet = pack('VV', $packetId, $packetType);
        $packet = $packet . $packetBody . "\x00";
        $packet = $packet . "\x00";

        $packet_size = strlen($packet);

        $packet = pack('V', $packet_size) . $packet;
        fwrite($this->socket, $packet, strlen($packet));
    }

    /**
     * @return array
     */
    private function readPacket()
    {
        $size_data = fread($this->socket, 4);
        $size_pack = unpack('V1size', $size_data);

        $size = $size_pack['size'];

        // if size is > 4096, the response will be in multiple packets.
        // this needs to be address. get more info about multi-packet responses
        // from the RCON protocol specification at
        // https://developer.valvesoftware.com/wiki/Source_RCON_Protocol
        // currently, this script does not support multi-packet responses.
        $packet_data = fread($this->socket, $size);
        $packet_pack = unpack('V1id/V1type/a*body', $packet_data);

        return $packet_pack;
    }
}

?>
<!--
<form method="POST" action="">
    <input type="text" name="host" autocomplete="on">
    <input type="number" name="port" autocomplete="on">
    <input type="text" name="password" autocomplete="on">
    <button type="submit">GO CONNECT</button>
</form>
-->

<?php
/*
if(isset($_POST)){
$rcon = new Rcon($_POST['host'], $_POST['port'],$_POST['password'], 3);
if ($rcon->connect()) {
    echo $rcon->isConnected();
    $rcon->sendCommand("p give Astaro 1000");
    echo "Success";
} else {
    echo "No connection:".$rcon->lastResponse;

}
}
*/ ?>