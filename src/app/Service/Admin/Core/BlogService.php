<?php
namespace App\Service\Admin\Core;

use App\Enums\StatusEnum;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogService
{ 
    public $fileService;

     /**
     * 
     * @param FileService $customerService
     */

    public function __construct () {

        $this->fileService = new FileService;
    }

    public function findBlogByUid($uid) {

        return Blog::where('uid', $uid)->first();
    }

    public function getAllBlogs() {

        return Blog::search(['title'])
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function save($data) {

        $uid = null;
        if(array_key_exists('uid', $data)) {

            $uid = $data['uid'];
        }
        if(array_key_exists('image', $data)) {

            $data['image'] = $this->fileService->uploadFile($data['image'], 'blog_images', config('setting.file_path.blog_images')['path'],config('setting.file_path.blog_images')['size'], false);
        }
        Blog::updateOrCreate([
            'uid' => $uid
        ], $data);

        return [
            'success',
            $uid ? translate("Blog updated successfully") : translate("Blog created successfully"),
        ];
    }

    /**
     * 
     * @param string $uid
     *
     * @return array
     */
    public function deleteContact(string $uid): array {

        $blog = $this->findBlogByUid($uid);
        if($blog) {

            $blog->delete();
            $status  = 'success';
            $message = translate("Blog ").$blog->title.translate(' has been deleted successfully from admin panel');
        } else {

            $status  = 'error';
            $message = translate("Blog couldn't be found"); 
        }
        return [
            $status, 
            $message
        ];
    }

    public function statusUpdate($request) {
        
        try {
            $status  = true;
            $reload  = false;
            $message = translate('Blog status updated successfully');
            $blog = Blog::where("id",$request->input('id'))->first();
            $column  = $request->input("column");
            
            if($request->value == StatusEnum::TRUE->status()) {
                
                $blog->status = StatusEnum::FALSE->status();
                $blog->update();
            } else {

                $blog->status = StatusEnum::TRUE->status();
                $blog->update();
            } 

        } catch (\Exception $error) {

            $status  = false;
            $message = $error->getMessage();
        }

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }

    
}