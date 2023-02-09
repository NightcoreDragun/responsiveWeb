@extends("layouts.app")

@section("content")
<section class="container mx-auto my-10 flex flex-col sm:flex-row sm:justify-center">
    <div class="flex-shrink-0 sm:w-32 sm:h-32">
      <img src="{{asset('assets/img/Profile.jpg')}}" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto">
    </div>
     <div class="ml-4 rounded-lg border-2 border-gray-800 p-4 sm:ml-0 sm:mt-4">
    <h2 class="text-lg font-medium">Bio</h2>
    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, enim vel iaculis aliquet, ex augue convallis velit, vel malesuada velit velit vitae nibh. </p>
  </div>
  </section>
  @if(count($posts) > 0)
    @foreach($posts as $post)
        <h3>{{ $post->commentaire }}</h3>
        @if(count($post->media) > 0)
            @foreach($post->media as $media)
            <img src="{{ asset('storage/' . $media->nomFichierMedia) }}" alt="{{ $media->nomFichierMedia }}">
            @endforeach
        @endif
    @endforeach
@else
    <p>No data to display</p>
@endif
@stop
