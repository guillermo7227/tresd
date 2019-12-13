<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
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
            <a href="javascript:cambiarMaterial('img/lider.jpg')">material 1</a> |
            <a href="javascript:cambiarMaterial('img/vt.jpg')">material 2</a> |
            <a href="javascript:cambiarMaterial('img/cancion.jpg')">material 3</a> |
            <a href="javascript:cambiarMaterial('img/acordeon.jpg')">material 4</a> |
            <a href="/three">threejs</a> |
        </div>

        <script src="{{ asset('js/xeogl.js') }}"></script>
        <script src="{{ asset('js/OBJModel.js') }}"></script>

    <script src="{{ asset('js/k3d.js') }}"></script>
    <script src="{{ asset('js/objGeometryLoader.js') }}"></script>
        <script>

            function cambiarMaterial (src) {
                xeogl.scenes["default.scene"].meshes._13._material = new xeogl.PhongMaterial({
                    pointSize: 5,
                    diffuseMap: new xeogl.Texture({
                        src: src,
                        encoding: "sRGB"
                    }),
                })
            }

    var scene = xeogl.getDefaultScene();
    xeogl.loadOBJGeometry(scene, "obj/obj1.obj", function (geometry) {
        var mesh = new xeogl.Mesh({
            geometry: geometry,
            material: new xeogl.PhongMaterial({
                pointSize: 5,
                diffuseMap: new xeogl.Texture({
                    src: "img/cancion.jpg",
                    encoding: "sRGB"
                }),
            }),
            rotation: [0, 120, 0],
            position: [10, 3, 10]
        });
        // Allow user camera control
        new xeogl.CameraControl();
        var cameraFlight = new xeogl.CameraFlightAnimation();
        // Fit to view
        cameraFlight.jumpTo({
            aabb: mesh.aabb,
            fit: true,
            fitFOV: 45
        });
    });
        </script>
    </body>
</html>
