<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
use App\Http\Response\MessageResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository extends BaseAccountRepository
{
    /**
     * @var Model|Authenticatable
     */
    protected $model;

    /**
     * @var array Searchable field dan html element
     */
    protected static $searchable = [];

    /**
     * Base repository Constructor
     *
     * @param Application $app
     * @param string $messageResponseKey
     * @throws Exception
     */
    public function __construct(Application $app, string $messageResponseKey = 'Data')
    {
        parent::__construct($app);
        $model = $this->app->make($this->model());
        $this->model = $model;
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        $this->messageResponseKey = $messageResponseKey;
        $this->messageResponse = MessageResponse::getMessage($this->messageResponseKey);
    }

    /**
     * Build a query for retrieving all records
     *
     * @param array $search Search query;
     * @return Builder
     */
    protected function allQuery($search = []): Builder
    {
        $search = $this->prepareSearch($search);
        $query = $this->model->newQuery();
        $searchableKeys = array_keys($this::$searchable);

        if (count($search) > 1) {
            $requestCols = $search['col'];
            $requestComps = $search['comp'];
            $requestVals = $search['val'];

            foreach ($requestCols as $index => $column) {
                if (in_array($column, $searchableKeys)) {
                    $query = $this->search($query, $column, $requestComps[$index], $requestVals[$index]);
                }
            }
        }
        return $query;
    }

    /**
     * Build search query
     *
     * @param Builder $query Eloquent Query Builder
     * @param string $column Field name
     * @param string $comp Search comparison keyword
     * @param string $val Search value
     * @param bool $withTrashed Included soft deleted record. Default is false
     * @return Builder
     */
    protected function search(Builder $query, string $column, string $comparison, string $value, bool $withTrashed = false): Builder
    {
        switch ($comparison) {
            case 'like':
                $query->orWhere($column, 'like', $value);
                break;
            case 'gt':
                $query->orWhere($column, '>', $value);
                break;
            case 'gte':
                $query->orWhere($column, '>=', $value);
                break;
            case 'lt':
                $query->orWhere($column, '<', $value);
                break;
            case 'lte':
                $query->orWhere($column, '<=', $value);
                break;
            default:
                if (is_array($value)) $query->whereIn($column, $value);
                else $query->orWhere($column, '=', $value);
                break;
        }
        $query->withTrashed();
        return $query;
    }

    /**
     * Find record by where condition
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @param bool $firstResult False return as Builder. Default true return as Collection|null
     * @param bool $withTrashed Included soft deleted record. Default is false
     * @return Builder|Collection|null
     */
    protected function findBy(string $column, string|int|float $value, bool $firstResult = true, bool $withTrashed = false): Builder|Collection|null
    {
        $query = $this->model->newQuery();
        if ($withTrashed)
            $query->withTrashed();

        if ($firstResult)
            $query->firstWhere($column, $value);
        else
            $query->where($column, $value)->get();
        return $query;
    }

    /**
     * Find record in trashed data by where condition
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @param bool $firstResult False return as Builder. Default true return as Collection|null
     * @return Builder|Collection|null
     */
    protected function findByTrashed(string $column, string|int|float $value, bool $firstResult = true): Builder|Collection|null
    {
        $query = $this->model->newQuery();
        $query->onlyTrashed();
        if ($firstResult)
            return $query->firstWhere($value, $column);
        else
            return $query->where($value, $column)->get();
    }

    /**
     * Get all data
     *
     * @param array $search Searching column and keyword key
     * @param array $columns Output columns
     * @param int $count Data per page
     * @return BodyResponse
     */
    public function index(array $search = [], $columns = ['*'], $count = 0): BodyResponse
    {
        if ($count > 0) $this->perPage = $count;
        $data = $this->allQuery($search)->orderBy('created_at', 'desc')->paginate($this->getPerPage(), $columns);
        $body = new BodyResponse();
        $body->setBodyMessage($this->messageResponse['successGet']);
        $body->setBodyData($data);
        return $body;
    }

    /**
     * Get all trashed data
     *
     * @param array $search Searching column and keyword key
     * @param array $columns Output columns
     * @param int $count Data per page
     * @return BodyResponse
     */
    public function indexTrashed(array $search = [], $columns = ['*'], $count = 0): BodyResponse
    {
        if ($count > 0) $this->perPage = $count;
        $data = $this->allQuery($search)->onlyTrashed()->orderBy('created_at', 'desc')->paginate($this->getPerPage(), $columns);
        $body = new BodyResponse();
        $body->setBodyMessage($this->messageResponse['successGetTrashed']);
        $body->setBodyData($data);
        return $body;
    }

    /**
     * Create a new record
     *
     * @param array $input Data attribute
     * @param bool $author Create data with author status
     * @return BodyResponse
     */
    public function create(array $input, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        if ($author) $this->insertAuthor(true, $input);

        $this->model = $this->model->newInstance($input);
        $this->model->save();
        $body->setBodyMessage($this->messageResponse['successCreated']);
        $body->setBodyData($this->model);
        return $body;
    }

    /**
     * Get record by where condition included trashed data
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @return BodyResponse
     */
    public function getFull(string $column, string|int|float $value): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value, true, true);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);

        $body->setBodyMessage($this->messageResponse['successGet']);
        $body->setBodyData($model);
        return $body;
    }

    /**
     * Get record by where condition
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @return BodyResponse
     */
    public function get(string $column, string|int|float $value): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);

        $body->setBodyMessage($this->messageResponse['successGet']);
        $body->setBodyData($model);
        return $body;
    }

    /**
     * Get record in trashed data by where condition
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @return BodyResponse
     */
    public function getTrashed(string $column, string|int|float $value): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findByTrashed($column, $value);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);

        $body->setBodyMessage($this->messageResponse['successGetTrashed']);
        $body->setBodyData($model);
        return $body;
    }

    /**
     * Update a record by where condition
     *
     * @param array $input Input value to be update
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @param bool $author Update data with author status
     * @return BodyResponse
     */
    public function updateBy(array $input, string $column, string|int|float $value, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);
        if ($author) $this->insertAuthor(true, $model);

        $model->fill($input);
        $model->save();

        $body->setBodyMessage($this->messageResponse['successUpdated']);
        $body->setBodyData($model);
        return $body;
    }

    /**
     * Restore a record from trash by where condition
     *
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @param bool $author Create data with author status
     * @return BodyResponse
     */
    public function restoreBy(string $column, string|int|float $value, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findByTrashed($column, $value);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);
        if ($author) $this->insertAuthor(true, $model);

        $model->restore();

        $body->setBodyData($model);
        $body->setBodyMessage($this->messageResponse['successRestored']);
        return $body;
    }

    /**
     * Delete a record by where condition
     *
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @param bool $author Create data with author status
     * @return BodyResponse
     */
    public function deleteBy(string $column, string|int|float $value, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if (!$model) $body->setResponseNotFound($this->messageResponseKey);
        if ($author) $this->insertAuthor(false);

        $model->delete();
        $body->setBodyMessage($this->messageResponse['successDeleted']);
        return $body;
    }

    /**
     * Permanent delete a deleted record by where condition
     *
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @return BodyResponse
     */
    public function permanentDeleteBy(string $column, string|int|float $value): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if ($model === null) {
            $body->setResponseNotFound($this->messageResponseKey);
        } else {
            $model->forceDelete();
        }
        $body->setBodyMessage($this->messageResponse['successPermanentDeleted']);
        return $body;
    }

    /** ================================= Support Function Below ================================= */
    /**
     * Set model class
     * @return Model
     */
    abstract public function model();

    /**
     * Remapping search input request
     *
     * @param array $input
     * @param array $skippedKey
     * @return array
     */
    private function prepareSearch(array $input, $skippedKey = []): array
    {
        // remove default key
        $defaultData = ['count', 'page', '_token'];
        foreach ($defaultData as $item) {
            unset($input[$item]);
        }
        foreach ($skippedKey as $item) {
            unset($input[$item]);
        }
        if (count($input) < 1) return ['*'];
        if (!key_exists('col', $input)) return $input;
        return $input;
    }

    /**
     * Get per page option
     *
     * @return int
     */
    protected function getPerPage()
    {
        if ($this->perPage === null) {
            $this->perPage = config('database.perPage');
        }
        return $this->perPage;
    }

    /**
     * Insert author if exists
     * @param bool $isUpdate Is query will call update function. If false it will update model automatically
     * @param array $input Input reference
     * @return void 
     */
    protected function insertAuthor(bool $isUpdate = false, array|Model|Collection &$input = []): void
    {
        if ($isUpdate) {
            $this->model->update(['author_id' => $this->currentAccount()->id]);
            return;
        }
        if (!key_exists('author_id', $this->model->getFillable())) return;

        if (is_array($input)) $input['author-id'] = $this->currentAccount()->id;
        else $input->author_id = $this->currentAccount()->id;
    }
}
