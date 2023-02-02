<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('forms.post');
    }


    public function submit(Request $request)
    {
        $totalSize = 0;
        $files = $request->file('file');

        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }

        if ($totalSize > 70000000) {
            return redirect()->back()->withErrors(['FileExceed' => 'Total file size exceeds 70MB.']);
        }

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('public');
            }
        }
    }
}
