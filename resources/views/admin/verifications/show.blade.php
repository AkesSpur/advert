<x-app-layout>
  <div class="flex pr-3 pl-3 items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Проверка верификации</h1>
    <a href="{{route('admin.verifications.index')}}"
      class="px-6 py-2 border border-white-700 rounded-md text-white text-sm transition">
      Назад к списку
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Profile Information -->
    <div class="bg-[#191919] rounded-2xl p-6">
      <h2 class="text-xl font-semibold mb-4">Информация об анкете</h2>
      
      <div class="flex items-center mb-6">
        <div class="w-16 h-16 rounded-full overflow-hidden mr-4 bg-gray-800">
          @if($verification->profile->primaryImage)
            <img src="{{ asset('storage/' . $verification->profile->primaryImage->path) }}" alt="Profile" class="w-full h-full object-cover">
          @endif
        </div>
        <div>
          <div class="text-lg font-medium">{{ $verification->profile->name }}, {{ $verification->profile->age }}</div>
          <div class="text-sm text-gray-400">ID анкеты: {{ $verification->profile->id }}</div>
          <div class="text-sm text-gray-400">Дата создания: {{ $verification->profile->created_at->format('d.m.Y') }}</div>
        </div>
      </div>
      
      <div class="mb-6">
        <h3 class="font-medium mb-2">Контактная информация</h3>
        <div class="text-sm text-gray-400">
          @if($verification->profile->email)
            <div class="mb-1">Email: {{ $verification->profile->email }}</div>
          @endif
          @if($verification->profile->phone)
            <div class="mb-1">Телефон: {{ $verification->profile->phone }}</div>
          @endif
        </div>
      </div>
      
      <div>
        <h3 class="font-medium mb-2">Заявка на верификацию</h3>
        <div class="text-sm text-gray-400">
          <div class="mb-1">Дата заявки: {{ $verification->created_at->format('d.m.Y H:i') }}</div>
          <div class="mb-1">Статус: 
            @if($verification->status === 'pending')
              <span class="text-yellow-400">На рассмотрении</span>
            @elseif($verification->status === 'approved')
              <span class="text-green-400">Одобрено</span>
            @elseif($verification->status === 'rejected')
              <span class="text-red-400">Отклонено</span>
            @endif
          </div>
        </div>
      </div>
    </div>
    
    <!-- Verification Photo -->
    <div class="bg-[#191919] rounded-2xl p-6">
      <h2 class="text-xl font-semibold mb-4">Фото для верификации</h2>
      
      <div class="mb-6">
        <div class="bg-gray-800 rounded-lg overflow-hidden">
          <img src="{{ asset('storage/' . $verification->photo_path) }}" alt="Verification Photo" class="w-full h-auto max-h-[500px] object-contain">
        </div>
        <div class="text-sm text-gray-400 mt-2">
          Пользователь должен держать лист бумаги с ID анкеты: {{ $verification->profile->id }}
        </div>
      </div>
      
      @if($verification->status === 'pending')
        <div class="flex space-x-4">
          <form action="{{ route('admin.verifications.process', $verification->id) }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="action" value="approve">
            <button type="submit" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
              Одобрить верификацию
            </button>
          </form>
          
          <form action="{{ route('admin.verifications.process', $verification->id) }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="action" value="reject">
            <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
              Отклонить верификацию
            </button>
          </form>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>