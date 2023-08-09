<label class="block mt-4 text-sm">
    <span class="text-gray-700 dark:text-gray-400 text-xs">Numero de parte </span>
    <input id="NP" name="NP" wire:model='search' wire:keyup='searchProduct' type="text" value="{{ $product}}""
        class=" form-control block w-80 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        required />
    @if ($showlist)
        <ul class="list-disc">
            @if (!empty($results))
                @foreach ($results as $result)
                    <li wire:click='getProduct({{ $result['IPROD'] }})'>{{ $result['IPROD'] }}/{{ $result['ICLAS'] }}
                    </li>
                @endforeach
            @endif
        </ul>
    @endif
</label>
