@php
    $wa = \App\Models\WhatsApp::where('active', true)->orderByDesc('id')->first();
@endphp

@if($wa)
<link rel="stylesheet" href="{{ asset('css/whatsapp-bubble.css') }}">
<div id="whatsapp-bubble"
     data-phone="{{ $wa->phone }}"
     data-message="{{ rawurlencode($wa->message ?? '') }}"
     data-label="{{ $wa->label ?? 'Chat' }}">
    <button id="whatsapp-open" aria-label="WhatsApp">
        @if($wa->image)
            <img src="{{ asset('storage/'.$wa->image) }}" alt="WhatsApp" style="width:38px; height:38px; object-fit:contain; border-radius:50%;">
        @else
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden> ... </svg>
        @endif
    </button>
</div>
<script src="{{ asset('js/whatsapp-bubble.js') }}" defer></script>
@endif
