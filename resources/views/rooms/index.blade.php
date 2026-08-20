@extends('layouts.app')

@section('title', 'Odalar')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Odalar</h1>
        @if ($canManageRooms)
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Yeni Oda</a>
        @endif
    </div>

    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Ad</th>
                <th>Konum</th>
                <th>Kapasite</th>
                <th>Durum</th>
                <th class="text-end">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr class="{{ $room->is_faulty ? 'table-danger' : '' }}">
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->location ?? '-' }}</td>
                    <td>{{ $room->capacity ?? '-' }}</td>
                    <td>
                        @if ($room->is_faulty)
                            <span class="badge bg-danger">⚠️ Arızalı</span>
                            @if ($room->fault_note)
                                <div class="small text-muted mt-1">{{ $room->fault_note }}</div>
                            @endif
                        @elseif ($room->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Pasif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('signage.show', $room) }}" class="btn btn-sm btn-outline-info" target="_blank">Ekranı Aç</a>

                        @if ($canManageRooms)
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-secondary">Düzenle</a>
                        @endif

                        @if ($canManageFaults)
                            @if ($room->is_faulty)
                                <form action="{{ route('rooms.clear-faulty', $room) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Arızayı Kapat</button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#faultModal{{ $room->id }}">
                                    Arıza Bildir
                                </button>
                            @endif
                        @endif

                        @if ($canManageRooms)
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu odayı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                            </form>
                        @endif
                    </td>
                </tr>

                @if ($canManageFaults && ! $room->is_faulty)
                    <div class="modal fade" id="faultModal{{ $room->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('rooms.mark-faulty', $room) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $room->name }} - Arıza Bildir</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Arıza Açıklaması (isteğe bağlı)</label>
                                        <input type="text" name="fault_note" class="form-control" placeholder="Örn: Projeksiyon çalışmıyor">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Vazgeç</button>
                                        <button type="submit" class="btn btn-danger">Arızalı Olarak İşaretle</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Henüz oda eklenmemiş.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection