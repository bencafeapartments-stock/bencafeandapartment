<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminApartmentController extends Controller
{
    public function index()
    {

        $apartments = Apartment::all();
        $totalApartments = $apartments->count();
        $availableApartments = $apartments->where('status', 'available')->count();
        $occupiedApartments = $apartments->where('status', 'occupied')->count();
        $maintenanceApartments = $apartments->where('status', 'maintenance')->count();


        $apartmentsArray = $apartments->map(function ($apartment) {
            return [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'amenities' => $apartment->amenities,
                'description' => $apartment->description,
                'created_at' => $apartment->created_at,
            ];
        })->toArray();

        return view('owner.apartments.index', compact(
            'totalApartments',
            'availableApartments',
            'occupiedApartments',
            'maintenanceApartments'
        ))->with('apartmentsData', $apartmentsArray);
    }

    public function createApartment()
    {
        return view('owner.apartments.create');
    }

    public function storeApartment(Request $request)
    {
        $request->validate([
            'apartment_number' => 'required|string|max:10|unique:apartments,apartment_number',
            'apartment_type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:available,occupied,maintenance',
            'description' => 'nullable|string|max:1000',
        ]);


        $apartment = Apartment::create([
            'apartment_number' => $request->apartment_number,
            'apartment_type' => $request->apartment_type,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.apartments.index')
            ->with('success', 'Apartment "' . $request->apartment_number . '" created successfully!');
    }

    public function editApartment($apartmentId)
    {

        $apartment = Apartment::findOrFail($apartmentId);

        return view('owner.apartments.edit', compact('apartment'));
    }

    public function updateApartment(Request $request, $apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $request->validate([
            'apartment_number' => 'required|string|max:10|unique:apartments,apartment_number,' . $apartment->id,
            'apartment_type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'floor_number' => 'nullable|integer|min:1|max:50',
            'size_sqm' => 'nullable|numeric|min:1',
            'status' => 'required|string|in:available,occupied,maintenance',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ]);


        $apartment->update([
            'apartment_number' => $request->apartment_number,
            'apartment_type' => $request->apartment_type,
            'price' => $request->price,
            'floor_number' => $request->floor_number,
            'size_sqm' => $request->size_sqm,
            'status' => $request->status,
            'amenities' => $request->amenities ?: [],
            'description' => $request->description,
        ]);

        return redirect()->route('owner.apartments.index')
            ->with('success', 'Apartment "' . $request->apartment_number . '" updated successfully!');
    }

    public function deleteApartment($apartment)
    {

        $apartment = Apartment::findOrFail($apartment);
        $apartment->delete();


        return redirect()->route('owner.apartments.index')
            ->with('success', 'Apartment deleted successfully.');
    }


    public function getTenantDetails($apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $currentRent = Rent::where('apartment_id', $apartmentId)
            ->where('status', 'active')
            ->with('tenant')
            ->first();

        if (!$currentRent || !$currentRent->tenant) {
            return response()->json([
                'tenant' => null,
                'apartment' => $apartment,
                'rent' => null
            ]);
        }

        return response()->json([
            'tenant' => [
                'name' => $currentRent->tenant->name,
                'email' => $currentRent->tenant->email,
                'contact_number' => $currentRent->tenant->contact_number,
            ],
            'apartment' => [
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
            ],
            'rent' => [
                'monthly_rent' => $currentRent->monthly_rent,
                'security_deposit' => $currentRent->security_deposit,
                'start_date' => $currentRent->start_date->toDateString(),
                'end_date' => $currentRent->end_date ? $currentRent->end_date->toDateString() : null,
                'status' => $currentRent->status,
            ]
        ]);
    }

    public function getApartmentDetails($apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $currentRent = Rent::where('apartment_id', $apartmentId)
            ->where('status', 'active')
            ->with('tenant')
            ->first();

        $response = [
            'apartment' => [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'amenities' => $apartment->amenities ?? [],
                'description' => $apartment->description,
                'created_at' => $apartment->created_at->toISOString(),
            ],
            'tenant' => null,
            'rent' => null
        ];

        if ($currentRent && $currentRent->tenant) {
            $response['tenant'] = [
                'id' => $currentRent->tenant->id,
                'name' => $currentRent->tenant->name,
                'email' => $currentRent->tenant->email,
                'contact_number' => $currentRent->tenant->contact_number,
            ];

            $response['rent'] = [
                'id' => $currentRent->id,
                'monthly_rent' => $currentRent->monthly_rent,
                'security_deposit' => $currentRent->security_deposit,
                'start_date' => $currentRent->start_date->toDateString(),
                'end_date' => $currentRent->end_date ? $currentRent->end_date->toDateString() : null,
                'status' => $currentRent->status,
                'terms' => $currentRent->terms,
            ];
        }

        return response()->json($response);
    }
    
    public function getEditData(Apartment $apartment)
    {
        return response()->json([
            'apartment' => [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'description' => $apartment->description,
                'amenities' => $apartment->amenities ?? []
            ]
        ]);
    }
}
