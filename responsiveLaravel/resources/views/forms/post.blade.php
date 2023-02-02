@extends('layouts.app')

@section('content')
    <div class="w-full max-w-xl mx-auto p-4 rounded-lg bg-white border border-gray-300">
        <form method="POST" action="{{ url('post') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="message" class="block font-medium text-gray-700 mb-2">Message:</label>
                <textarea class="form-control h-48 p-2 resize-none bg-white border border-gray-400" id="message" name="message"></textarea>
            </div>
            <div class="form-group">
                <label for="file" class="block font-medium text-gray-700 mb-2">Upload file:</label>
                <input type="file" class="form-control-file" id="file" name="file[]" accept="image/*" multiple
                    max="3000000">
            </div>
            <button type="submit"
                class="btn bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">Publish</button>
        </form>
        @error('FileExceed')
            <p>{{ $message }}</p>
        @enderror
    </div>
@stop
