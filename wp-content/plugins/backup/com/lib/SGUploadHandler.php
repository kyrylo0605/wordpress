<?php

namespace BackupGuard\Upload;

class Handler
{
	private $data = null;
	private $fileName = "";
	private $tmpFileName = "";

	public function __construct($data)
	{
		$this->data = $data;
		$this->import();
	}

	private function import()
	{
		$this->fileName = $this->data['files']['name'][0];
		$this->tmpFileName = $this->data['files']['tmp_name'][0];

		$dirPath = $this->getDestinationDirPath();
		$file = $dirPath.$this->fileName;

		$data = file_get_contents($this->tmpFileName);
		file_put_contents($file, $data, FILE_APPEND);
	}

	private function getDestinationDirPath()
	{
		return SG_BACKUP_DIRECTORY;
	}

	private function getDestinationDirUrl()
	{
		return SG_BACKUP_DIRECTORY_URL;
	}
}
