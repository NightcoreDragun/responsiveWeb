<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>

<body>
    <header class="bg-black">
      <nav class="container mx-auto flex items-center justify-between p-4">
        <a href="#" class="flex items-center">
          <img src="{{ asset('assets/img/Logo.png') }}" alt="Logo" class="w-15 h-10">
        </a>
        <div class="flex">
          <a href="#" class="px-4 text-white">Home</a>
          <a href="#" class="px-4 text-white">Post</a>
        </div>
      </nav>
    </header>
    <section class="container mx-auto my-10 flex flex-col sm:flex-row sm:justify-center">
      <div class="flex-shrink-0 sm:w-32 sm:h-32">
        <img src="{{asset('assets/img/Profile.jpg')}}" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto">
      </div>
       <div class="ml-4 rounded-lg border-2 border-gray-800 p-4 sm:ml-0 sm:mt-4">
      <h2 class="text-lg font-medium">Bio</h2>
      <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, enim vel iaculis aliquet, ex augue convallis velit, vel malesuada velit velit vitae nibh. </p>
    </div>
    </section>
  </body>
  </html>
