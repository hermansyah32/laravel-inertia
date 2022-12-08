<?php

namespace App\Http\API\Sync;

use App\Http\Controllers\BaseAPIController as Controller;
use App\Http\Repositories\SubjectContentRepository;
use App\Http\Repositories\SubjectGroupRepository;
use App\Http\Repositories\SubjectReferenceRepository;
use App\Http\Response\BodyResponse;
use App\Models\SubjectContent;
use App\Models\SubjectGroup;
use App\Models\SubjectReference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SyncContentController extends Controller
{
    /** @var integer */
    private $perPage = 15;

    /** @var SubjectGroupRepository */
    private $subjectGroupRepo;

    /** @var SubjectContentRepository */
    private $subjectContentRepo;

    /** @var SubjectReferenceRepository */
    private $subjectReferenceRepo;

    /** @var SubSubjectContentRepository */
    private $subSubjectContentRepo;

    /**
     * Class constructor
     * 
     * @return void 
     */
    public function __construct(
        SubjectGroupRepository $subjectGroupRepository,
        SubjectContentRepository $subjectContentRepository,
        SubjectReferenceRepository $subjectReferenceRepository,
    ) {
        $this->subjectGroupRepo = $subjectGroupRepository;
        $this->subjectContentRepo = $subjectContentRepository;
        $this->subjectReferenceRepo = $subjectReferenceRepository;
    }

    public function checkPermission($rule): bool|BodyResponse
    {
        try {
            if (!$this->repository->currentAccount()->can($rule)) throw new Exception('Permission denied');
            return true;
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }
    }

    public function permissionRule()
    {
        // Empty
    }


    public function sync(Request $request)
    {
        $input = $request->all();
        $input['page'] = $input['page'] ?? 1;
        $this->validation($input);

        $countSubjectGroup = SubjectGroup::where('updated_at', '>', $input['subjectGroup'])->count();
        $countSubjectContent = SubjectContent::where('updated_at', '>', $input['subjectContent'])->count();
        $countSubjectReference = SubjectReference::where('updated_at', '>', $input['subjectReference'])->count();

        // Get the most bigger count in pages
        $biggerPage = 1;
        if ($biggerPage < round($countSubjectGroup / $this->perPage))
            $biggerPage = round($countSubjectGroup / $this->perPage);
        if ($biggerPage < round($countSubjectContent / $this->perPage))
            $biggerPage = round($countSubjectContent / $this->perPage);
        if ($biggerPage < round($countSubjectReference / $this->perPage))
            $biggerPage = round($countSubjectReference / $this->perPage);

        $dataSubjectGroup = SubjectGroup::where('updated_at', '>', $input['subjectGroup'])
            ->skip(0)->take($this->perPage)->get();
        $dataSubjectContent = SubjectContent::where('updated_at', '>', $input['subjectContent'])
            ->skip(0)->take($this->perPage)->get();
        $dataSubjectReference = SubjectReference::where('updated_at', '>', $input['subjectReference'])
            ->skip(0)->take($this->perPage)->get();

        $result = [
            'currentPage' => 1,
            'data' => [],
            'first_page_url' => '',
            'from' => '',
            'last_page' => '',
            'last_page_url' => '',
            'next_page_url' => '',
            'path' => '',
            'per_page' => '',
            'prev_page_url' => '',
            'to' => '',
            'total' => $biggerPage,
        ];
    }

    /**
     * Sync data with page. Available only if data more than $perPage
     * @param Request $request 
     * @return void 
     */
    private function syncPages(Request $request)
    {
    }

    // ====================================== Helper Method ===============================
    private function validation(array $input)
    {
        $body = new BodyResponse();
        $rules = [
            'page' => ['required', 'integer'],
            'subjectContent' => ['required', 'datetime'],
            'subjectGroup' => ['required', 'datetime'],
            'subjectReference' => ['required', 'datetime'],
            'subSubjectContent' => ['required', 'datetime'],
        ];
        $messages = [];
        $attributes = [];

        $validator = Validator::make($input, $rules, $messages, $attributes);
        if ($validator->fails())
            return $this->sendResponse($body->setResponseValidationError($validator->errors(), $this->messageResponseKey));
    }
}
