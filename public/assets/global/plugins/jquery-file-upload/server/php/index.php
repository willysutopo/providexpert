<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

$options = array(
	'delete_type' => 'POST',
	'db_host' => 'localhost',
	'db_user' => 'root',
	'db_pass' => 'wil123',
	'db_name' => 'kejorapro',
	'db_table' => 'medias'
);

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
// $upload_handler = new UploadHandler();

class CustomUploadHandler extends UploadHandler 
{
	protected function initialize() 
	{
		$this->db = new mysqli(
			$this->options['db_host'],
			$this->options['db_user'],
			$this->options['db_pass'],
			$this->options['db_name']
		);
		parent::initialize();
		$this->db->close();
	}

	protected function handle_form_data($file, $index) {
		$file->title = @$_REQUEST['title'][$index];
		//$file->description = @$_REQUEST['description'][$index];
	}

	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) 
	{
		$file = parent::handle_file_upload(
			$uploaded_file, $name, $size, $type, $error, $index, $content_range
		);
		
		if (empty($file->error)) 
		{
			$current_datetime = date("Y-m-d H:i:s");
			
			$sql = 'INSERT INTO `'.$this->options['db_table'].'` (`caption`, `type`, `url`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?)';
			$query = $this->db->prepare($sql);
			$query->bind_param(
				'sssss',
				$file->title,
				$file->type,
				$file->name,
				$current_datetime,
				$current_datetime
			);
			$query->execute();
			$file->id = $this->db->insert_id;
		}
		
		return $file;
	}

	protected function set_additional_file_properties($file) 
	{
		parent::set_additional_file_properties($file);
		if ($_SERVER['REQUEST_METHOD'] === 'GET') 
		{
			$sql = 'SELECT `id`, `type`, `caption` FROM `'.$this->options['db_table'].'` WHERE `url`=?';
			$query = $this->db->prepare($sql);
			$query->bind_param('s', $file->name);
			$query->execute();
			$query->bind_result(
				$id,
				$type,
				$caption
			);
			while ($query->fetch()) 
			{
				$file->id = $id;
				$file->type = $type;
				$file->title = $caption;
			}
		}
	}

	public function delete($print_response = true) 
	{
		$response = parent::delete(false);
		foreach ($response as $name => $deleted) 
		{
			if ($deleted) 
			{
				$sql = 'DELETE FROM `'.$this->options['db_table'].'` WHERE `url`=?';
				$query = $this->db->prepare($sql);
				$query->bind_param('s', $name);
				$query->execute();
			}
		} 
		return $this->generate_response($response, $print_response);
	}

}

$upload_handler = new CustomUploadHandler($options);
