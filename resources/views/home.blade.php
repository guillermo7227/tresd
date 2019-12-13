<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            body {
                background: gray;
            }
            a,body {
                color: yellow;
            }
            a:visited {
                color: yellow;
            }
        </style>
    </head>
    <body>
        <div>
            <b>Modelos</b> <br/>
            <a href="/mystic">Mystic</a> |
            <a href="/echo">Echo</a> |
            <a href="/orochi">Orochi</a> |
        </div>
        <hr/>

        @yield('contenido')

        <script src="https://threejs.org/build/three.js"></script>
        <script src="https://threejs.org/examples/js/loaders/OBJLoader.js"></script>
        <script src="https://threejs.org/examples/js/controls/OrbitControls.js"></script>
        @stack('scripts')
    </body>
</html>
