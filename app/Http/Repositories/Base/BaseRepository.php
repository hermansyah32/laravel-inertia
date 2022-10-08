<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
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
     * @return Model
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $model = $this->app->make($this->model());
        $this->model = $model;
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
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
     * @return Builder
     */
    protected function search(Builder $query, string $column, string $comparison, string $value): Builder
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
        return $query;
    }

    /**
     * Find record by where condition
     *
     * @param string|int|float $value Keyword value
     * @param string $column Where column
     * @param bool $firstResult False return as Builder. Default true return as Collection|null
     * @return Builder|Collection|null
     */
    protected function findBy(string|int|float $value, string $column, bool $firstResult = true): Builder|Collection|null
    {
        $query = $this->model->newQuery();
        if ($firstResult)
            return $query->firstWhere($column, $value);
        else
            return $query->where($column, $value);
    }

    /**
     * Find record in trashed data by where condition
     *
     * @param string|int|float $value Keyword value
     * @param string $column Where column
     * @param bool $firstResult False return as Builder. Default true return as Collection|null
     * @return Builder|Collection|null
     */
    protected function findByTrashed(string|int|float $value, string $column, bool $firstResult = true): Builder|Collection|null
    {
        $query = $this->model->newQuery();
        $query->onlyTrashed();
        if ($firstResult)
            return $query->firstWhere($value, $column);
        else
            return $query->where($value, $column);
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
        if ($author) {
            $input['author_id'] = $this->currentAccount()->id;
        }
        $body = new BodyResponse();
        $this->model = $this->model->newInstance($input);
        $this->model->save();
        $body->setBodyData($this->model);
        return $body;
    }

    /**
     * Show record by where condition
     *
     * @param string|int|float $value Keyword value
     * @param string $column Where column
     * @return BodyResponse
     */
    public function show(string|int|float $value, string $column): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if ($model === null) {
            $body->setResponseNotFound();
        } else {
            $body->setBodyData($model);
        }
        return $body;
    }

    /**
     * Show record in trashed data by where condition
     *
     * @param string|int|float $value Keyword value
     * @param string $column Where column
     * @return BodyResponse
     */
    public function showTrashed(string|int|float $value, string $column): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findByTrashed($column, $value);
        if ($model === null) {
            $body->setResponseNotFound();
        } else {
            $body->setBodyData($model);
        }
        return $body;
    }

    /**
     * Update a record by where condition
     *
     * @param array $input Input value to be store/updated
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @param bool $author Create data with author status
     * @return BodyResponse
     */
    public function updateBy(array $input, string $column, string|int|float $value, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        $model = $this->findBy($column, $value);
        if ($model === null) {
            $body->setResponseNotFound();
        } else {
            if ($author) {
                $input['author_id'] = $this->currentAccount()->id;
            }

            $model->fill($input);
            $model->save();
            $body->setBodyData($model);
        }
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
        if ($model === null) {
            $body->setResponseNotFound();
        } else {
            if ($author) {
                $input['author_id'] = $this->currentAccount()->id;
            }
            $input = ['deleted_at' => null];
            $model->fill($input);
            $model->save();
            $body->setBodyData($model);
        }
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
        if ($model === null) {
            $body->setResponseNotFound();
        } else {
            if ($author) {
                $input['author_id'] = $this->currentAccount()->id;
                $model->update($input);
            }
            $model->delete();
        }
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
            $body->setResponseNotFound();
        } else {
            $model->forceDelete();
        }
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
}
