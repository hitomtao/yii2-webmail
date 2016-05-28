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

	public function showMailBoxes()
	{
		$mailboxes = $this->_connection->getMailboxes();

		foreach ($mailboxes as $mailbox) {
			// $mailbox is instance of \Ddeboer\Imap\Mailbox
			printf('Mailbox %s has %s messages', $mailbox->getName(), $mailbox->count());
		}
	}
}