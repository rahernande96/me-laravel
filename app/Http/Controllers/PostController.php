<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\Country;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $cover_image;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('category')->paginate(20);
        
        return view('posts.index',[
            'posts'=>$posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        
        $countries = Country::all();

        return view('posts.create',[
            'categories'=>$categories,
            'countries'=>$countries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'summary'=>'required|string',
            'img'=>'required|file',
            'category'=>'required|numeric|exists:categories,id',
            'country'=>'required|numeric|exists:countries,id',
        ]);

        $post = Post::create([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'summary'=>$request->input('summary'),
            'cover_image'=>$request->file('img')->store('cover_img_post','public'),
            'category_id'=>$request->input('category'),
            'country_id'=>$request->input('country'),
        ]);

        return redirect()->route('admin.post.index')->with('success','Post creado con Exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('category','country')->where('id',$id)->first();

        if (!$post) 
        {
            return abort(404);
        }

        return view('posts.show',[
            'post'=>$post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        
        $countries = Country::all();

        $post = Post::with('category')->where('id',$id)->first();

        if (!$post) 
        {
            return abort(404);
        }

        return view('posts.edit',[
            'post'=>$post,
            'categories'=>$categories,
            'countries'=>$countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'summary'=>'required|string',
            'img'=>'file',
            'category'=>'required|numeric|exists:categories,id',
            'country'=>'required|numeric|exists:countries,id',
        ]);

        $post = Post::where('id',$id)->first();

        $this->cover_image = $post->cover_image;

        if (!$post) 
        {
            return abort(404);
        }

        if ($request->file('img')) 
        {
            $this->cover_image = $request->file('img')->store('cover_img_post','public');
        }

        $post->update([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'summary'=>$request->input('summary'),
            'cover_image'=>$this->cover_image,
            'category_id'=>$request->input('category'),
            'country_id'=>$request->input('country'),
        ]);

        return redirect()->route('admin.post.show',$post->id)->with('success','Post actualizado con exito!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id',$id)->first();

        if (!$post) 
        {
            return abort(404);
        }


        $post->delete();

        return redirect()->route('admin.post.index')->with('success','Eliminado Exitosamente');

        
    }

    public function ajaxImg(Request $request)
    {
        $request->validate([
            'image'=>'file|required'
        ]);

        $url = $request->file('image')->store('img_posts','public');



        return \Response::json(asset('storage/'.$url),200);
    }
}