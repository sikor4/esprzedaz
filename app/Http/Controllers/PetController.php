<?php

namespace App\Http\Controllers;

use App\Enums\PetStatus;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    protected string $baseUri;

    public function __construct()
    {
        $this->baseUri = env('PETSTORE_API_URL', 'https://petstore.swagger.io/v2');
    }

    public function index(Request $request)
    {
        try {
    
            $possibleStatuses = PetStatus::values();
            $selectedStatuses = $request->input('status', []);
    
            if (!is_array($selectedStatuses)) {
                $selectedStatuses = [$selectedStatuses];
            }
    
            $selectedStatuses = array_intersect($selectedStatuses, $possibleStatuses);
    
            if (empty($selectedStatuses)) {
                $selectedStatuses = [PetStatus::AVAILABLE->value];
            }
    
            $statusParam = implode(',', $selectedStatuses);
            $response = Http::get($this->baseUri . '/pet/findByStatus', [
                'status' => $statusParam
            ]);
    
            $pets = $response->json();
    
            return view('pets.index', [
                'pets'             => $pets,
                'selectedStatuses' => $selectedStatuses,
            ]);
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return back()->with('error', 'Błąd przy pobieraniu listy zwierząt');
        }
    }


    public function create()
    {
        return view('pets.create');
    }

    public function store(StorePetRequest $request)
    {
        $data = $request->validated();

        $payload = [
            'id'        => rand(1000, 999999),
            'name'      => $data['name'],
            'photoUrls' => [],
            'tags'      => [],
            'status'    => $data['status']
        ];

        try {
            $response = Http::post($this->baseUri . '/pet', $payload);
            $pet = $response->json();

            return redirect()
                ->route('pets.index')
                ->with('success', 'Utworzono nowe zwierzę o ID równym: ' . $pet['id']);
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd podczas próby utworzenia');
        }
    }

    public function show($id)
    {
        try {
            $response = Http::get($this->baseUri . '/pet/' . $id);
            $pet = $response->json();

            return view('pets.show', compact('pet'));
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return redirect()->route('pets.index')->with('error', 'Wystąpił błąd podczas pobrania');
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::get($this->baseUri . '/pet/' . $id);
            $pet = $response->json();

            return view('pets.edit', compact('pet'));
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return redirect()->route('pets.index')->with('error', 'Wystąpił błąd podczas edycji');
        }
    }

    public function update(UpdatePetRequest $request, $id)
    {
        $data = $request->validated();

        $payload = [
            'id'        => (int) $id,
            'name'      => $data['name'],
            'photoUrls' => [],
            'tags'      => [],
            'status'    => $data['status']
        ];

        try {
            $response = Http::put($this->baseUri . '/pet', $payload);
            $pet = $response->json();

            return redirect()->route('pets.show', $id)
                ->with('success', "Zaktualizowano zwierzę o ID: {$pet['id']} pomyślnie");
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd podczas aktualizacji');
        }
    }

    public function destroy($id)
    {
        try {
            Http::delete($this->baseUri . '/pet/' . $id);

            return redirect()->route('pets.index')
                ->with('success', 'Usunięto zwierzęcie o ID równym ' . $id);
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '::' . __METHOD__ . ' ' . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd przy próbie usunięcia');
        }
    }
}
