<?php
/**
 * @author 	Jorgen Ellingsen
 * @package jellingsen/yii2-webmail
 * @version 0.1
 */

namespace jellingsen\webmail;

use Ddeboer\Imap\Server;
use jellingsen\webmail\classes\Structure;
use yii\base\InvalidConfigException;
use yii\base\Exception;

class Webmail
{
	private $_server;
	private $_connection;
	private $_mailboxes = [];
	public $server;
	public $port = 993;
	public $flag = '/imap/ssl/novalidate-cert';
	public $parameters = [];

	/**
	 * Checks if config is set correctly
	 *
	 * @since 0.1
	 * @throws InvalidConfigException
	 */
	public function __construct()
	{
		if(!is_string($this->server))
		{
			throw new InvalidConfigException('"' . get_class($this) . '::server" should be a string, "' . gettype($this->server) . '" given.');
		}
		elseif(!isset($this->server) || $this->server == '')
		{
			throw new InvalidConfigException('"' . get_class($this) . '::server" must be set.');
		}
	}

	/**
	 * Authenticates a user
	 *
	 * @param $username
	 * @param $password
	 */
	public function authenticate($username, $password)
	{
		if(!isset($this->_server)) $this->_server = new Server($this->server, $this->port, $this->flag, $this->parameters);
		if(!isset($this->_connection)) $this->_connection = $this->_server->authenticate($username, $password);
	}

	/**
	 * Returns folder structure of current mailbox
	 *
	 * @return Structure
	 * @throws Exception
	 */
	public function structure()
	{
		if(!isset($this->_connection)) throw new Exception('You need to authenticate a user before requesting structure');
		$mailboxes = $this->_connection->getMailboxes();
		$structure = new Structure();
		foreach ($mailboxes as $mailbox) {
			$structure->addFolder($mailbox->getName(), $mailbox->count());
		}

		return $structure->generate();
	}
}