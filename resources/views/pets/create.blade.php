@extends('layouts.app')

@section('content')
    <h1> Dodaj zwierzę </h1>

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pets.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nazwa:</label><br>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="status">Status:</label><br>
            <select name="status" id="status">
                @foreach(\App\Enums\PetStatus::values() as $statusValue)
                    <option value="{{ $statusValue }}"
                        {{ old('status') == $statusValue ? 'selected' : '' }}>
                        {{ $statusValue }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Zapisz</button>
    </form>
@endsection
