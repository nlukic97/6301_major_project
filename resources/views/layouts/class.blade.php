<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Requiered for the text editor to work -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">

</head>
<body>
    @yield('content')

<!-- Requiered for the code mirror editor to work -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>
<!-- Requiered for the text editor to work -->


<!-- User code from codemirror which will be executed -->
<script id="code-to-execute"></script>

<!-- Initializing the codemirror instance -->
<script src="{{asset('js/codeMirrorCustomCode.js')}}"></script>
</body>
</html>
