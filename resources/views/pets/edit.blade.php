@extends('layouts.app')

@section('content')
    <h1>Edycja zwierzęcia</h1>

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    @if (isset($pet))
        <form action="{{ route('pets.update', $pet['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="name">Nazwa:</label><br>
                <input type="text" id="name" name="name" value="{{ old('name', $pet['name'] ?? '') }}">
                @error('name')
                    <div style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="status">Status:</label><br>
                @php
                    $currentStatus = old('status', $pet['status'] ?? 'available');
                @endphp
                <select name="status" id="status">
                    @foreach(\App\Enums\PetStatus::values() as $statusValue)
                        <option value="{{ $statusValue }}"
                            {{ $currentStatus == $statusValue ? 'selected' : '' }}>
                            {{ $statusValue }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Zapisz zmiany</button>
        </form>
    @else
        <p>Brak danych do edycji.</p>
    @endif
@endsection
