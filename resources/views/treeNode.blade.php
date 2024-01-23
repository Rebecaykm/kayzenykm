<!-- resources/views/partials/treeNode.blade.php -->

<li class="mb-2 tree-node">
    <div>
        @for ($i = 0; $i < $level; $i++)
            @if ($i === $level - 1)
                <span>|___</span>
            @else
                <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @endif
        @endfor
        <span>{{ $node['number'] }}</span>
        <div class="tree-line"></div>
    </div>
    @if (!empty($node['subParts']))
        <ul class="list-none ml-4">
            @foreach ($node['subParts'] as $index => $subNode)
                @include('treeNode', ['node' => $subNode, 'level' => $level + 1, 'isLast' => $index === count($node['subParts']) - 1])
            @endforeach
        </ul>
    @endif
</li>
