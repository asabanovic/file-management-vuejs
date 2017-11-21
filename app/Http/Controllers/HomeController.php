<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileManagement\Props;
use App\FileManagement\Repositories\Attachment\AttachmentRepository;
use Auth;
use Exception;

class HomeController extends Controller
{	
    /**
     * AttachmentRepository instance
     *
     * @var App\FileManagement\Repositories\AttachmentRepository
     **/
    protected $attachmentRepo;

    public function __construct(AttachmentRepository $attachmentRepo)
    {
        $this->attachmentRepo = $attachmentRepo;
    }

	/**
	 * Load homepage
	 * 
	 * @param  Request $request 
	 * @return view           
	 */
    public function index(Request $request)
    {	
    	$props = Props::get();

    	return view('home', ['props' => $props]);
    }

    /**
     * Delete an attachment
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function deleteAttachment(Request $request)
    {
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $attachment_id = $request->input('attachment_id');

            if (!$attachment_id) {
                throw new Exception("You are trying to delete a non-existing file", 1);
            }

            $attachment = $this->attachmentRepo->delete(intval($attachment_id));

            return response()->json(array(
                'success' => true,
                'data' => $attachment,
                'errors' => []
            ), 200);

        } catch(\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }

    /**
     * Pull all the attachments for the logged in user
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function pullAttachments(Request $request)
    {   
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $user_id = Auth::user()->id;

            $attachments = $this->attachmentRepo->where('user_id' ,$user_id)->with('category')->orderBy('created_at', 'DESC')->all();

            return response()->json(array(
                'success' => true,
                'data' => $attachments,
                'errors' => []
            ), 200);

        } catch(\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }

    /**
     * Store files locally
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function store(Request $request)
    {
    	try {

            $attachments = $this->processAttachments($request);
            
            if (count($attachments) > 0) {
                $this->attachmentRepo->saveInBulk($attachments);
            }

    		return response()->json(array(
                'success' => true,
                'data' => [],
                'errors' => []
            ), 200);

    	} catch(\Exception $e) {
    		return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e
            ), 200);
    	}
    }

    /**
     * We are categorizing uploaded files. Since we can't attach data to immutable javascript file objects, we have to
     * send a nested array where the second array (with key = 1) will hold custom data, such as category ID of that file
     * 
     * @param  Request $request 
     * @return array          
     */
    public function processAttachments($request)
    {
        $attachments_input = $request->input('attachments');
        $attachments_files = $request->file('attachments');
        $attachments = [];

        if (count($attachments_files)) {
            foreach ($attachments_files as $key => $value) {
                $category_id = $attachments_input[$key][1] != 'undefined' ? $attachments_input[$key][1] : NULL;
                $value[0]->category_id = $category_id;
                array_push($attachments, $value[0]);
            }
        }

        return $attachments;
    }

    /**
     * Retrieve a full list of categories
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function getCategories(Request $request)
    {   
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $user_id = Auth::user()->id;

            $categories = $this->attachmentRepo->categories($user_id);

            return response()->json(array(
                'success' => true,
                'data' => $categories,
                'errors' => []
            ), 200);

        } catch(\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }
}
