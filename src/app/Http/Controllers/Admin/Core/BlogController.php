<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Service\Admin\Core\BlogService;
use App\Service\Admin\Core\FileService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Traits\ModelAction;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    use ModelAction;
    public $blogService;
    public $fileService;
    public function __construct() {

        $this->fileService = new FileService;
        $this->blogService = new BlogService($this->fileService);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {
        
        Session::put("menu_active", false);
        $title = translate("Blog List");
        $blogs = $this->blogService->getAllBlogs();
        return view('admin.blog.index', compact('title', 'blogs'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function create(): View {
        
        Session::put("menu_active", false);
        $title = translate("Create Blog");
        return view('admin.blog.create', compact('title'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function edit($uid): View {
        
        Session::put("menu_active", false);
        $title = translate("Update Blog");
        $blog = $this->blogService->findBlogByUid($uid);
        return view('admin.blog.edit', compact('title', 'blog'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function save(BlogRequest $request) {
        
        $status  = 'error';
        $message = 'Something went wrong';
        
        try {
            $data = $request->toArray();
            unset($data['_token']);
            list($status, $message) = $this->blogService->save($data);
        } catch (\Exception $e) {

            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

     /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws ValidationException If the validation fails.
     * 
     */
    public function statusUpdate(Request $request) {
        
        try {

            $this->validate($request,[

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]); 

            $notify = $this->blogService->statusUpdate($request);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                'status'  => false,
                'message' => $$validation->errors()
            ]);
        } 
    }

    /**
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function bulk(Request $request) :RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {

            list($status, $message) = $this->bulkAction($request, null, [
                "model" => new Blog(),
            ]);
    
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

     /**
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request) {
        
        $status  = 'error';
        $message = 'Something went wrong';

        try {

            list($status, $message) = $this->blogService->deleteContact($request->input('uid'));

        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

}
