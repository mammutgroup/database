<?php namespace Mammutgroup\Database\Geometries;

use GeoJson\GeoJson;

class Point extends Geometry
{
    protected $lat;
    protected $lng;

    public function __construct($lng, $lat = null)
    {
        return $this->setLngLat($lng, $lat);
    }

    public function setLngLat($lng, $lat = null)
    {
        if (is_object($lng) && $lng instanceof $this){
            $this->setLng($lng->getLng());
            $this->setLat($lng->getLat());
            return $this;
        }

        if (is_string($lng)) {
            $lng=trim($lng);
            if (preg_match('~^\-?\d+(\.\d+)?\s*,\s*\-?\d+(\.\d+)?$~', $lng)){
                $lng = explode(',', $lng);
            }elseif ($json = g_is_point($lng)){
                $lat = $json['coordinates'][0];
                $lng = $json['coordinates'][1];
            }
        }

        if (is_array($lng)) {
            $lng = array_map('trim', $lng);
            $lat = $lng[1];
            $lng = $lng[0];
        }

        $this->setLng($lng);
        $this->setLat($lat);
        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        if (g_is_valid_lat($lat)){
            return $this->lat = (float)$lat;
        }

        throw new \InvalidArgumentException('Lat value can not take ' . $lat);
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        if (g_is_valid_lng($lng)){
            return $this->lng = (float)$lng;
        }

        throw new \InvalidArgumentException('Lng value can not take ' . $lng);
    }

    public function toPair()
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    public static function fromPair($pair)
    {
        list($lng, $lat) = explode(' ', trim($pair));

        return new static((float)$lat, (float)$lng);
    }

    public function toWKT()
    {
        return sprintf('POINT(%s)', (string)$this);
    }

    public static function fromString($wktArgument)
    {
        return static::fromPair($wktArgument);
    }

    public function __toString()
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Point
     */
    public function jsonSerialize()
    {
        return new \GeoJson\Geometry\Point([$this->getLng(), $this->getLat()]);
    }
}
