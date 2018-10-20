<?php
namespace Bookly\Lib\Entities;

use Bookly\Lib;

/**
 * Class Shop
 * @package Bookly\Lib\Entities
 */
class Shop extends Lib\Base\Entity
{
    /** @var  int */
    protected $plugin_id;
    /** @var  string */
    protected $type;
    /** @var  string */
    protected $title;
    /** @var  string */
    protected $slug;
    /** @var  string */
    protected $description;
    /** @var  string */
    protected $url;
    /** @var  string */
    protected $icon;
    /** @var float */
    protected $price;
    /** @var int */
    protected $sales;
    /** @var float */
    protected $rating;
    /** @var int */
    protected $reviews;
    /** @var  string */
    protected $published;
    /** @var  int */
    protected $seen = 0;
    /** @var  string */
    protected $created;

    protected static $table = 'bookly_shop';

    protected static $schema = array(
        'id'          => array( 'format' => '%d' ),
        'plugin_id'   => array( 'format' => '%d' ),
        'type'        => array( 'format' => '%s' ),
        'title'       => array( 'format' => '%s' ),
        'slug'        => array( 'format' => '%s' ),
        'description' => array( 'format' => '%s' ),
        'url'         => array( 'format' => '%s' ),
        'icon'        => array( 'format' => '%s' ),
        'price'       => array( 'format' => '%f' ),
        'sales'       => array( 'format' => '%d' ),
        'rating'      => array( 'format' => '%f' ),
        'reviews'     => array( 'format' => '%d' ),
        'published'   => array( 'format' => '%s' ),
        'seen'        => array( 'format' => '%d' ),
        'created'     => array( 'format' => '%s' ),
    );

    /**************************************************************************
     * Entity Fields Getters & Setters                                        *
     **************************************************************************/

    /**
     * Gets plugin_id
     *
     * @return int
     */
    public function getPluginId()
    {
        return $this->plugin_id;
    }

    /**
     * Sets message_id
     *
     * @param int $plugin_id
     * @return $this
     */
    public function setPluginId( $plugin_id )
    {
        $this->plugin_id = $plugin_id;

        return $this;
    }

    /**
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets type
     *
     * @param string $type
     * @return $this
     */
    public function setType( $type )
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets slug
     *
     * @param string $slug
     * @return $this
     */
    public function setSlug( $slug )
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Gets description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription( $description )
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets icon
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon( $icon )
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice( $price )
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets sales
     *
     * @return int
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Sets sales
     *
     * @param int $sales
     * @return $this
     */
    public function setSales( $sales )
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * Gets rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Sets rating
     *
     * @param float $rating
     * @return $this
     */
    public function setRating( $rating )
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Gets reviews
     *
     * @return int
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Sets reviews
     *
     * @param int $reviews
     * @return $this
     */
    public function setReviews( $reviews )
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Gets published
     *
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Sets published
     *
     * @param string $published
     * @return $this
     */
    public function setPublished( $published )
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Gets seen
     *
     * @return int
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Sets seen
     *
     * @param int $seen
     * @return $this
     */
    public function setSeen( $seen )
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Gets created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets created
     *
     * @param string $created
     * @return $this
     */
    public function setCreated( $created )
    {
        $this->created = $created;

        return $this;
    }

}