<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        return view('forms.post');
    }

    public function submit(Request $request)
    {
        if (!$request->hasFile('file')) {
            return redirect()->back()->withErrors(['FileError' => 'No file provided.']);
        }

        if (!$this->validateFileUpload($request)) {
            return redirect()->back()->withErrors(['FileError' => 'File validation failed.']);
        }

        $post = $this->createPost($request);
        $this->storeFiles($request, $post);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    private function validateFileUpload(Request $request)
    {
        $validImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/tiff'];

        if (!$request->file('file')->isValid()) {
            return false;
        }

        $files = $request->file('file');
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += $file->getSize();
            if (!in_array($file->getMimeType(), $validImageMimeTypes)) {
                return false;
            }
            if ($file->getSize() > 3000000) {
                return false;
            }
            if ($totalSize > 70000000) {
                return false;
            }
        }

        return true;
    }

    private function createPost(Request $request)
    {
        return Post::create([
            'commentaire' => $request->input('message'),
            'dateDeCreation' => now(),
            'dateDeModification' => now()
        ]);
    }

    private function storeFiles(Request $request, Post $post)
    {
        foreach ($request->file('file') as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public', $fileName);

            $media = new Media();
            $media->nomFichierMedia = $path;
            $media->dateDeCreation = now();
            $media->typeMedia = $file->getClientMimeType();
            $media->post()->associate($post);
            $media->save();
        }
    }
}
