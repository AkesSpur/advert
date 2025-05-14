@extends('admin.layouts.master') {{-- Main admin layout --}}

{{-- @section('title', 'Edit SEO Template for ' . ucfirst($pageType)) --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Редактирование SEO-шаблона для {{ ucfirst($pageType) }} Страница</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.seo_templates.update', $pageType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title_template">Шаблон названия</label>
                            <input type="text" name="title_template" id="title_template" class="form-control @error('title_template') is-invalid @enderror" value="{{ old('title_template', $template->title_template) }}">
                            @error('title_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_description_template">Шаблон метаописания</label>
                            <textarea name="meta_description_template" id="meta_description_template" class="form-control @error('meta_description_template') is-invalid @enderror" rows="3">{{ old('meta_description_template', $template->meta_description_template) }}</textarea>
                            @error('meta_description_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="h1_template">Шаблон H1</label>
                            <input type="text" name="h1_template" id="h1_template" class="form-control @error('h1_template') is-invalid @enderror" value="{{ old('h1_template', $template->h1_template) }}">
                            @error('h1_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Обновить шаблон</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Отмена</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

