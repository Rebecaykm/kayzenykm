<!-- resources/views/partials/treeNode.blade.php -->

<li>
    {{ $node['number'] }}
    @if (!empty($node['subParts']))
    <ul>
        @foreach ($node['subParts'] as $subNode)
        @include('treeNode', ['node' => $subNode])
        @endforeach
    </ul>
    @endif
</li>
