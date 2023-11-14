<!-- resources/views/tuVista.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Árbol de PartNumbers</title> -->
    <style>
        ul {
            list-style-type: none;
        }
    </style>
</head>

<body>

    <!-- <h1>Árbol de PartNumbers</h1> -->

    @foreach ($tree as $node)
    <ul>
        @include('treeNode', ['node' => $node])
        <hr>
    </ul>
    @endforeach

</body>

</html>
