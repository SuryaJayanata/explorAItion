<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Kelas Saya</h3>
                        
                        <div class="flex space-x-4">
                            @if(Auth::user()->isGuru())
                                <a href="{{ route('kelas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Buat Kelas Baru
                                </a>
                            @endif
                            <button onclick="document.getElementById('joinModal').classList.remove('hidden')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Gabung Kelas
                            </button>
                        </div>
                    </div>
                    
                    @if($kelas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($kelas as $k)
                                <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                                    <h4 class="font-semibold text-lg mb-2">{{ $k->nama_kelas }}</h4>
                                    <p class="text-gray-600 mb-2">Kode: {{ $k->kode_kelas }}</p>
                                    @if(Auth::user()->isGuru())
                                        <p class="text-gray-600">{{ $k->anggota_count }} Anggota</p>
                                    @else
                                        <p class="text-gray-600">Dibuat oleh: {{ $k->guru->nama }}</p>
                                    @endif
                                    <div class="mt-4">
                                        <a href="{{ route('kelas.show', $k->id_kelas) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Masuk Kelas
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">Anda belum memiliki kelas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Join Modal -->
    <div id="joinModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gabung Kelas</h3>
                
                <form action="{{ route('kelas.join') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="kode_kelas" class="block text-gray-700 text-sm font-bold mb-2">Kode Kelas:</label>
                        <input type="text" name="kode_kelas" id="kode_kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('kode_kelas')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="document.getElementById('joinModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Gabung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>