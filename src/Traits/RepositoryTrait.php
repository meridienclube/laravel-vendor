<?php

namespace ConfrariaWeb\Vendor\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait RepositoryTrait
{

    public $obj;

    protected function cacheName($name = null)
    {
        return class_basename($this->obj) . $name;
    }

    public function withTrashed()
    {
        $this->obj = $this->obj->withTrashed();
        return $this;
    }

    public function onlyTrashed()
    {
        $this->obj = $this->obj->onlyTrashed();
        return $this;
    }

    public function withoutGlobalScope($scope)
    {
        $this->obj = $this->obj->withoutGlobalScope($scope);
        return $this;
    }

    public function withoutGlobalScopes($scopes)
    {
        $this->obj = $this->obj->withoutGlobalScope($scopes);
        return $this;
    }

    public function skip($offset = 0)
    {
        $this->obj = $this->obj->skip($offset);
        return $this;
    }

    public function take($limit = 10)
    {
        $this->obj = $this->obj->take($limit);
        return $this;
    }

    public function groupBy($by)
    {
        $this->obj = $this->obj->groupBy($by);
        return $this;
    }

    public function orderBy($order = 'id', $by = 'asc')
    {
        $this->obj = $this->obj->orderBy($order, $by);
        return $this;
    }

    public function get()
    {
        return $this->obj->get();
    }

    public function first()
    {
        return $this->obj->first();
    }

    public function paginate($take = 10)
    {
        return $this->obj->paginate($take);
    }

    public function select($fields = false)
    {
        $this->obj = ($fields) ? $this->obj->select($fields) : $this->obj;
        return $this;
    }

