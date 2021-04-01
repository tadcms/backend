<?php

namespace Tadcms\Backend\Controllers;

use Tadcms\System\Models\User;
use Tadcms\System\Models\TadNotify;

class NotificationController extends BackendController
{
    public function index() {
        
        
        return view('tadcms::backend.notification.index', [
            'title' => trans('tadcms::app.notification')
        ]);
    }
    
    public function getData() {
        $search = $this->request->get('search');
        $status = $this->request->get('status');
        
        $sort = $this->request->get('sort', 'id');
        $order = $this->request->get('order', 'desc');
        $offset = $this->request->get('offset', 0);
        $limit = $this->request->get('limit', 20);
        
        $query = TadNotify::query();
        
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('subject', 'like', '%'. $search .'%');
            });
        }
        
        if (!is_null($status)) {
            $query->where('status', '=', $status);
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->created = $row->created_at->format('H:i Y-m-d');
            $row->edit_url = route('admin.notification.edit', ['id' => $row->id]);
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function form($id = null) {
        $model = TadNotify::firstOrNew(['id' => $id]);
        $users = User::whereIn('id', explode(',', $model->users))
            ->get(['id', 'name']);
        return view('tadcms::backend.notification.form', [
            'title' => $model->name ?: trans('app.add-new'),
            'model' => $model,
            'users' => $users,
        ]);
    }
    
    public function save() {
        $this->validateRequest([
            'name' => 'required|string|max:250',
            'subject' => 'required|string|max:300',
            'content' => 'required',
            'type' => 'required|in:1,2,3',
        ], $this->request, [
            'name' => trans('app.name'),
            'subject' => trans('app.subject'),
            'content' => trans('app.content'),
            'type' => trans('app.type'),
        ]);
        
        $users = $this->request->post('users');
        $model = TadNotify::firstOrNew(['id' => $this->request->post('id')]);
        $model->fill($this->request->all());
        
        if (empty($users)) {
            $model->users = null;
        }
        else {
            $model->users = implode(',', $users);
        }
        
        $model->save();
        
        return response()->json([
            'status' => 'success',
            'message' => trans('app.saved_successfully'),
            'redirect' => route('admin.notification'),
        ]);
    }
    
    public function remove() {
        $this->validateRequest([
            'ids' => 'required',
        ], $this->request, [
            'ids' => trans('app.notification')
        ]);
        
        TadNotify::destroy($this->request->post('ids'));
        
        return response()->json([
            'status' => 'success',
            'message' => trans('app.deleted_successfully'),
        ]);
    }
}
