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
		$this->_structure[ucfirst(strtolower(end($folders)))] = $count;
	}

	public function generate()
	{
		return $this->_structure;
	}
}