@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Настройки Hero секции для конкретных моделей</h1>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Выберите модель для настройки Hero секции</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hero-section-override.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="model_type_selector">Тип модели</label>
                            <select name="model_type" id="model_type_selector" class="form-control select2">
                                <option value="">-- Выберите тип --</option>
                                <option value="CustomCategory" {{ request('model_type') == 'CustomCategory' ? 'selected' : '' }}>Кастомная категория</option>
                                <option value="Service" {{ request('model_type') == 'Service' ? 'selected' : '' }}>Услуга</option>
                                <!-- Add other filterable models here if needed -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="model_id_selector">Конкретная модель</label>
                            <select name="model_id" id="model_id_selector" class="form-control select2">
                                <option value="">-- Сначала выберите тип --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Загрузить</button>
                    </div>
                </div>
            </form>

            @if($model && $heroOverride)
            <h5>Настройка для: {{ $model->name ?? $model->title }} ({{ ucfirst($modelType) }} ID: {{ $modelId }})</h5>
            <form action="{{ route('admin.hero-section-override.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="model_type" value="{{ $modelType }}">
                <input type="hidden" name="model_id" value="{{ $modelId }}">

                <div class="form-group">
                    <label for="title">Заголовок (оставьте пустым для значения по умолчанию)</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $heroOverride->title) }}">
                </div>

                <div class="form-group">
                    <label for="text_content">Текстовое содержимое (оставьте пустым для значения по умолчанию)</label>
                    <textarea name="text_content" id="text_content" class="form-control summernote">{{ old('text_content', $heroOverride->text_content) }}</textarea>
                </div>

                <div class="form-group">
                    @if($heroOverride->image)
                    <img src="{{ asset($heroOverride->image) }}" width="200px" alt="hero image override">
                    <br>
                    @endif
                    <label for="image">Изображение (оставьте пустым для значения по умолчанию)</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <input type="hidden" class="form-control" name="old_image" value="{{ $heroOverride->image }}">
                </div>

                <div class="form-group">
                    <div class="control-label">Статус</div>
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="is_active" class="custom-switch-input" {{ $heroOverride->is_active ? 'checked' : '' }} value="1">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Активно (если выключено, будет использовано значение по умолчанию)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Обновить переопределение</button>
            </form>
            @elseif(request('model_type') && request('model_id'))
                <p class="text-danger">Не удалось найти указанную модель или ее переопределение.</p>
            @else
                <p>Пожалуйста, выберите тип модели и конкретную модель для настройки.</p>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    const modelTypeSelector = $('#model_type_selector');
    const modelIdSelector = $('#model_id_selector');
    const initialModelType = '{{ request("model_type") }}';
    const initialModelId = '{{ request("model_id") }}';

    const modelsData = {
        CustomCategory: @json($customCategories->mapWithKeys(function ($item) { return [$item->id => $item->name]; })->toArray()),
        Service: @json($services->mapWithKeys(function ($item) { return [$item->id => $item->name]; })->toArray()),
    };

    function populateModelIds(selectedType) {
        modelIdSelector.empty().append('<option value="">-- Выберите модель --</option>');
        if (selectedType && modelsData[selectedType]) {
            $.each(modelsData[selectedType], function(id, name) {
                modelIdSelector.append($('<option></option>').attr('value', id).text(name));
            });
            if (selectedType === initialModelType && initialModelId) {
                 modelIdSelector.val(initialModelId);
            }
        }
        modelIdSelector.trigger('change'); // for select2 update
    }

    modelTypeSelector.on('change', function() {
        populateModelIds($(this).val());
    });

    // Initial population if model_type is already selected (e.g. on page load with query params)
    if (initialModelType) {
        populateModelIds(initialModelType);
    }

    $('.select2').select2();

});
</script>
@endpush

@endsection