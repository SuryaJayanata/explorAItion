@extends('layouts.app')

@section('title', 'eduSPACE - Dashboard')

@section('content')
    <div class="action-section flex flex-col items-center mb-12 md:mb-16">
        @if(Auth::user()->isGuru())
            <!-- Teacher: Create Class Button -->
            <a href="{{ route('kelas.create') }}" class="create-class-btn w-80 md:w-96 bg-primary py-5 rounded-2xl text-white text-2xl md:text-3xl font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all text-center mb-8 md:mb-10">
                Create class +
            </a>
        @else
            <!-- Student: Join Class Button -->
            <button onclick="openJoinModal()" class="join-class-btn w-80 md:w-96 bg-primary py-5 rounded-2xl text-white text-2xl md:text-3xl font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all text-center mb-8 md:mb-10">
                Join class +
            </button>
        @endif
        
        <h1 class="page-title text-2xl md:text-3xl font-montserrat font-semibold text-center">
            Your classes
        </h1>
    </div>
    
    <div class="classes-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-7xl mx-auto">
        @if($kelas->count() > 0)
            @foreach($kelas as $k)
                <a href="{{ route('kelas.show', $k->id_kelas) }}" 
                   class="class-card bg-gradient-to-br from-gray-700 to-purple-800 rounded-2xl outline outline-2 outline-purple-400 p-6 h-64 flex flex-col justify-between transition-all hover:-translate-y-2 hover:shadow-xl">

                    <div>
                        <!-- Nama Kelas -->
                        <h2 class="class-name text-xl md:text-2xl font-montserrat font-bold mb-2 line-clamp-2">
                            {{ $k->nama_kelas }}
                        </h2>

                        <!-- Deskripsi Kelas -->
                        @if(!empty($k->deskripsi))
                            <p class="class-description text-sm text-white/70 mb-3 line-clamp-3 leading-relaxed">
                                {{ $k->deskripsi }}
                            </p>
                        @else
                            <p class="class-description text-sm text-white/40 mb-3 italic">
                                Tidak ada deskripsi kelas
                            </p>
                        @endif

                        <!-- Nama Guru -->
                        <p class="teacher-name text-sm font-montserrat font-semibold opacity-80">
                            {{ Auth::user()->isGuru() ? '' : 'Taught by: ' . ($k->guru->nama ?? 'Unknown') }}
                        </p>

                        <!-- Kode Kelas -->
                        <p class="class-code text-xs font-montserrat opacity-60 mt-2">
                            Code: {{ $k->kode_kelas }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <span class="inline-block bg-primary/20 text-primary text-xs px-3 py-1 rounded-full">
                            {{ Auth::user()->isGuru() ? 'Teacher' : 'Student' }}
                        </span>

                        @if(Auth::user()->isGuru())
                            <span class="inline-block bg-green-500/20 text-green-400 text-xs px-3 py-1 rounded-full ml-2">
                                {{ $k->anggota_count ?? 0 }} members
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div class="col-span-full text-center py-12">
                <p class="text-xl text-white/70">You don't have any classes yet.</p>
                <p class="text-white/50 mt-2">
                    {{ Auth::user()->isGuru() ? 'Create your first class to get started!' : 'Join a class to get started!' }}
                </p>
            </div>
        @endif
    </div>

    <!-- Join Class Modal -->
    <div id="joinModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-md mx-4 relative">
            <button onclick="closeJoinModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center text-shadow shadow-white/30">Join Class</h2>
            
            <form action="{{ route('kelas.join') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="kode_kelas" class="block text-lg mb-3">Class Code</label>
                    <div class="input-with-icon relative">
                        <input type="text" id="kode_kelas" name="kode_kelas" class="form-input w-full bg-transparent border-b border-white py-2 pr-10 text-white outline-none focus:border-primary" placeholder="Enter class code" required>
                        <img class="input-icon absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5" src="https://placehold.co/20x20/ab1ad6/white?text=🔒" alt="Code Icon">
                    </div>
                    @error('kode_kelas')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-green-600 py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all">
                    Join Class
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Join Modal Functions
    function openJoinModal() {
        const modal = document.getElementById('joinModal');
        if (modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
        }
    }

    function closeJoinModal() {
        const modal = document.getElementById('joinModal');
        if (modal) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    // Close modal when clicking outside
    const joinModal = document.getElementById('joinModal');
    if (joinModal) {
        joinModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeJoinModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeJoinModal();
        }
    });
</script>
@endpush