<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', ['posts' => Post::all()]);
    }

    public function store(Request $request){
        //create an instance of the Post model and save the data
        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = rand(1, 5);
        $post->save();
        return redirect('/posts');
    }

    public function show($id){
        // the firstOrFail() method will throw an exception if the post is not found
        $post = Post::with('user')->where('id', $id)->firstorFail();
        return view('posts.show', ['post' => $post]);
    }

    public function edit($id){
        // the firstOrFail() method will throw an exception if the post is not found
        $post = Post::where('id', $id)->firstOrFail();
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $id){
        // the firstOrFail() method will throw an exception if the post is not found
        $post = Post::where('id', $id)->firstOrFail();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return redirect('/posts');
    }

    public function destroy($id){
        // the firstOrFail() method will throw an exception if the post is not found
        $post = Post::where('id', $id)->firstOrFail();
        $post->delete();
        return redirect('/posts');
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('landing')->with('success', 'Login successful!');
        } else {
            // Authentication failed...
            return redirect()->route('login')->with('error', 'Invalid credentials');
        }
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
    }
    public function registerUser(Request $request)
    {
        // Validate form data
        $validatedData = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
    
        // Create new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
    
        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}
