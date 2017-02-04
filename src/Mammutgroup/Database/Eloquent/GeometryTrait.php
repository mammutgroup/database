<?php namespace Mammutgroup\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Arr;
use Mammutgroup\Database\Exceptions\GeoFieldsNotDefinedException;
use Mammutgroup\Database\Geometries\Geometry;
use Mammutgroup\Database\Geometries\GeometryInterface;

trait GeometryTrait
{
    public $geometries = [];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function newQuery($excludeDeleted = true)
    {
        $raw = '';
        foreach ($this->geoFields as $column) {
            $raw .= ' ST_AsBinary(' . $column . ') as ' . $column . ' ';
        }

        return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        $pgfields = $this->getGeoFields();

        foreach ($attributes as $attribute => &$value) {
            if (in_array($attribute, $pgfields) && is_string($value) && strlen($value) >= 15) {

                $value = Geometry::fromWKB($value);
            }
        }

        parent::setRawAttributes($attributes, $sync);
    }

    public function getGeoFields()
    {
        if (property_exists($this, 'geoFields')) {
            return Arr::isAssoc($this->geoFields) ? //Is the array associative?
                array_keys($this->geoFields) : //Returns just the keys to preserve compatibility with previous versions
                $this->geoFields; //Returns the non-associative array that doesn't define the geometry type.
        } else {
            throw new GeoFieldsNotDefinedException(__CLASS__ . ' has to define $geoFields');
        }
    }

    protected function performInsert(EloquentBuilder $query, array $options = [])
    {
        foreach ($this->attributes as $key => $value) {
            if ($value instanceof GeometryInterface && !$value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $this->attributes[$key] = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s')", $value->toWKT()));
            } else if ($value instanceof GeometryInterface && $value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $this->attributes[$key] = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s', 4326)", $value->toWKT()));
            }
        }

        $insert = parent::performInsert($query, $options);

        foreach ($this->geometries as $key => $value) {
            $this->attributes[$key] = $value; //Retrieve the geometry objects so they can be used in the model
        }

        return $insert; //Return the result of the parent insert
    }

    protected function performUpdate(EloquentBuilder $query, array $options = [])
    {
        foreach ($this->attributes as $key => $value) {
            if ($value instanceof GeometryInterface && !$value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $this->attributes[$key] = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s')", $value->toWKT()));
            } else if ($value instanceof GeometryInterface && $value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $this->attributes[$key] = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s', 4326)", $value->toWKT()));
            }
        }

        $update = parent::performUpdate($query, $options);

        foreach ($this->geometries as $key => $value) {
            $this->attributes[$key] = $value; //Retrieve the geometry objects so they can be used in the model
        }

        return $update; //Return the result of the parent insert
    }
}
