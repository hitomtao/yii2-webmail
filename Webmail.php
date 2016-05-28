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

	public function __construct($username, $password)
	{
		/* TEMP START */
		$server = 'mail.ellera.no';
		$port = 993;
		$flag = '/imap/ssl/novalidate-cert';
		$parameters = [];
		/* TEMP END */

		$this->_server = new Server($server, $port, $flag, $parameters);
		$this->_connection = $this->_server->authenticate($username, $password);
	}

	public function structure()
	{
		$mailboxes = $this->_connection->getMailboxes();

		foreach ($mailboxes as $mailbox) {
			$this->_mailboxes[str_replace('.', '/', ucfirst($mailbox->getName()))] = $mailbox->count();
		}

		return $this->_mailboxes;
	}
}