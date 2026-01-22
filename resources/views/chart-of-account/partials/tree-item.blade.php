{{-- Tree item partial for recursive rendering --}}
<div class="py-1" style="padding-left: {{ $level * 16 }}px">
    <div class="flex items-center text-gray-700 hover:text-primary-600 transition">
        @if($account->children->count() > 0)
            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @else
            <span class="w-4 mr-1"></span>
        @endif
        
        @if($account->is_header)
            <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        @else
            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-2"></span>
        @endif
        
        <span class="font-mono text-xs text-gray-400 mr-2">{{ $account->code }}</span>
        <span class="{{ $account->is_header ? 'font-medium' : '' }} {{ !$account->is_active ? 'text-gray-400 line-through' : '' }}">
            {{ $account->name }}
        </span>
    </div>
    
    @if($account->children->count() > 0)
        @foreach($account->children as $child)
            @include('chart-of-account.partials.tree-item', ['account' => $child, 'level' => $level + 1])
        @endforeach
    @endif
</div>
