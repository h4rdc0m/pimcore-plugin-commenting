<?php
class Resource_Comment extends Pimcore_Model_Abstract
{
    /**
     * @var integer
     */
    public $id = 0;

    /**
     *
     * @var integer
     */
    public $data;

    /**
     *
     * @var string
     */
    public $user;

    /**
     * @var integer
     */
    public $userId;

    /**
     *
     * @var integer
     */
    public $date;

    /**
     *
     * @var Element_Interface $commentingTarget
     */
    public $commentingTarget;

    /**
     *
     * @var integer
     */
    public $commentingTargetId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $metadata;

    public function getResource()
    {
        if (!$this->resource) {
            $this->initResource("Resource_Comment");
        }
        return $this->resource;
    }


    /**
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * return string $userId
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return integer $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return Zend_Date $date
     */
    public function getDate()
    {
        return new Zend_Date($this->date, Zend_Date::TIMESTAMP);
    }

    /**
     * @param integer|Zend_Date $date
     */
    public function setDate($date)
    {
        if($date instanceof Zend_Date) {
            $date = $date->getTimestamp();
        }
        $this->date = $date;
    }

    /**
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Element_Interface $commentingTarget
     */
    public function getCommentingTarget()
    {
        return $this->commentingTarget;
    }

    /**
     * @return integer
     */
    public function getCommentingTargetId()
    {
        return $this->commentingTargetId;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        if(is_array($metadata)) {
            $metadata = serialize($metadata);
        }
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return new ArrayObject(unserialize($this->metadata), ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param Object_Abstract $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        $this->userId = $user->getO_Id();
    }

    /**
     *
     * @param integer $id
     */
    public function setUserId($id)
    {
        $this->userId = $id;
        try {
            $this->user = Object_Abstract::getById($id);
        } catch (Exception $e) {

        }
    }

    /**
     *
     * @param integer $id
     */
    public function setCommentingTargetId($id)
    {
        $this->commentingTargetId = $id;
        try {
            if ($this->type == "object") {
                $this->commentingTarget = Object_Abstract::getById($id);
            } else if ($this->type == "asset") {
                $this->commentingTarget = Asset::getById($id);
            } else if ($this->type == "document") {
                $this->commentingTarget = Document::getById($id);
            } else {
                Logger::log(get_class($this) . ": could not set resource - unknown type[" . $this->type . "]");
            }
        } catch (Exception $e) {
            Logger::log(get_class($this) . ": Error setting resource");
        }
    }

    /**
     *
     * @param Element_Interface $commentingTarget
     */
    public function setCommentingTarget($commentingTarget)
    {
        $this->commentingTarget = $commentingTarget;
        $this->commentingTargetId = $commentingTarget->getId();
    }

    /**
     *
     * @param integer $data
     */
    public function setData($data)
    {
        $this->data = $data;

    }

    /**
     * @return void
     */
    public function save()
    {
        if ($this->getId()) {
            $this->update();
        } else {
            $this->getResource()->create();
            $this->update();
        }
    }

    /**
     * Deletes Comment
     */
    public function delete()
    {
        $this->getResource()->delete();
    }

    /**
     * Deletes all comments for current target
     */
    public function deleteAllForTarget()
    {
        $this->getResource()->deleteAllForTarget();
    }

    /**
     *
     * @param Resource_Comment $id
     */
    public static function getById($id)
    {
        $comment = new Resource_Comment();
        $comment->getResource()->getById($id);
        return $comment;
    }
}