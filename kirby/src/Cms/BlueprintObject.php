<?php

namespace Kirby\Cms;

/**
 * Foundation for all other Blueprint objects/models
 *
 * TODO: refactor this. We don't really need it.
 */
class BlueprintObject extends Component
{

    /**
     * The parent collection
     *
     * @var Collection
     */
    protected $collection;

    /**
     * @var Page|Site|File|User
     */
    protected $model;

    public function __construct(array $props = [])
    {
        $props = Blueprint::extend($props);
        $this->setProperties($props);
    }

    /**
     * Returns the default parent collection
     *
     * @return Collection
     */
    protected function collection()
    {
        return $this->collection;
    }

    /**
     * Returns the parent model
     *
     * @return Page|File|Site|User
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Sets the parent Collection object
     * This is used to handle traversal methods
     * like next, prev, etc.
     *
     * @param Collection|null $collection
     * @return self
     */
    public function setCollection(Collection $collection = null)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * Sets the parent model
     *
     * @param Page|File|User|Site $model
     * @return self
     */
    protected function setModel($model = null)
    {
        $this->model = $model;
        return $this;
    }

    public function toArray(): array
    {
        return $this->propertiesToArray();
    }
}
