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
    // Show the post form
    public function index()
    {
        return view('forms.post');
    }

    // Handle the post form submission
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

    // Validate the uploaded files
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

    // Create a new post record in the database
    private function createPost(Request $request)
    {
        $post = new Post();
        $post->commentaire = $request->input('message');
        $post->dateDeCreation = now();
        $post->dateDeModification = now();
        $post->save();

        return $post;
    }

    /**
     * Store uploaded files for a post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return void
     */
    private function storeFiles(Request $request, Post $post)
    {
        $mediaData = [];

        // Loop through each uploaded file and save it to the filesystem and database
        foreach ($request->file('file') as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public', $fileName);

            // If file could not be saved, delete the post and return an error message
            if (!$path) {
                $post->delete();
                return redirect()->back()->withErrors(['error' => 'Could not save file.']);
            }

            // Save file information to mediaData array for later insertion to database
            $mediaData[] = [
                'nomFichierMedia' => $fileName,
                'dateDeCreation' => now(),
                'typeMedia' => $file->getClientMimeType(),
                'post_id' => $post->id,
            ];
        }

        // Insert mediaData array to the database
        Media::insert($mediaData);
    }

    /**
     * Delete a post and its media files.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
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

            // If everything succeeded, commit the transaction and redirect with success message
            DB::commit();
            return redirect()->back()->with('success', 'Post and file deleted successfully.');
        } catch (\Exception $e) {
            // If an exception was thrown during the transaction, rollback the transaction and redirect with error message
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Could not delete post and file.']);
        }
    }

    /**
     * Delete media files for a post.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    private function deleteFiles(Post $post)
    {
        $media = $post->media;

        // Loop through each media file and delete it from the filesystem and the database
        foreach ($media as $file) {
            Storage::delete('public/' . $file->nomFichierMedia);
            $file->delete();
        }
    }
}
