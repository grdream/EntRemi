<x-app-layout>
    @section('title', 'Data Importer')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-orange-500/10 dark:bg-orange-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">Data Importer</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Bulk import shows from a JSON backup file.</p>
            </div>
        </div>
    </x-slot>

    {{-- Import Result Summary --}}
    @if(session('import_result'))
    @php $result = session('import_result'); @endphp
    <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false, 8000)" x-transition class="mb-6 glass-card p-5 border border-brand-500/20">
        <h3 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            Import Complete
        </h3>
        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="p-3 rounded-xl bg-emerald-500/10">
                <p class="text-xl font-bold text-emerald-500">{{ $result['imported'] ?? 0 }}</p>
                <p class="text-xs text-surface-400 mt-0.5">Imported</p>
            </div>
            <div class="p-3 rounded-xl bg-amber-500/10">
                <p class="text-xl font-bold text-amber-500">{{ $result['skipped'] ?? 0 }}</p>
                <p class="text-xs text-surface-400 mt-0.5">Skipped</p>
            </div>
            <div class="p-3 rounded-xl bg-red-500/10">
                <p class="text-xl font-bold text-red-500">{{ $result['failed'] ?? 0 }}</p>
                <p class="text-xs text-surface-400 mt-0.5">Failed</p>
            </div>
        </div>
        @if(!empty($result['errors']))
        <div class="mt-4">
            <p class="text-xs font-semibold text-red-500 mb-2">Per-record Errors:</p>
            <div class="max-h-36 overflow-y-auto space-y-1">
                @foreach($result['errors'] as $err)
                <div class="text-xs text-red-400 bg-red-500/5 rounded-lg px-3 py-1.5 font-mono">{{ $err }}</div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false, 3500)" x-transition
         class="mb-6 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Drag-and-drop Upload Form --}}
        <div class="glass-card p-6">
            <h2 class="text-lg font-semibold text-surface-900 dark:text-white mb-4">Upload Backup (.json)</h2>
            <form action="{{ route('tools.import.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="{
                      dragging: false,
                      fileName: null,
                      handleDrop(e) {
                          this.dragging = false;
                          const file = e.dataTransfer.files[0];
                          if (file && file.name.endsWith('.json')) {
                              this.fileName = file.name;
                              const dt = new DataTransfer();
                              dt.items.add(file);
                              document.getElementById('file_input').files = dt.files;
                          }
                      },
                      handleChange(e) {
                          this.fileName = e.target.files[0]?.name || null;
                      }
                  }">
                @csrf

                {{-- Drop Zone --}}
                <div @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="dragging ? 'border-brand-500 bg-brand-500/5' : 'border-surface-300 dark:border-surface-600 hover:border-brand-400 dark:hover:border-brand-500'"
                     class="relative border-2 border-dashed rounded-2xl transition-all duration-200 p-8 text-center cursor-pointer mb-4"
                     @click="document.getElementById('file_input').click()">

                    <input class="hidden" id="file_input" type="file" name="json_file" accept=".json,application/json"
                           @change="handleChange($event)">

                    <div x-show="!fileName">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500/10 mx-auto flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Drag & drop your JSON file here</p>
                        <p class="text-xs text-surface-400 mt-1">or click to browse files</p>
                    </div>
                    <div x-show="fileName" x-cloak>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 mx-auto flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="fileName"></p>
                        <p class="text-xs text-surface-400 mt-1">Ready to import · click to change</p>
                    </div>
                </div>

                @error('json_file')
                    <p class="mb-3 text-xs text-red-500 bg-red-500/10 rounded-lg px-3 py-2">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-primary w-full justify-center" id="import-submit-btn">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    Import Now
                </button>
            </form>
        </div>

        {{-- JSON Format Documentation --}}
        <div class="space-y-4">
            <div class="glass-card p-6 bg-surface-50 dark:bg-surface-800/30">
                <h3 class="font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    JSON Format
                </h3>
                <p class="text-sm text-surface-600 dark:text-surface-400 mb-4">Upload a JSON array. Each object represents one show. All fields except <code class="text-brand-500 bg-brand-500/10 px-1 rounded">title</code> are optional.</p>
                <div class="bg-surface-900 dark:bg-black rounded-xl p-4 overflow-x-auto">
                    <pre class="text-xs text-brand-300 font-mono leading-relaxed">[
  {
    "title": "Goblin",
    "type": "drama",
    "status": "completed",
    "year": 2016,
    "rating": 9.2,
    "total_episodes": 16,
    "country": "South Korea",
    "description": "A grim reaper..."
  },
  {
    "title": "Attack on Titan",
    "type": "anime",
    "status": "watching",
    "episodes": 87
  },
  {
    "title": "Inception",
    "type": "movie",
    "status": "completed"
  }
]</pre>
                </div>
            </div>

            {{-- Supported Fields --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-surface-900 dark:text-white mb-3 text-sm">Supported Fields</h3>
                <div class="space-y-2">
                    @foreach([
                        ['title', 'string', 'Required — show title', true],
                        ['type', 'string', 'drama · movie · anime · tv_series · anime_movie · other', false],
                        ['status', 'string', 'plan_to_watch · watching · completed · on_hold · dropped', false],
                        ['year', 'integer', 'Release year (e.g. 2024)', false],
                        ['rating', 'number', 'Score from 0–10', false],
                        ['total_episodes / episodes', 'integer', 'Total episode count', false],
                        ['country', 'string', 'Country of origin', false],
                        ['description', 'string', 'Synopsis / plot', false],
                        ['poster_url', 'string', 'Poster image URL', false],
                    ] as [$field, $type, $desc, $req])
                    <div class="flex items-start gap-3 py-1.5 border-b border-surface-200/40 dark:border-surface-700/40 last:border-0">
                        <code class="text-[10px] font-mono text-brand-500 bg-brand-500/10 px-1.5 py-0.5 rounded flex-shrink-0 mt-0.5">{{ $field }}</code>
                        <div class="flex-1">
                            <span class="text-xs text-surface-600 dark:text-surface-400">{{ $desc }}</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded {{ $req ? 'bg-red-500/10 text-red-500' : 'bg-surface-200/60 dark:bg-surface-700/60 text-surface-400' }}">
                            {{ $req ? 'required' : $type }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-surface-400 mt-4">
                    <strong class="text-surface-500">MAL-style numeric status:</strong> 1=watching, 2=completed, 3=on_hold, 4=dropped, 6=plan_to_watch. Auto-mapped.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
