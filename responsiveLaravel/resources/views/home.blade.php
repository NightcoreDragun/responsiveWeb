@extends('layouts.app')

@section('content')
    <section class="container mx-auto my-10 flex flex-col sm:flex-row sm:justify-center">
        <div class="flex-shrink-0 sm:w-32 sm:h-32">
            <img src="{{ asset('assets/img/Profile.jpg') }}" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto">
        </div>
        <div class="ml-4 rounded-lg border-2 border-gray-800 p-4 sm:ml-0 sm:mt-4">
            <h2 class="text-lg font-medium">Bio</h2>
            <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, enim vel iaculis
                aliquet, ex augue convallis velit, vel malesuada velit velit vitae nibh. </p>
        </div>
    </section>
    @if (count($posts) > 0)
        @foreach ($posts as $post)
            <div class="w-full max-w-xl mx-auto p-4 rounded-lg bg-white border border-gray-300">
                <h3>{{ $post->commentaire }}</h3>
                @if (count($post->media) > 0)
                    @foreach ($post->media as $media)
                        @if ($media->typeMedia == 'video/mp4')
                            <video controls autoplay loop width="640" height="360">
                                <source src="{{ asset('storage/' . $media->nomFichierMedia) }}" type="video/mp4">
                            </video>
                        @elseif($media->typeMedia == 'image/png' || $media->typeMedia == 'image/jpeg')
                            <img src="{{ asset('storage/' . $media->nomFichierMedia) }}"
                                alt="{{ $media->nomFichierMedia }}">
                        @elseif ($media->typeMedia == 'audio/mpeg')
                            <audio controls>
                                <source src="{{ asset('storage/' . $media->nomFichierMedia) }}" type="audio/mpeg">
                            </audio>
                        @endif
                    @endforeach
                @endif
                <form action="{{route('post.destroy', $post)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete   </button>
                </form>
                <button type="submit" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">Edit</button>

            </div>
        @endforeach
    @else
        <p>No data to display</p>
    @endif
@stop
