@extends('layouts.app')

@section('content')
    <h1>Szczegóły zwierzęcia</h1>

    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    @if(isset($pet))
        <ul>
            <li>ID: {{ $pet['id'] ?? 'brak' }}</li>
            <li>Nazwa: {{ $pet['name'] ?? 'brak' }}</li>
            <li>Status: {{ $pet['status'] ?? 'brak' }}</li>
        </ul>
    @else
        <p>Brak danych do wyświetlenia.</p>
    @endif

    <a href="{{ route('pets.index') }}">Powrót do listy</a>
@endsection
