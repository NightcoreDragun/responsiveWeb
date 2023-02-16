<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        return view('forms.post');
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();

        try {
            $post = $this->createPost($request);

            if ($request->hasFile('file')) {
                if (!$this->validateFileUpload($request)) {
                    DB::rollback();
                    return redirect()->back()->withErrors(['FileError' => 'File validation failed.']);
                }
                $this->storeFiles($request, $post);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Post and file uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            if (isset($post)) {
                $post->delete();
            }

            return redirect()->back()->withErrors(['error' => 'Could not save post and file.']);
        }
    }

    private function validateFileUpload(Request $request)
    {
        $validImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/tiff'];

        $files = $request->file('file');
        $totalSize = 0;

        foreach ($files as $file) {
            if (!$file->isValid()) {
                return false;
            }

            $totalSize += $file->getSize();

            if ($file->getSize() > 3000000) {
                return false;
            }
            if (!in_array($file->getMimeType(), $validImageMimeTypes)) {
                return false;
            }
        }

        if ($totalSize > 70000000) {
            return false;
        }

        return true;
    }

    private function createPost(Request $request)
    {
        $post = new Post();
        $post->commentaire = $request->input('message');
        $post->dateDeCreation = now();
        $post->dateDeModification = now();
        $post->save();

        return $post;
    }

    private function storeFiles(Request $request, Post $post)
    {
        foreach ($request->file('file') as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public', $fileName);
            if (!$path) {
                $post->delete();
                return redirect()->back()->withErrors(['error' => 'Could not save file.']);
            }

            $media = new Media();
            $media->nomFichierMedia = $fileName;
            $media->dateDeCreation = now();
            $media->typeMedia = $file->getClientMimeType();
            $media->post()->associate($post);
            $media->save();
        }
    }
}
