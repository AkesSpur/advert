<x-app-layout>
  <div class="flex pr-3 pl-3 items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Анкеты</h1>
    <a href="{{route('user.form.index')}}"
      class="px-6 py-2 border border-white-700 rounded-md text-white text-sm transition">
      Добавить анкету
    </a>
  </div>

  @if(count($profiles) == 0)
    <!-- Empty state message -->
    <div class="bg-[#191919] rounded-2xl p-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24"
      stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    <h2 class="text-xl font-semibold mb-2">У вас пока нет анкет</h2>
    <p class="text-gray-400 mb-6">Создайте свою первую анкету, чтобы начать получать заказы</p>
    <a href="{{route('user.form.index')}}"
      class="px-6 py-3 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-xl text-sm transition inline-block">
      Создать анкету
    </a>
    </div>
  @else

    <!-- Tabs for desktop - now with pill-like edges -->
    {{-- <div class="bg-[#191919] rounded-2xl mb-6 hidden sm:flex px-2 py-1">
    <a href="#" class="px-8 py-3 text-white bg-[#6340FF] rounded-full mx-1">Основные данные</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Локация</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Просмотры</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Клики</a>
    <a href="#" class="px-8 py-3 text-[#C2C2C2] hover:bg-gray-800 rounded-full mx-1 transition">Реклама</a>
    </div> --}}


    <!-- Profiles list - Desktop -->
    <div class="p-3 hidden lg:block">

    <div class=" w-full">
      <table class="min-w-full table-auto">
      <thead>
        <tr class="bg-[#191919]">
        <th class="w-1/3 p-4 text-sm text-white text-left">Основные данные</th>
        <th class="w-1/3 p-4 text-sm text-white text-left">Локация</th>
        <th class="w-1/6 p-4 text-sm text-white text-center">Просмотры</th>
        <th class="w-1/6 p-4 text-sm text-white text-center">Клики</th>
        <th class="w-1/6 p-4 text-sm text-white text-left">Реклама</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($profiles as $profile)

      <tr class="border-b border-gray-800">
      <!-- Profile cell -->
      <td class="p-4 align-top">
        <div class="flex items-center">
        <div class="w-12 h-12 rounded-full overflow-hidden mr-3 bg-gray-800">
        <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
        class="w-full h-full object-cover">
        </div>
        <div>
        <div class="flex items-center gap-2">
        <span class="font-medium text-white">{{$profile->name}}, {{$profile->age}}</span>
        @if ($profile->is_active)
      <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
    @else
    <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
  @endif
        </div>
        <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
        </div>
        </div>
      </td>

      <!-- Location cell -->
      <td class="p-4 text-sm text-[#C2C2C2] align-top">
        <div class="flex items-center mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
        </svg>
        @foreach ($profile->neighborhoods as $key => $neighborhood)
      {{ $neighborhood->name}}
      @if ($key < count($profile->neighborhoods) - 1)
      ,
    @endif
    @endforeach
        </div>
        <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
        </svg>
        @foreach ($profile->metroStations as $key => $metroStation)
      м. {{ $metroStation->name }}
      @if ($key < count($profile->metroStations) - 1)
      ,
    @endif
    @endforeach
        </div>
      </td>

      <!-- Views cell -->
      <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
        {{$profile->views_count}}
      </td>

      <!-- Clicks cell -->
      <td class="p-4 text-sm text-[#C2C2C2] text-center align-top">
        {{$profile->clicks_count}}
      </td>

      <!-- Actions cell -->
      <td class="p-4 align-top">
        <div class="flex items-center justify-end space-x-3">
          @if ($profile->is_vip)
          <span class="px-2 py-1 text-sm text-gray-400">
            Премиум
          </span>
          @else          
          <span class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
            Рекламировать
          </span>
          @endif
        <form action="{{ route('user.profiles.destroy', $profile->id) }}" method="POST" class="inline"
        x-data="{}"
        @submit.prevent="if (confirm('Вы уверены, что хотите удалить этот профиль?')) $el.submit();">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        </button>
        </form>

        <a href="{{route('user.profiles.edit', $profile->id)}}" class="text-[#C2C2C2] hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        </a>
        </div>
      </td>
      </tr>

    @endforeach
      </tbody>
      </table>

      <!-- Archive link -->
      <div class="mt-5">
      <a href="#" class="text-[#C2C2C2] hover:text-white text-sm underline">Архив анкет (8)</a>
      </div>
    </div>
    </div>


    <!-- Profiles list - Mobile -->
    <div class="lg:hidden space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">

    @foreach ($profiles as $profile)

    <div class="bg-[#191919] rounded-2xl overflow-hidden">
      <div class="p-5">
      <div class="flex items-start justify-between">
      <div class="flex items-center">
      <div class="w-16 h-16 rounded-full overflow-hidden mr-3 bg-gray-800">
        <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile"
        class="w-full h-full object-cover">
      </div>
      <div>
        <div class="flex items-center gap-2">
        <span class="font-medium text-lg">{{$profile->name}}, {{$profile->age}}</span>
        @if ($profile->is_active)
      <span class="text-xs bg-[#5FD013] text-black px-2 py-0.5 rounded-md">Активна</span>
    @else
    <span class="text-xs bg-[#49494999] text-white px-2 py-0.5 rounded-md">Неактивна</span>
  @endif
        </div>
        <div class="text-xs text-gray-400">Анкета добавлена {{$profile->created_at->format('d.m.Y')}}</div>
      </div>
      </div>
      </div>

      <div class="mt-4 space-y-2 text-sm">
      <div class="flex items-center text-[#C2C2C2]">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
      </svg>
      @foreach ($profile->neighborhoods as $key => $neighborhood)
      {{ $neighborhood->name }}
      @if ($key < count($profile->neighborhoods) - 1)
      ,
    @endif
    @endforeach
      </div>
      <div class="flex items-center text-[#C2C2C2]">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 flex-shrink-0" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="#FF000099" stroke-width="2" />
        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="#FF000099"
        font-family="Arial, sans-serif">M</text>
      </svg>
      @foreach ($profile->metroStations as $key => $metroStation)
      м. {{ $metroStation->name }}
      @if ($key < count($profile->metroStations) - 1)
      ,
    @endif
    @endforeach
      </div>
      </div>

      <!-- Stats -->
      <div class="text-center mt-5 pb-5 border-b border-gray-800">
      <div class="flex justify-between mb-2">
      <div class="text-white">Просмотры</div>
      <div class="text-sm text-gray-400">{{$profile->views_count}}</div>
      </div>
      <div class="flex justify-between mb-2">
      <div class="text-white">Клики</div>
      <div class="text-sm text-gray-400">{{$profile->clicks_count}}</div>
      </div>
      <div class="flex justify-between mb-2">
      <div class="text-white">Реклама</div>
      @if ($profile->is_vip)
          <div class="text-sm text-gray-400">
            Премиум
          </div>
          @else          
          <div class="px-2 py-1 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded text-xs">
            Рекламировать
          </div>
          @endif
      </div>
      </div>


      <!-- Actions -->
      <div class="">
      <form action="{{ route('user.profiles.destroy', $profile->id) }}" method="POST" class="w-full" x-data="{}"
      @submit.prevent="if (confirm('Вы уверены, что хотите удалить этот профиль?')) $el.submit();">
      @csrf
      @method('DELETE')
      <button type="submit" class="w-full py-3 text-red-900 mb-2">
        Удалить анкету
      </button>
      </form>
      <a href="{{route('user.profiles.edit', $profile->id)}}"
      class="block w-full py-3 bg-[#6340FF] hover:bg-[#5737e7] rounded-xl text-white transition text-center">
      Редактировать
      </a>
      </div>
      </div>
    </div>

  @endforeach

    </div>

    <!-- Archive link - Mobile -->
    <div class="mt-4 lg:hidden">
    <a href="#" class="text-[#C2C2C2] hover:text-white text-sm">Архив анкет (8)</a>
    </div>
  @endif

</x-app-layout>