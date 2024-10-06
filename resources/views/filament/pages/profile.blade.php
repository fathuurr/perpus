<x-filament-panels::page>

    <form wire:submit.prevent="submit">
        {{ $this->form }}
    </form>

    @if(Auth::user()->role === 'siswa')
        <div class="mt-8">
            <h2 class="text-lg font-medium">Borrowed Books</h2>
            <ul class="mt-4 space-y-2">
                @forelse($this->borrowedBooks as $borrowing)
                    <li>{{ $borrowing->book->title }} (Borrowed on: {{ $borrowing->borrowed_at->format('Y-m-d') }})</li>
                @empty
                    <li>No books currently borrowed.</li>
                @endforelse
            </ul>
        </div>
    @endif

</x-filament-panels::page>
