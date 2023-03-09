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
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Create the post record in the database
            $post = $this->createPost($request);

            // If media files were uploaded with the form, validate and store them
            if ($request->hasFile('file')) {
                if (!$this->validateFileUpload($request)) {
                    // If file validation failed, rollback the transaction and redirect back to the form with an error message
                    DB::rollback();
                    return redirect()->back()->withErrors(['FileError' => 'File validation failed.']);
                }
                $this->storeFiles($request, $post);
            }


            // If everything succeeded, commit the transaction and redirect back to the form with a success message
            DB::commit();
            return redirect()->back()->with('success', 'Post and file uploaded successfully.');

        } catch (\Exception $e) {
            // If an exception was thrown during the transaction, rollback the transaction and delete the post record if it was created
            DB::rollback();

            if (isset($post)) {
                $post->delete();
            }

            // Redirect back to the form with an error message
            return redirect()->back()->withErrors(['error' => 'Could not save post and file. Please be sure to provide a message']);
        }
    }


    private function validateFileUpload(Request $request)
    {
        $maxFileSize = 3000000;
        $totalMaxSize = 70000000;
        $validMimeTypes = ['image/jpeg', 'image/png', 'video/mp4', 'audio/mpeg'];

        $files = $request->file('file');
        $totalSize = 0;

        foreach ($files as $file) {
            if (!$file->isValid()) {
                return false;
            }

            $totalSize += $file->getSize();

            if ($file->getSize() > $maxFileSize) {
                return false;
            }
            if (!in_array($file->getMimeType(), $validMimeTypes)) {
                return false;
            }
        }

        if ($totalSize > $totalMaxSize) {
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
        $mediaData = [];

        foreach ($request->file('file') as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public', $fileName);
            if (!$path) {
                $post->delete();
                return redirect()->back()->withErrors(['error' => 'Could not save file.']);
            }

            $mediaData[] = [
                'nomFichierMedia' => $fileName,
                'dateDeCreation' => now(),
                'typeMedia' => $file->getClientMimeType(),
                'post_id' => $post->id,
            ];
        }

        Media::insert($mediaData);
    }

    public function destroy(Post $post)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // If the post has any media files, delete them from the filesystem and the database
            if ($post->media->count()) {
                $this->deleteFiles($post);
            }

            // Delete the post record from the database
            $post->delete();

            // If everything succeeded, commit the transaction
            DB::commit();

            // Redirect back to the home page with a success message
            return redirect()->back()->with('success', 'Post and file deleted successfully.');

        } catch (\Exception $e) {
            // If an exception was thrown during the transaction, rollback the transaction
            DB::rollback();

            // Redirect back to the home page with an error message
            return redirect()->back()->withErrors(['error' => 'Could not delete post and file.']);
        }
    }

    private function deleteFiles(Post $post)
    {
        $media = $post->media;
        foreach ($media as $file) {
            Storage::delete('public/' . $file->nomFichierMedia);
            $file->delete();
        }
    }



}
