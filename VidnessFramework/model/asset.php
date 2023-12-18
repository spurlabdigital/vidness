<?php

class Asset extends Model {
	private $id;
	private $filename;
	private $filetype;
	private $filesize;
	private $path;
	private $thumbnail;
	private $createdOn;
	private $updatedOn;

	public function load() {
		$assets = $this->fetch("select * from asset where id= :id ",
			array(
				':id'=>$this->id
			));
		if(sizeof($assets)>0){
			$record = $assets[sizeof($assets)-1];
			$this->setId($record['id'])
				->setFilename($record['filename'])
				->setFiletype($record['filetype'])
				->setPath($record['path'])
				->setThumbnail($record['thumbnail'])
				->setFilesize($record['filesize']);
		}
		else
			$this->setId(0);
		return $this;
	}

	public function save() {
		
		if($this->id > 0 ){

		}
		else{
			$data = array( ':filename' =>  $this->filename,
				':filetype' =>  $this->filetype,
				':filesize' =>   $this->filesize,
				':path' => $this->path,
				':thumbnail' => $this->thumbnail
				);

			$this->execute("insert into asset  (filename , filetype , path  , thumbnail , filesize) values (:filename , :filetype , :path , :thumbnail  ,:filesize );" , $data);
			$assets = $this->fetch("select id from asset");
			$asset = $assets[sizeof($assets)-1];
			$this->id = intval($asset['id']);
			return $this;
		}
	}

	public function delete() {
		$this->execute("delete from asset where id = :id",array(':id'=> $this->id));
		return $this;
	}


	public function setId($id) { $this->id = $id; return $this; }
	public function setFilename($filename) { $this->filename = $filename; return $this; }
	public function setFiletype($filetype) { $this->filetype = $filetype; return $this; }
	public function setPath($path) { $this->path = $path; return $this; }
	public function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail; return $this; }
	public function setFilesize($filesize) { $this->filesize = $filesize; return $this; }
	public function setCreatedOn($createdOn) { $this->createdOn = $createdOn; return $this; }
	public function setUpdatedOn($updatedOn) { $this->updatedOn = $updatedOn; return $this; }

	public function getId() { return $this->id; }
	public function getFiletype() { return $this->filetype; }
	public function getFilename() { return $this->filename; }
	public function getFilesize() { return $this->filesize; }
	public function getPath() { return $this->path; }
	public function getThumbnail() { return $this->thumbnail; }
	public function getCreatedOn() { return $this->createdOn; }
	public function getUpdatedOn() { return $this->updatedOn; }

}

 

?>