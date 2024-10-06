<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Profil Pengguna
        </x-slot>

        <form wire:submit.prevent="updateName" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit">
                Simpan Perubahan
            </x-filament::button>
        </form>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Riwayat Peminjaman Buku
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($this->borrowingHistory as $borrowing)
                <x-filament::card>
                    <div class="space-y-2">
                        <div class="font-medium">
                            Buku:
                            {{ $borrowing->book->title }}
                        </div>

                        <p class="text-sm text-gray-600 dark:text-white">
                            Tanggal Peminjaman: {{ $borrowing->borrowed_at->format('d F Y') }}
                        </p>

                        <p class="text-sm text-gray-600 dark:text-white ">
                            @if($borrowing->returned_at)
                                Tanggal Pengembalian: {{ \Carbon\Carbon::parse($borrowing->returned_at)->format('d F Y') }}
                            @else
                                <x-filament::badge color="danger">
                                    Buku belum dikembalikan
                                </x-filament::badge>
                            @endif
                        </p>

                        <div>
                            Status:
                            <x-filament::badge
                                :color="$borrowing->status === 'borrowed' ? 'warning' : 'success'"
                            >
                                {{ $borrowing->status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}
                            </x-filament::badge>
                        </div>
                    </div>
                </x-filament::card>
            @empty
                <x-filament::card class="col-span-full">
                    <div class="text-center text-gray-500">
                        Anda belum pernah meminjam buku.
                    </div>
                </x-filament::card>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>
