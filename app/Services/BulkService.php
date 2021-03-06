<?php

namespace App\Services;

use App\Datagrids\Bulks\Bulk;
use App\Exceptions\TranslatableException;
use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\MiscModel;
use Exception;
use Illuminate\Support\Str;

class BulkService
{
    /**
     * @var EntityService
     */
    protected $entityService;

    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var array
     */
    protected $ids;

    /**
     * BulkService constructor.
     * @param EntityService $entityService
     * @param PermissionService $permissionService
     */
    public function __construct(EntityService $entityService, PermissionService $permissionService)
    {
        $this->entityService = $entityService;
        $this->permissionService = $permissionService;
    }

    /**
     * @param string $entityName
     * @return $this
     */
    public function entity(string $entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function entities(array $ids = [])
    {
        $this->ids = $ids;
        return $this;
    }

    /**
     * Delete several entities
     * @param string $entityName
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function delete()
    {
        $model = $this->getEntity();

        $count = 0;
        foreach ($this->ids as $id) {
            $entity = $model->find($id);
            if (Auth::user()->can('delete', $entity)) {
                //dd($entity->descendants);
                $entity->delete();
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param $entityName
     * @param array $ids
     * @return array
     * @throws Exception
     */
    public function export()
    {
        $model = $this->getEntity();
        $entities = [];
        foreach ($this->ids as $id) {
            $entities[] = $model->findOrFail($id);
        }
        return $entities;
    }

    /**
     * Set permissions for several entities
     * @param array $users
     * @param array $roles
     * @return int number of updated entities
     */
    public function permissions(array $permissions = [], bool $override = true): int
    {
        $count = 0;
        $model = $this->getEntity();

        foreach ($this->ids as $id) {
            $entity = $model->findOrFail($id);
            if (Auth::user()->can('update', $entity)) {
                $this->permissionService->change($permissions, $entity->entity, $override);
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param array $fields
     * @param Bulk $bulk
     * @return int
     * @throws Exception
     */
    public function editing(array $fields, Bulk $bulk): int
    {
        $count = 0;
        $model = $this->getEntity();

        // Only get fields that can be bulk edited and with content
        $fillableFields = Arr::only($fields, $bulk->fields());
        $filledFields = [];
        foreach ($fillableFields as $field => $value) {
            if (!empty($value)) {
                $filledFields[$field] = $value;
            }
        }

        foreach ($bulk->mappings() as $field) {
            if (Arr::has($fields, $field)) {
                $value = Arr::get($fields, $field);
                if (Str::startsWith($field, 'is_') && $value === null) {
                    // Do nothing
                } else {
                    $filledFields[$field] = $value;
                }
            }
        }

        // Private
        if (isset($fields['is_private']) && $fields['is_private'] !== null) {
            $filledFields['is_private'] = $fields['is_private'] === "0";
        }


        // Handle tags differently
        unset($filledFields['tags']);
        $tagIds = Arr::get($fields, 'tags', []);

        foreach ($this->ids as $id) {
            /** @var MiscModel $entity */
            $entity = $model->with('entity', 'entity.tags')->findOrFail($id);
            if (Auth::user()->can('update', $entity)) {
                $entity->savingObserver = false;
                $entity->update($filledFields);
                $count++;

                // Tags?
                if (!empty($fields['tags'])) {
                    /** @var Collection $existingTags */
                    $tagAction = Arr::get($fields, 'bulk-tagging', 'add');
                    if ($tagAction === 'remove') {
                        $entity->entity->tags()->detach($tagIds);
                    } else {
                        $existingTags = $entity->entity->tags->pluck('id')->toArray();
                        $addTagIds = $tagIds;
                        // Exclude existing tags to avoid adding a tag several times
                        if (!empty($existingTags)) {
                            $addTagIds = [];
                            foreach ($tagIds as $tag) {
                                if (!in_array($tag, $existingTags)) {
                                    $addTagIds[] = $tag;
                                }
                            }
                        }
                        // If we have tags to add
                        if (!empty($tagIds)) {
                            $entity->entity->tags()->attach($addTagIds);
                        }
                    }
                }
            }
        }

        return $count;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getEntity()
    {
        $entity = $this->entityService->getClass($this->entityName);
        if (empty($entity)) {
            throw new Exception("Unknown entity name {$this->entityName}.");
        }

        $model = new $entity();
        if (empty($model)) {
            throw new Exception("Couldn't create a class from {$this->entity}.");
        }

        return $model;
    }
}
