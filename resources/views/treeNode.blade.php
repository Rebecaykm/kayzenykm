<!-- resources/views/partials/treeNode.blade.php -->

<li class="mb-2 tree-node">
    <div>
        <span>{{ $node['number'] }}</span>
        <div class="tree-line"></div>
    </div>
    @if (!empty($node['subParts']))
        <ul class="list-none ml-4">
            @foreach ($node['subParts'] as $subNode)
                @include('treeNode', ['node' => $subNode])
            @endforeach
        </ul>
    @endif
</li>
