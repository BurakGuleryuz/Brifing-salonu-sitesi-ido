@extends('layouts.app')

@section('title', 'Yeni Oda')

@section('content')
    <h1>Yeni Oda Ekle</h1>

    <form action="{{ route('rooms.store') }}" method="POST" class="bg-white p-4 rounded border">
        @csrf

        <div class="mb-3">
            <label class="form-label">Oda Adı</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konum</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Kapasite</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="1">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
            <label class="form-check-label" for="is_active">Aktif (Ekranda görünsün)</label>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Vazgeç</a>
    </form>
@endsection