<?php

namespace Tadcms\Backend\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tadcms\Backend\Controllers\BackendController;
use Tadcms\Backend\Requests\PostRequest;
use Tadcms\System\Models\Post;
use Tadcms\System\Repositories\PostRepository;
use Tadcms\System\Repositories\TaxonomyRepository;

abstract class PostControllerAbstract extends BackendController
{
    protected $postRepository;
    protected $taxonomyRepository;
    protected $postType = 'posts';
    protected $postTypeSingular = 'post';
    protected $supports = [
        'category',
        'tag'
    ];

    public function __construct(
        PostRepository $postRepository,
        TaxonomyRepository $taxonomyRepository
    ) {
        $this->postRepository = $postRepository;
        $this->taxonomyRepository = $taxonomyRepository;
    }

    public function index()
    {
        return view('tadcms::post.index', [
            'title' => $this->getTitle(),
            'postType' => $this->postType,
            'supports' => $this->supports
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb([
            'title' => $this->getTitle(),
            'url' => route("admin.{$this->postType}.index"),
        ]);

        $model = new Post();
        return view('tadcms::post.form', [
            'model' => $model,
            'lang' => app()->getLocale(),
            'title' => trans('tadcms::app.add-new'),
            'postType' => $this->postType,
            'postTypeSingular' => $this->postTypeSingular,
            'supports' => $this->supports
        ]);
    }

    public function edit($id)
    {
        $this->addBreadcrumb([
            'title' => $this->getTitle(),
            'url' => route("admin.{$this->postType}.index"),
        ]);

        $model = $this->postRepository->findOrFail($id);
        $model->load(['translations', 'taxonomies']);
        $selectedTaxonomies = $model->taxonomies->pluck('id')->toArray();

        return view('tadcms::post.form', [
            'model' => $model,
            'lang' => app()->getLocale(),
            'title' => $model->title,
            'postType' => $this->postType,
            'postTypeSingular' => $this->postTypeSingular,
            'supports' => $this->supports,
            'selectedTaxonomies' => $selectedTaxonomies
        ]);
    }

    public function getDataTable(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Post::query();

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('description', 'like', '%'. $search .'%');
            });
        }

        $query->where('type', '=', $this->postTypeSingular);

        if ($status) {
            $query->where('status', '=', $status);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->thumb_url = $row->getThumbnail();
            $row->created = get_date($row->created_at);
            $row->edit_url = route("admin.{$this->postType}.edit", [$row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function store(PostRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->create(array_merge($request->all(), [
                'type' => $this->postTypeSingular
            ]));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $this->success(
            trans('tadcms::app.saved-successfully')
        );
    }

    public function update($id, PostRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->update($id, array_merge($request->all(), [
                'type' => $this->postTypeSingular
            ]));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $this->success(
            trans('tadcms::app.saved-successfully')
        );
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required',
        ]);

        $action = $request->post('action');
        $ids = $request->post('ids');

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'delete':
                    foreach ($ids as $id) {
                        $this->postRepository->delete($id);
                    }
                    break;
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $this->success(
            trans('tadcms::app.successfully')
        );
    }

    protected function getTitle()
    {
        return $this->label();
    }

    abstract protected function label() : string;
}
