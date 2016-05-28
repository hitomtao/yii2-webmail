<?php
/**
 * @author 	Jorgen Ellingsen
 * @package jellingsen/yii2-webmail
 * @version 0.1
 */

namespace jellingsen\webmail;

use Ddeboer\Imap\Server;

class Webmail
{
	private $_server;
	private $_connection;
	private $_mailboxes = [];
	public $server = 'mail.ellera.no';
	public $port = 993;
	public $flag = '/imap/ssl/novalidate-cert';
	public $parameters = [];

	public function __construct($username, $password)
	{
		$this->_server = new Server($this->server, $this->port, $this->flag, $this->parameters);
		$this->_connection = $this->_server->authenticate($username, $password);
	}

	public function structure()
	{
		$mailboxes = $this->_connection->getMailboxes();

		foreach ($mailboxes as $mailbox) {
			$this->_mailboxes[ucfirst(strtolower($mailbox->getName()))] = $mailbox->count();
		}

		return $this->_mailboxes;
	}
}