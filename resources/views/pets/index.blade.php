@extends('layouts.app')

@section('content')
    
    <h1>Zwierzęta</h1>

    @if(session('success'))
        <div style="color:green;"><h2>{{ session('success') }}</h2></div>
    @endif
    @if(session('error'))
        <div style="color:red;"><h2>{{ session('error') }}</h2></div>
    @endif

    <form action="{{ route('pets.index') }}" method="GET">
        <label>
            <input type="checkbox" name="status[]" value="available"
                {{ in_array('available', $selectedStatuses ?? []) ? 'checked' : '' }}>
            available
        </label>

        <label>
            <input type="checkbox" name="status[]" value="pending"
                {{ in_array('pending', $selectedStatuses ?? []) ? 'checked' : '' }}>
            pending
        </label>

        <label>
            <input type="checkbox" name="status[]" value="sold"
                {{ in_array('sold', $selectedStatuses ?? []) ? 'checked' : '' }}>
            sold
        </label>

        <button type="submit">Filtruj</button>
    </form>

    <hr>

    <a href="{{ route('pets.create') }}">Dodaj nowe zwierzę</a>

    @if (!empty($pets) && is_array($pets))
        <ul>
            @foreach ($pets as $pet)
                <li>
                    ID: {{ $pet['id'] ?? 'brak' }} |
                    Nazwa: {{ $pet['name'] ?? 'brak' }} |
                    Status: {{ $pet['status'] ?? 'brak' }}

                    [<a href="{{ route('pets.show', $pet['id']) }}">Szczegóły</a>]
                    [<a href="{{ route('pets.edit', $pet['id']) }}">Edytuj</a>]
                    <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Usuń</button>
                    </form>
                </li>
                <hr>
            @endforeach
        </ul>
    @else
        <p>Brak zwierząt do wyświetlenia.</p>
    @endif
@endsection