    public function all()
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitAll');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitAll');
        }
        return $this->obj->get();
    }

    public function create(array $data)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitCreate');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitCreate');
        }
        try {
            $create = $this->obj->create($data);
            $this->relationships($create, $data);
            return $create;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    public function find($id)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitFind');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitFind');
        }
        return $this->obj->find($id);
    }

    public function pluck($field = 'name', $id = 'id')
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitFind');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitFind');
        }
        return $this->obj->get()->pluck($field, $id);
    }

    public function findBy($field, $value)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitFindBy');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitFindBy');
        }
        if (
            !in_array($field, $this->obj->getFillable()) &&
            !Str::contains($field, 'option.') &&
            //!Str::contains($field, 'optionsValues.') &&
            !Str::contains($field, 'contact.')
        ) {
            return false;
        }
        return $this->obj
            ->when(in_array($field, $this->obj->getFillable()), function ($query) use ($field, $value) {
                return $query->where($this->obj->getTable() . '.' . $field, $value);
            })
            ->when(Str::contains($field, 'option.'), function ($query) use ($field, $value) {
                return $query->whereHas('optionsValues', function (Builder $query) use ($value) {
                    $query->where('optiongables.content', $value);
                });
            })
            ->when(Str::contains($field, 'contact.'), function ($query) use ($field, $value) {
                return $query->whereHas('contacts', function (Builder $query) use ($value) {
                    $query->where('contacts.content', $value);
                });
            })
            ->first();
    }

    public function update(array $data, $id)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in EloquentTraitUpdate');
            throw new RuntimeException('Missing OBJ attribute in EloquentTraitUpdate');
        }
        $update = $this->obj->find($id);
        $update->update($data);
        $this->relationships($update, $data);
        return $update;
    }


    public function updateOrCreate(array $attributes, array $values = array())
    {
        try {
            $attributeKey = key($attributes);
            $attributeVal = data_get($values, $attributeKey);
            $updateOrCreate = $this->findBy($attributeKey, $attributeVal);
            if (!$updateOrCreate) {
                $updateOrCreate = $this->create($values);
            } else {
                $updateOrCreate->update($values);
            }
            $this->relationships($updateOrCreate, $values);
            return $updateOrCreate;
        } catch (Exception $e) {
            return false;
        }
        return false;
    }


    public function destroy($id)
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }
        return $this->obj->destroy($id);
    }

    public function where(array $data = [])
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }
        $this->obj = $this->obj->where($data);
        return $this;
    }

    public function orWhere(array $data = [])
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }
        $this->obj = $this->obj->orWhere($data);
        return $this;
    }

    public function whereIn(string $column, $data = [])
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }
        $this->obj = $this->obj->whereIn($this->obj->getTable() . '.' . $column, $data);
        return $this;
    }

    protected function attachEloquent($obj, array $data)
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }
        if (isset($data['files'])) {
            $obj->files()->createMany($data['files']);
        }
        if (isset($data['address'])) {
            $data['address'] = resolve('AddressService')->prepareData($data['address']);
            $obj->addresses()->create($data['address']);
        }
        if (isset($data['contacts'])) {
            $obj->contacts()->createMany($data['contacts']);
        }
        if (isset($data['contact'])) {
            $obj->contacts()->create($data['contact']);
        }
        if (isset($data['historic'])) {
            $obj->historics()->create($data['historic']);
        }
        if (isset($data['historics'])) {
            $obj->historics()->createMany($data['historics']);
        }
    }

    protected function attach($obj, array $data)
    {
        //
    }

    protected function syncWithoutDetachingEloquent($obj, array $data)
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }

        if (isset($data['optionsValues'])) {
            $obj->optionsValues()->sync($data['optionsValues']);
        }
    }

    protected function syncWithoutDetaching($obj, array $data)
    {
        //
    }

    protected function syncsEloquent($obj, array $data)
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }

        if (isset($data['address']) && isset($data['address']['city_id'])) {
            $obj->addresses()->delete();
            $data['address'] = resolve('AddressService')->prepareData($data['address']);
            $obj->addresses()->create($data['address']);
        }

        if (isset($data['options'])) {
            $obj->options()->sync($data['options']);
        }

        if (isset($data['optionsValues'])) {
            $obj->optionsValues()->sync($data['optionsValues']);
        }

        if (isset($data['integrations'])) {
            $obj->integrations()->attach($data['integrations']);
        }

        if (isset($data['contact'])) {
            $obj->contacts()->delete();
            $obj->contacts()->create($data['contact']);
        }

        if (isset($data['contacts'])) {
            $obj->contacts()->delete();
            $obj->contacts()->createMany($data['contacts']);
        }

    }

    protected function syncs($obj, array $data)
    {
        //
    }

    protected function relationships($obj, array $data)
    {
        if (!property_exists($this, 'obj')) {
            throw new RuntimeException('Missing OBJ attribute');
        }

        if (isset($data['attach'])) {
            $this->attachEloquent($obj, $data['attach']);
            $this->attach($obj, $data['attach']);
        }

        if (isset($data['sync'])) {
            $this->syncsEloquent($obj, $data['sync']);
            $this->syncs($obj, $data['sync']);
        }

        if (isset($data['syncWithoutDetaching'])) {
            $this->syncWithoutDetachingEloquent($obj, $data['syncWithoutDetaching']);
            $this->syncWithoutDetaching($obj, $data['syncWithoutDetaching']);
        }
    }

    /**
     * Toda a Model que extender a Trait ContactTrait pode gravar contatos
     * passando uma matriz contendos os arrays com 'type_id' e 'content'.
     * @param array $data = ['type_id' => 'id', 'content' => 'contact']
     * @param $id = ID do objeto mantenedor dos contatos
     * @return mixed
     */
    public function createContact(array $data, $id)
    {
        $create = $this->obj->find($id);
        if (isset($data['sync']['contacts'])) {
            return $create->contacts()->createMany($data['sync']['contacts']);
        }
        if (isset($data['sync']['contact'])) {
            return $create->contacts()->create($data['sync']['contact']);
        }
        if (isset($data['type_id']) && isset($data['content'])) {
            return $create->contacts()->create($data);
        }
        return false;
    }


}
