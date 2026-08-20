<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoomController extends Controller
{
    private function currentUser(): ?User
    {
        return User::find(session('user_id'));
    }

    private function isBakimSorumlusu(): bool
    {
        $user = $this->currentUser();
        return $user && $user->role === 'bakim_sorumlusu';
    }

    private function isOzelKalemMuduru(): bool
    {
        $user = $this->currentUser();
        return $user && $user->role === 'ozel_kalem_muduru';
    }

    /**
     * Oda ekleme/düzenleme/silme yetkisi sadece Özel Kalem Müdürü'nde.
     * Personel sadece görüntüleyebilir.
     */
    private function canManageRooms(): bool
    {
        return $this->isOzelKalemMuduru();
    }

    public function index(): View
    {
        $rooms = Room::orderBy('name')->get();
        $canManageFaults = $this->isBakimSorumlusu();
        $canManageRooms = $this->canManageRooms();

        return view('rooms.index', compact('rooms', 'canManageFaults', 'canManageRooms'));
    }

    public function create(): View
    {
        if (! $this->canManageRooms()) {
            abort(403, 'Oda ekleme yetkiniz yok.');
        }

        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->canManageRooms()) {
            abort(403, 'Oda ekleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'location'  => 'nullable|string|max:255',
            'capacity'  => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Oda başarıyla eklendi.');
    }

    public function edit(Room $room): View
    {
        if (! $this->canManageRooms()) {
            abort(403, 'Oda düzenleme yetkiniz yok.');
        }

        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        if (! $this->canManageRooms()) {
            abort(403, 'Oda düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'location'  => 'nullable|string|max:255',
            'capacity'  => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Oda güncellendi.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if (! $this->canManageRooms()) {
            abort(403, 'Oda silme yetkiniz yok.');
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Oda silindi.');
    }

    public function markFaulty(Request $request, Room $room): RedirectResponse
    {
        if (! $this->isBakimSorumlusu()) {
            abort(403, 'Bu işlem için bakım sorumlusu yetkisi gerekiyor.');
        }

        $validated = $request->validate([
            'fault_note' => 'nullable|string|max:255',
        ]);

        $room->update([
            'is_faulty'  => true,
            'fault_note' => $validated['fault_note'] ?? null,
        ]);

        return redirect()->route('rooms.index')->with('success', $room->name . ' arızalı olarak işaretlendi.');
    }

    public function clearFaulty(Room $room): RedirectResponse
    {
        if (! $this->isBakimSorumlusu()) {
            abort(403, 'Bu işlem için bakım sorumlusu yetkisi gerekiyor.');
        }

        $room->update([
            'is_faulty'  => false,
            'fault_note' => null,
        ]);

        return redirect()->route('rooms.index')->with('success', $room->name . ' için arıza durumu kaldırıldı.');
    }
}