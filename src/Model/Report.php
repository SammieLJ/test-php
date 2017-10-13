<?php
/**
 * Created by PhpStorm.
 * User: StormTrooper
 * Date: 12.10.2017
 * Time: 14:28
 */

namespace BOF\Model;


class Report
{
    private $profile_id;
    private $profile_name;
    private $date;
    private $views;

    /**
     * Report constructor.
     * @param $profile_id
     * @param $profile_name
     * @param $date
     * @param $views
     */
    public function __construct($profile_id, $profile_name, $date, $views)
    {
        $this->profile_id = $profile_id;
        $this->profile_name = $profile_name;
        $this->date = $date;
        $this->views = $views;
    }

    public static function create($profile_id, $profile_name, $date, $views)
    {
        return new static($profile_id, $profile_name, $date, $views);
    }

    /**
     * @return mixed
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * @param mixed $profile_id
     */
    public function setProfileId($profile_id)
    {
        $this->profile_id = $profile_id;
    }

    /**
     * @return mixed
     */
    public function getProfileName()
    {
        return $this->profile_name;
    }

    /**
     * @param mixed $profile_name
     */
    public function setProfileName($profile_name)
    {
        $this->profile_name = $profile_name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param mixed $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }
}