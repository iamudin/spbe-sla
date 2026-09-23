@extends('spbe-sla::layout.app', ['title' => 'Settings - SPBE SLA'])

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-navy">SPBE SLA Plugin Settings</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-2xl">
    <form action="{{ route('spbe-sla.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-navy mb-2">Custom Domain</label>
                <input type="text" name="custom_domain" value="{{ old('custom_domain', $customDomain) }}" placeholder="e.g. sla.domain.com" class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">
                <p class="text-xs text-gray-500 mt-2">
                    Enter a custom domain (without http/https) to access the Public Dashboard directly. <br>
                    <strong>Note:</strong> Ensure you have mapped the domain to this server's IP address.
                </p>
                @error('custom_domain')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="bg-indigo text-white font-bold px-6 py-2.5 rounded-lg shadow hover:bg-indigo/90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
