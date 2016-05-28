<?php
/**
 * @author 	Jorgen Ellingsen
 * @package jellingsen/yii2-webmail
 * @version 0.1
 */

namespace jellingsen\webmail\classes;


class Structure
{
	private $_structure = [];
	public function addFolder($folder, $count)
	{
		$folders = explode('.', $folder);
		$this->_structure[] = ['name' => ucfirst(strtolower(end($folders))), 'mails' => $count];
	}

	public function getStructure()
	{
		return $this->_structure;
	}
}