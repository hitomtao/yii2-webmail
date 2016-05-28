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
	private $_structure;
	public $active;
	public $server = 'mail.google.com';
	public $port = 993;
	public $flag = '/imap/ssl/validate-cert';
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
	 * Set the active folder
	 *
	 * @param $folder
	 */
	public function setActive($folder)
	{
		$this->active = $folder;
	}

	private function generateStructure()
	{
		$mailboxes = $this->_connection->getMailboxes();
		$this->_structure = new Structure();
		foreach ($mailboxes as $mailbox) {
			$this->_structure->addFolder($mailbox->getName(), $mailbox->count());
		}
	}

	/**
	 * Returns folder structure of current mailbox
	 *
	 * @return Structure
	 * @throws Exception
	 */
	public function getMenuItems()
	{
		if(!isset($this->_connection)) throw new Exception('You need to authenticate a user before requesting structure');
		return $this->_structure->getStructure();
	}

	/**
	 * Get the default folder for this mail.
	 * Usually Inbox
	 *
	 * @return string
	 */
	public function getDefaultFolder()
	{
		if(!isset($this->_structure)) $this->generateStructure();
		return $this->getMenuItems()[0]['name'];
	}

	/**
	 * Generate HTML folder menu
	 *
	 * @return string
	 */
	public function generateMenu()
	{
		$start = '<ul class="webmail_menu">';
		$end = '</ul>';
		$content = '';
		foreach($this->getMenuItems() as $item)
		{
			$content .= '<li>'.$item['name'].' ('.$item['mails'].')</li>';
		}
		return $start.$content.$end;
	}
}