<x-app-layout>
  <div class="flex pr-3 pl-3 items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Верификация анкет</h1>
  </div>

  <div class="bg-[#191919] rounded-2xl p-6">
    <h2 class="text-xl font-semibold mb-4">Ожидающие проверки</h2>

    @if(count($verifications) == 0)
      <div class="text-center py-8">
        <p class="text-gray-400">Нет ожидающих заявок на верификацию</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-gray-800">
              <th class="p-4 text-sm text-white text-left">ID</th>
              <th class="p-4 text-sm text-white text-left">Анкета</th>
              <th class="p-4 text-sm text-white text-left">Дата заявки</th>
              <th class="p-4 text-sm text-white text-left">Действия</th>
            </tr>
          </thead>
          <tbody>
            @foreach($verifications as $verification)
              <tr class="border-b border-gray-800">
                <td class="p-4 text-sm text-white">{{ $verification->id }}</td>
                <td class="p-4 text-sm text-white">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full overflow-hidden mr-3 bg-gray-800">
                      @if($verification->profile->primaryImage)
                        <img src="{{ asset('storage/' . $verification->profile->primaryImage->path) }}" alt="Profile" class="w-full h-full object-cover">
                      @endif
                    </div>
                    <div>
                      <div>{{ $verification->profile->name }}, {{ $verification->profile->age }}</div>
                      <div class="text-xs text-gray-400">ID: {{ $verification->profile->id }}</div>
                    </div>
                  </div>
                </td>
                <td class="p-4 text-sm text-white">{{ $verification->created_at->format('d.m.Y H:i') }}</td>
                <td class="p-4 text-sm text-white">
                  <a href="{{ route('admin.verifications.show', $verification->id) }}" class="px-4 py-2 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-md text-sm transition">
                    Просмотреть
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $verifications->links() }}
      </div>
    @endif
  </div>
</x-app-layout>