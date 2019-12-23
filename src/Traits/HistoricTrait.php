<?php

namespace App\Traits;

use App\User;
use Auth;

trait HistoricTrait
{

    public function register($historic){
        $data['data'] = $historic->data();
        $data['title'] = $historic->title();
        $data['user_id'] = $historic->user('id');
        return $this->historics()->create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    /*
    public function createHistoric(array $data)
    {
        $getOriginal = $this->getOriginal();
        $getChanges = $this->getChanges();
        $prefixLang = isset($this) ? str_replace('_', 's.', $this->getTable()) . '.' : null;
        $action = ($this->wasRecentlyCreated) ? 'created' : 'updated';
        $data['title'] = isset($data['title']) ? $data['title'] : $prefixLang . $action;
        $data['user_id'] = $this->extractUserToHistory($data);
        //$data['data'] = isset($data['data']) ? $data['data'] : ['action' => $action, 'content' => $prefixLang . $action . '.content'];
        $data['data']['action'] = isset($data['data']['action']) ? $data['data']['action'] : $action;
        $data['data']['content'] = isset($data['data']['content']) ? $data['data']['content'] : $prefixLang . $action . '.content';
        if (isset($getChanges) && !empty($getChanges)) {
            unset($getChanges['updated_at']);
            foreach ($getChanges as $changes_k => $changes_v) {
                $data['data']['changes'][$changes_k] = [
                    'from' => $getOriginal[$changes_k],
                    'to' => $changes_v
                ];
            }
        }
        return $this->historics()->create($data);
        //return $this->historics()->UpdateOrCreate($data);
    }
    */

    protected function extractUserToHistory(array $data)
    {
        if (isset($data['user_id']) && is_int($data['user_id']) && User::find($data['user_id'])->exists()) {
            return $data['user_id'];
        }
        if (Auth::check()) {
            return Auth::id();
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public function historics()
    {
        return $this->morphMany('App\Historic', 'historicable');
    }

}
