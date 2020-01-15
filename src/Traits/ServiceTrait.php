<?php

namespace ConfrariaWeb\Vendor\Traits;

use Illuminate\Support\Facades\Log;

trait ServiceTrait
{

    protected $obj;

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

    public function trashed()
    {
        return $this->obj->onlyTrashed()->get();
    }

    public function withoutGlobalScope($scope)
    {
        $this->obj = $this->obj->withoutGlobalScope($scope);
        return $this;
    }

    public function withoutGlobalScopes($scopes)
    {
        $this->obj = $this->obj->withoutGlobalScopes($scopes);
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

    public function paginate($take = 10)
    {
        return $this->obj->paginate($take);
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

    public function select($fields)
    {
        $this->obj = $this->obj->select($fields);
        return $this;
    }

    public function get()
    {
        return $this->obj->get();
    }

    function all()
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitAll');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitAll');
        }
        try {
            return $this->obj->all();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    function find($id)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitFind');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitFind');
        }
        try {
            return $this->obj->find($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    public function findBy($field, $value)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitFindBy');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitFindBy');
        }
        try {
            return $this->obj->findBy($field, $value);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    function pluck($field = 'name', $id = 'id')
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitPluck');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitPluck');
        }
        try {
            return $this->obj->pluck($field, $id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    function where(array $data)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitWhere');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitWhere');
        }
        try {
            $this->obj = $this->obj->where($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $this;
    }

    public function orWhere(array $data)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitWhere');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitWhere');
        }
        try {
            $this->obj = $this->obj->orWhere($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $this;
    }

    public function whereIn(string $column, $data = [])
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitWhereIn');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitWhereIn');
        }
        try {
            $this->obj = $this->obj->whereIn($column, $data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     */
    function create(array $data)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitCreate');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitCreate');
        }
        try {
            //dd($this->obj);
            $data = $this->prepareData($data);
            $data = $this->prepareRelationships($data);
            //$data = $this->sometimes($data);
            $this->executeEvent($this->obj->obj, 'Saving');
            $this->executeEvent($this->obj->obj, 'Creating');
            $obj = $this->obj->create($data);
            $this->executeEvent($obj, 'Saved');
            $this->executeEvent($obj, 'Created');
            $this->executeSchedule($obj, 'Saved');
            $this->executeSchedule($obj, 'Created');
            return $obj;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param array $data
     * @return bool|null
     */
    public function createMany(array $data)
    {
        try {
            foreach ($data as $objData) {
                $obj[] = $this->create($objData);
            }
            return isset($obj) ? $obj : NULL;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     */
    function update($data, $id)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitUpdate');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitUpdate');
        }
        try {
            $data = $this->prepareData($data);
            $data = $this->prepareRelationships($data);
            $data = $this->sometimes($data);
            $obj = $this->obj->find($id);
            if ($obj) {
                $this->executeEvent($obj, 'Saving');
                $this->executeEvent($obj, 'Updating');
                $obj = $this->obj->update($data, $id);
                $this->executeEvent($obj, 'Saved');
                $this->executeEvent($obj, 'Updated');
                $this->executeSchedule($obj, 'Saved');
                $this->executeSchedule($obj, 'Updated');
                return $obj;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param array $data
     * @param string $key_field
     * @param null $obj
     * @return array|bool|null
     */
    public function updateMany(array $data, string $key_field, $obj = null)
    {
        try {
            foreach ($data as $userData) {
                $findBy = $this->findBy($key_field, $userData[$key_field]);
                if ($findBy) {
                    $obj[] = $this->update($userData, $findBy->id);
                }
            }
            return $obj;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param array $data
     * @param string $key_field
     * @return bool
     */
    public function updateOrCreate(array $data, string $key_field)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitUpdateOrCreate');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitUpdateOrCreate');
        }
        try {
            $data = $this->prepareData($data);;
            $updateOrCreate = $this->findBy($key_field, data_get($data, $key_field));

            $data = $this->prepareRelationships($data);
            $data = $this->sometimes($data);

            $attributes = [$key_field => data_get($data, $key_field)];

            $obj = $this->obj->updateOrCreate($attributes, $data);

            $this->executeEvent($obj, 'Saved');
            $this->executeSchedule($obj, 'Saved');
            if (!is_null($updateOrCreate)) {
                $this->executeEvent($obj, 'Updated');
                $this->executeSchedule($obj, 'Updated');
            } else {
                $this->executeEvent($obj, 'Created');
                $this->executeSchedule($obj, 'Created');
            }

            return $obj;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param array $data
     * @param string $key_field
     * @param bool $obj
     * @return array|bool
     */
    public function updateOrCreateMany(array $data, string $key_field, $obj = false)
    {
        try {
            if (isset($data)) {
                foreach ($data as $d) {
                    $obj[] = $this->updateOrCreate($d, $key_field);
                }
            }
            return $obj;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    function destroy($id)
    {
        if (!property_exists($this, 'obj')) {
            Log::error('Missing OBJ attribute in ServiceTraitDestroy');
            throw new RuntimeException('Missing OBJ attribute in ServiceTraitDestroy');
        }
        try {
            $obj = $this->obj->find($id);

            if ($obj) {
                $this->executeEvent($obj, 'Deleting');
                $deleted = $obj->delete();
                $this->executeEvent($obj, 'Deleted');
                $this->executeSchedule($obj, 'Deleted');
                return $obj;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return false;
    }

    public function executeEvent($obj, $action)
    {
        try {
            $event = $this->checkEventExist($obj, $action);
            if ($event) {
                $eventClass = new $event($obj);
                event($eventClass);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function checkEventExist($obj, $action)
    {
        if (!$obj) {
            return false;
        }

        $event = false;
        $eventCW = false;

        $get_class = get_class($obj);
        $get_class_basename = class_basename(get_class($obj));

        $event = (string) 'App\\Events\\' . $get_class_basename . $action . 'Event';

        if (class_exists($event)) {
            return $event;
        }

        $explodeClass = explode('\\', $get_class);
        if (isset($explodeClass[0]) && $explodeClass[0] == 'ConfrariaWeb' && isset($explodeClass[1])) {
            $eventCW = (string) 'ConfrariaWeb\\' . $explodeClass[1] . '\\Events\\' . $get_class_basename . $action . 'Event';
            if (class_exists($eventCW)) {
                return $eventCW;
            }
        }

        return false;
    }

    public function executeSchedule($obj, $when)
    {
        try {
            if (!class_exists('ScheduleService')) {
                return false;
            }
            $where = class_basename(get_class($obj));
            Log::info($where);
            if (isset($obj) && isset($when) && isset($where)) {
                resolve('ScheduleService')->executeService($obj, $where, $when);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return |null
     */
    protected function prepareAddress(array $data)
    {
        if (isset($data) && !empty($data)) {
            return resolve('AddressService')->prepareData($data);
        }
        return null;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function formatOptionsForRelationships(array $data)
    {
        return resolve('OptionService')->formatOptionsForRelationships($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function encodeOptions(array $data)
    {
        return resolve('OptionService')->encodeOptions($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function prepareRelationships($data)
    {
        if (isset($data['sync']['options'])) {
            $data['sync']['options'] = $data['sync']['options'];
            //$data['sync']['options'] = $this->formatOptionsForRelationships(isset($data['sync']['options']) ? $data['sync']['options'] : []);
        }

        if (isset($data['syncWithoutDetaching']['optionsValues'])) {
            $data['syncWithoutDetaching']['optionsValues'] = $this->formatOptionsForRelationships(isset($data['syncWithoutDetaching']['optionsValues']) ? $data['syncWithoutDetaching']['optionsValues'] : []);
        }

        if (isset($data['sync']['optionsValues'])) {
            $data['sync']['optionsValues'] = $this->formatOptionsForRelationships(isset($data['sync']['optionsValues']) ? $data['sync']['optionsValues'] : []);
        }

        if (isset($data['attach']['address'])) {
            $data['attach']['address'] = $this->prepareAddress(isset($data['attach']['address']) ? $data['attach']['address'] : []);
        }

        if (isset($data['sync']['address'])) {
            $data['sync']['address'] = $this->prepareAddress(isset($data['sync']['address']) ? $data['sync']['address'] : []);
        }

        /*Prepare contacts*/
        if (isset($data['syncWithoutDetaching']['contacts'])) {
            $data['syncWithoutDetaching']['contacts'] = $this->prepareContacts($data['syncWithoutDetaching']['contacts']);
        }
        if (isset($data['sync']['contacts'])) {
            $data['sync']['contacts'] = $this->prepareContacts($data['sync']['contacts']);
        }
        if (isset($data['attach']['contacts'])) {
            $data['attach']['contacts'] = $this->prepareContacts($data['attach']['contacts']);
        }

        return $data;
    }

    public function prepareData(array $data)
    {
        return $data;
    }

    public function sometimes($data)
    {
        if (isset($data) && isset($this->sometimes) && is_array($this->sometimes)) {
            foreach ($this->sometimes as $sometimes) {
                if (
                    !isset($data[$sometimes]) ||
                    empty($data[$sometimes]) ||
                    is_null($data[$sometimes])
                ) {
                    unset($data[$sometimes]);
                }
            }
        }
        return $data;
    }

    public function createComment($data, $id)
    {
        $createComment = $this->obj->find($id);
        $user_id = isset($createComment->user_id) ?: NULL;
        $user_id = is_array($data) && isset($data['user_id']) ? $data['user_id'] : $user_id;
        $content = is_array($data) ? $data['content'] : $data;
        if (isset($user_id) && isset($content)) {
            $comment = $createComment->comments()->create([
                'content' => $content,
                'user_id' => $user_id
            ]);
            $this->executeEvent($comment, 'Saved');
            $this->executeSchedule($comment, 'Saved');
            $this->executeEvent($comment, 'Created');
            $this->executeSchedule($comment, 'Created');
            return $comment;
        }
        return false;
    }

    public function datatable($data)
    {
        $table = $this->obj->obj->getTable();
        $objThis = (!isset($data['trashed']) || $data['trashed'] < 1) ? $this->obj : $this->obj->onlyTrashed();
        $draw = isset($data['draw']) ? $data['draw'] : NULL;
        $skip = isset($data['start']) ? $data['start'] : 0;
        $take = isset($data['length']) ? $data['length'] : 10;
        $where = isset($data['where']) ? $data['where'] : [];
        $orWhere = isset($data['orWhere']) ? $data['orWhere'] : [];
        $columns = isset($data['columns']) ? $data['columns'] : NULL;
        $order = $table . '.' . 'id';
        $orderBy = 'asc';
        if (isset($data['order']) && isset($columns[$data['order'][0]['column']]['name'])) {
            $order = $columns[$data['order'][0]['column']]['name'];
        }
        if (isset($data['order'][0]['dir'])) {
            $orderBy = $data['order'][0]['dir'];
        }
        $recordsTotal = $objThis->all()->count();
        $objThis = $objThis
            ->where($where)
            ->orWhere($orWhere);
        $recordsFiltered = $objThis->get()->count();
        $ServiceWhere = $objThis
            ->skip($skip)
            ->take($take)
            ->orderBy($order, $orderBy)
            ->get();
        $dataResponse = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $ServiceWhere
        ];
        return $dataResponse;
    }

    /**
     * Este método prepara o array de contatos para serem salvos na tabela "contacts"
     * Os contatos devem ser passados da controller via "sync", "syncWithoutDetaching" ou "attach", EX:
     * $data['attach']['contacts'] = ['telefone => '55 51 9988-7766', 'email' => 'rafazingano@gmail.com']
     * As chaves do array devem ser meios de contatos cadastrados na tabela contact_types collunn slug
     * @param $data
     * @return array
     */
    protected function prepareContacts($data)
    {
        $contacts = [];
        if (!isset($data)) {
            return $contacts;
        }
        if (isset($data['type_id']) && isset($data['content'])) {
            $contacts[] = ['type_id' => $data['type_id'], 'content' => $data['content']];
            return $contacts;
        }
        foreach ($data as $k => $v) {
            if (isset($v['type_id']) && isset($v['content'])) {
                $contacts[] = ['type_id' => $v['type_id'], 'content' => $v['content']];
                continue;
            }
            if (!is_string($k) || !is_string($v)) {
                continue;
            }
            $type = resolve('ContactTypeService')->findBy('slug', $k);
            if ($type) {
                $contacts[] = ['type_id' => $type->id, 'content' => $v];
            }
        }
        return $contacts;
    }

    public function createContact(array $data, int $id)
    {
        return $this->obj->createContact($data, $id);
    }
}
