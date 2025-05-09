<x-app-layout>
  <div class="flex pr-3 pl-3 items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Верификация фото</h1>
    <a href="{{route('user.profiles.index')}}"
      class="px-6 py-2 border border-white-700 rounded-md text-white text-sm transition">
      Назад к анкетам
    </a>
  </div>

  <div class="bg-[#191919] rounded-2xl p-8 max-w-2xl mx-auto">
    <div class="mb-6">
      <h2 class="text-xl font-semibold mb-2">Верификация анкеты #{{ $profile->id }}</h2>
      <p class="text-gray-400 mb-4">Для верификации вашей анкеты, пожалуйста, загрузите фото, на котором вы держите лист бумаги с ID вашей анкеты.</p>
      <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <p class="text-white">Ваш ID: <span class="font-bold">{{ $profile->id }}</span></p>
      </div>
      
      @if(isset($verification) && $verification->status === 'rejected')
      <div class="bg-red-900/50 p-4 rounded-lg mb-4">
        <p class="text-white">Ваша предыдущая заявка на верификацию была отклонена. Пожалуйста, загрузите новое фото, соответствующее требованиям.</p>
      </div>
      @endif
    </div>

    @if(session('error'))
    <div class="bg-red-900 text-white p-4 rounded-lg mb-6">
      {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('user.profiles.verification.submit', $profile->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div class="mb-6">
        <label for="verification_photo" class="block text-white mb-2">Загрузите фото для верификации</label>
        <input type="file" id="verification_photo" name="verification_photo" accept="image/*"
          class="w-full bg-gray-800 text-white p-3 rounded-lg @error('verification_photo') border border-red-500 @enderror">
        @error('verification_photo')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="bg-gray-800 p-4 rounded-lg mb-6">
        <h3 class="font-semibold mb-2">Требования к фото:</h3>
        <ul class="list-disc pl-5 text-gray-400">
          <li>Вы должны быть четко видны на фото</li>
          <li>Лист бумаги с вашим ID должен быть хорошо читаем</li>
          <li>Фото должно быть хорошего качества и при хорошем освещении</li>
          <li>Максимальный размер файла: 5MB</li>
        </ul>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="px-6 py-3 bg-[#6340FF] hover:bg-[#5737e7] text-white rounded-xl text-sm transition">
          Отправить на проверку
        </button>
      </div>
    </form>

    @if($profile->verificationPhoto && $profile->verificationPhoto->status === 'pending')
    <div class="mt-6 bg-yellow-900/30 p-4 rounded-lg">
      <p class="text-yellow-400">Ваша заявка на верификацию находится на рассмотрении. Мы уведомим вас о результате проверки.</p>
    </div>
    @elseif($profile->verificationPhoto && $profile->verificationPhoto->status === 'rejected')
    <div class="mt-6 bg-red-900/30 p-4 rounded-lg">
      <p class="text-red-400">Ваша предыдущая заявка на верификацию была отклонена. Пожалуйста, загрузите новое фото, следуя требованиям.</p>
    </div>
    @endif
  </div>
</x-app-layout>