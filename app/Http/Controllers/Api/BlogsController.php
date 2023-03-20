<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use App\Repositories\Front\FrontRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogsController extends Controller
{
    /**
     * Constructor Method.
     *
     * @param  \App\Repositories\Front\FrontRepository $repository
     *
     */
    public function __construct(FrontRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {

        $tagz = '';
        $tags = null;
        $name = Post::pluck('tags')->toArray();
        foreach($name as $nm)
        {
            $tagz .= $nm.',';
        }
        $tags = array_unique(explode(',',$tagz));

        if(Setting::first()->is_blog == 0) return response()->json([
            'status' => false,
            'message' => 'Bạn chưa mở hiển thị blogs',
            'data' => [],
            'code' => 305
        ], 305);;

        return response()->json([
            'status' => false,
            'message' => 'Tải danh sách bài viết thành công!!!',
            'data' => [
                'posts' => $this->repository->displayPosts($request),
                'recent_posts'       => Post::orderby('id','desc')->take(4)->get(),
                'categories' => \App\Models\Bcategory::withCount('posts')->whereStatus(1)->get(),
                'tags'       => array_filter($tags)
            ],
            'code' => 305
        ]);
    }
}
