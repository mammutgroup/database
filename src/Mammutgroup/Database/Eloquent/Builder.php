<?php namespace Mammutgroup\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Mammutgroup\LaravelPostgis\Geometries\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    protected function getGeoFields()
    {
        return $this->getModel()->getGeoFields();
    }


    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw(sprintf("ST_GeomFromText('%s')", $geometry->toWKT()));
    }
}
