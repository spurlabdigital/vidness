<?php

class Location extends Model{
	private $id;
    private $userId;

    private $categoryId;
    private $category;

	private $description;
	private $longitude;
	private $latitude;
	private $data;
	private $link;
	private $createdOn;
	private $updatedOn;
    private $approvedOn;

    private $videoType;
    private $subTitle;

    private $orderId; // required when retrieving channels


    public function load() {
        $locations = $this->fetch("select 
                locations.*, 
                categories.name as category_name, 
                categories.color as category_color 
            from locations 
                join categories on locations.category_id = categories.id 
            where locations.id = :id", array(':id' => $this->id));
        if(sizeof($locations)>0){
            $record = $locations[sizeof($locations)-1];
            $this->setId($record['id'])
                ->setUserId($record['user_id'])
                ->setCategoryId($record['category_id'])
                ->setVideoType($record['videotype'])
                ->setDescription($record['description'])
                ->setSubTitle($record['subtitle'])
                ->setLatitude($record['latitude'])
                ->setLongitude($record['longitude'])
                ->setData($record['data'])
                ->setLink($record['link'])
                ->setCreatedOn($record['created_on']);

            $category = new Category();
            $category->setId($record['category_id'])
                ->setName($record['category_name'])
                ->setColor($record['category_color']);

            $this->setCategory($category);
        } else {
            $this->setId(0);
        }
        return $this;
    }

	public static function find($status = '') {
        if ($status == 'approved') {
            $append = 'and approved_on is not null';
        } else if ($status == 'unapproved') {
            $append = 'and approved_on is null';
        }
		// perform search on each of these values here
		$model = new Model();
		$sql = 'select 
                locations.*, 
                categories.name as category_name, 
                categories.color as category_color 
            from locations 
                join categories on locations.category_id = categories.id 
            where 1 ' . $append . '
            order by id desc';

        $order = $model->fetch($sql);
		return $order;
	}

    public function getChannelsForLocation() {
        $sql = 'select * from channel_locations where location_id = :id';
        $results = $this->fetch($sql, array(':id' => $this->id));

        $returnArray = array();
        foreach ($results as $result) {
            $returnArray[] = $result['channel_id'];
        }

        return $returnArray;
    }

    public function save() {
        if ($this->getId() > 0) {
            $data = array(
                ':id' => $this->id,
                ':description' => $this->description,
                ':latitude' => $this->latitude,
                ':longitude' =>  $this->longitude,
                ':category' => $this->categoryId,
                ':videoType' => $this->videoType,
                ':subTitle' => $this->subTitle,
                ':data' =>  $this->data,
                ':link' => $this->link,
                ':createdOn' => $this->createdOn);
            $this->execute("update locations set description = :description, latitude = :latitude, longitude = :longitude, `data` = :data, link = :link, 
                category_id = :category, videotype = :videoType, subtitle = :subTitle created_on = :createdOn where id = :id", $data);
        } else {
            $data = array(
                ':userId' => $this->userId,
                ':description' => $this->description,
                ':latitude' => $this->latitude,
                ':longitude' =>  $this->longitude,
                ':category' => $this->categoryId,
                ':videoType' => $this->videoType,
                ':subTitle' => $this->subTitle,
                ':data' =>  $this->data,
                ':link' => $this->link);

            $this->execute("insert into locations (user_id, description, latitude, longitude, `data`, link, category_id, videotype, subtitle) values 
                (:userId, :description, :latitude, :longitude, :data, :link, :category, :videoType, :subTitle);", $data);
            $locations = $this->fetch("select id from locations order by id desc limit 1");
            $location = $locations[sizeof($locations)-1];
            $this->id = intval($location['id']);
        }
        
        return $this;
    }

    public function update() {
        $data = array(
            ':id' => $this->id,
            ':description' => $this->description,
            ':latitude' => $this->latitude,
            ':longitude' =>  $this->longitude,
            ':category' => $this->categoryId,
            ':videoType' => $this->videoType,
            ':subTitle' => $this->subTitle,
            ':createdOn' => $this->createdOn);
        $this->execute("update locations set description = :description, latitude = :latitude, longitude = :longitude, 
            category_id = :category, videotype = :videoType, subtitle = :subTitle, created_on = :createdOn where id = :id", $data);

        return $this;
    }

    public function delete() {
        $data = array( ':id' => $this->id );
        $this->execute("delete from locations where id = :id", $data);
        return $this;
    }

    public function approve() {
        $data = array( ':id' => $this->id );
        $this->execute("update locations set approved_on = now() where id = :id", $data);
        return $this;
    }

    public function setGifPath($newPath) {
        $data = array( ':id' => $this->id, ':gifPath' => $newPath);
        $this->execute("update locations set `data` = :gifPath where id = :id", $data);
        return $this;
    }

    public function setVideoPath($newPath) {
        $data = array( ':id' => $this->id, ':videoPath' => $newPath);
        $this->execute("update locations set `link` = :videoPath where id = :id", $data);
        return $this;
    }

	/**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of longitude.
     *
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the value of longitude.
     *
     * @param mixed $longitude the longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Gets the value of latitude.
     *
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the value of latitude.
     *
     * @param mixed $latitude the latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the value of data.
     *
     * @param mixed $data the data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Gets the value of link.
     *
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the value of link.
     *
     * @param mixed $link the link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Gets the value of createdOn.
     *
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Sets the value of createdOn.
     *
     * @param mixed $createdOn the created on
     *
     * @return self
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Gets the value of updatedOn.
     *
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Sets the value of updatedOn.
     *
     * @param mixed $updatedOn the updated on
     *
     * @return self
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Gets the value of userId.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the value of userId.
     *
     * @param mixed $userId the user id
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     *
     * @return self
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApprovedOn()
    {
        return $this->approvedOn;
    }

    /**
     * @param mixed $approvedOn
     *
     * @return self
     */
    public function setApprovedOn($approvedOn)
    {
        $this->approvedOn = $approvedOn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     *
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     *
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoType()
    {
        return $this->videoType;
    }

    /**
     * @param mixed $videoType
     *
     * @return self
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param mixed $subTitle
     *
     * @return self
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }
}

?>