<?php

class Channel extends Model {
	private $id;
	private $title;
	private $description;

	private $locations;

	private $createdOn;
	private $updatedOn;

    public function save() {
        if ($this->getId() > 0) {
            $data = array(
                ':id' => $this->id,
                ':title' => $this->title,
                ':description' => $this->description);
            $this->execute("update channels set title = :title, description = :description where id = :id", $data);
        } else {
            $data = array(
                ':title' => $this->title,
                ':description' => $this->description);

            $this->execute("insert into channels (title, description) values (:title, :description);", $data);
            $channels = $this->fetch("select id from channels order by id desc limit 1");
            $channel = $channels[0];
            $this->id = intval($channel['id']);
        }
        
        return $this;
    }

    public function load() {
        $channels = $this->fetch("select * from channels where id = :id", array(':id' => $this->id));
        if (sizeof($channels) > 0) {
            $channel = $channels[0];

            $this->setTitle($channel['title'])
                ->setDescription($channel['description']);

            $locations = $this->fetch("select * from channel_locations cl join locations l on cl.location_id = l.id
                    where cl.channel_id = :id
                order by order_id asc;", array(':id' => $this->id));

            $outLocations = array();
            foreach ($locations as $location) {
                $currentLocation = new Location();
                $currentLocation->setId($location['id'])
                    ->setUserId($location['user_id'])
                    ->setCategoryId($location['category_id'])
                    ->setDescription($location['description'])
                    ->setLatitude($location['latitude'])
                    ->setLongitude($location['longitude'])
                    ->setData($location['data'])
                    ->setLink($location['link'])
                    ->setCreatedOn($location['created_on'])
                    ->setOrderId($location['order_id']);

                $outLocations[] = $currentLocation;
            }

            $this->setLocations($outLocations);
        }

        return $this;
    }

    public function getChannels() {
        $channels = $this->fetch("select c.*
            from channels c 
            order by c.id asc");

        $outChannels = array();
        foreach ($channels as $c) {
            $channel = new Channel();
            $channel->setId($c['id'])
                ->setTitle($c['title'])
                ->setDescription($c['description']);
            $outChannels[] = $channel;
        }
        
        return $outChannels;
    }

	public function getAll() {
        $locations = $this->fetch("select c.id as channel_id, c.title, c.description as channel_description, 
        		l.*, cl.order_id
        	from channels c 
				join channel_locations cl on c.id = cl.channel_id 
				join locations l on cl.location_id = l.id
			order by c.id, cl.order_id asc");

        $channels = array();
        if(sizeof($locations) > 0) {
        	$currentChannel = 0;
        	foreach ($locations as $location) {
        		$currentLocation = new Location();
	            $currentLocation->setId($location['id'])
	                ->setUserId($location['user_id'])
	                ->setCategoryId($location['category_id'])
	                ->setDescription($location['description'])
	                ->setLatitude($location['latitude'])
	                ->setLongitude($location['longitude'])
	                ->setData($location['data'])
	                ->setLink($location['link'])
	                ->setCreatedOn($location['created_on'])
	                ->setOrderId($location['order_id']);

                $channels[$location['channel_id']]['id'] = $location['channel_id'];
	            $channels[$location['channel_id']]['title'] = $location['title'];
	            $channels[$location['channel_id']]['description'] = $location['channel_description'];
	            $channels[$location['channel_id']]['locations'][] = $currentLocation;
	        }
	    }
        return $channels;
    }

    public function getById() {
        $locations = $this->fetch("select c.id as channel_id, c.title, c.description as channel_description, 
                l.*, cl.order_id
            from channels c 
                join channel_locations cl on c.id = cl.channel_id 
                join locations l on cl.location_id = l.id
            where c.id = :id
            order by c.id, cl.order_id asc", array(':id' => $this->id));

        $channel = array();
        if(sizeof($locations) > 0) {
            foreach ($locations as $location) {
                $currentLocation = new Location();
                $currentLocation->setId($location['id'])
                    ->setUserId($location['user_id'])
                    ->setCategoryId($location['category_id'])
                    ->setDescription($location['description'])
                    ->setLatitude($location['latitude'])
                    ->setLongitude($location['longitude'])
                    ->setData($location['data'])
                    ->setLink($location['link'])
                    ->setCreatedOn($location['created_on'])
                    ->setOrderId($location['order_id']);

                $channel['id'] = $location['channel_id'];
                $channel['title'] = $location['title'];
                $channel['description'] = $location['channel_description'];
                $channel['locations'][] = $currentLocation;
            }
        }
        return $channel;
    }

    public function addLocation($locationId) {
        $channelLocation = $this->fetch("select max(order_id) as mid from channel_locations where channel_id = :channelId", array(':channelId' => $this->id));
        $channelLocation = $channelLocation[0];

        $newOrderId = 0;
        if ($channelLocation) {
            $newOrderId = $channelLocation['mid'] + 1;
        }

        $data = array(':channelId' => $this->id,
            ':locationId' => $locationId,
            ':orderId' => $newOrderId);
        $this->execute("insert into channel_locations (id, channel_id, location_id, order_id) values (NULL, :channelId, :locationId, :orderId)", $data);
        
        return true;
    }

    public function removeLocation($locationId) {
        $data = array(':channelId' => $this->id,
            ':locationId' => $locationId);
        $this->execute("delete from channel_locations where channel_id = :channelId and location_id = :locationId", $data);

        return true;
    }

    public function delete() {
        $data = array( ':id' => $this->id );
        $this->execute("delete from channels where id = :id", $data);
        return $this;
    }

    public function setOrder($newOrder) {
        $counter = 0;
        foreach ($newOrder as $newItem) {
            $data = array(':channelId' => $this->id,
                ':locationId' => $newItem,
                ':orderId' => $counter++);
            $this->execute("update channel_locations set order_id = :orderId where channel_id = :channelId and location_id = :locationId", $data);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     *
     * @return self
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param mixed $updatedOn
     *
     * @return self
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     *
     * @return self
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;

        return $this;
    }
}