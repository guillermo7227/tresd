<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->

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
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex0.jpg')">material 0</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex1.jpg')">material 1</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex2.jpg')">material 2</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex3.jpg')">material 3</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex4.jpg')">material 4</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/texhd.jpg')">material HD</a> |
            <a href="javascript:cambiarMaterial('t', 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex.png')">material colores</a> |
            <a href="javascript:cambiarMaterial('t', 'img/texa.png')">material colores a</a> |
            <a href="javascript:cambiarMaterial('t', 'img/texb.png')">material colores b</a> |
            <a href="javascript:cambiarMaterial('c', 0xFF0000)">rojo</a> |
            <a href="javascript:cambiarMaterial('c', 0x00FF00)">verde</a> |
            <a href="javascript:cambiarMaterial('c', 0x0000FF)">azul</a> |
        </div>

        <script src="https://threejs.org/build/three.js"></script>
        <script src="https://threejs.org/examples/js/loaders/OBJLoader.js"></script>
        <script src="https://threejs.org/examples/js/controls/OrbitControls.js"></script>
        {{-- <script src="{{ asset('js/OBJLoader.js') }}"></script> --}}
        {{-- <script src="{{ asset('js/OrbitControls.js') }}"></script> --}}

<script id="vertexShader" type="x-shader/x-vertex">
  varying vec2 vUv;
  void main() {
  vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position,1.0);
  }
</script>

<script id="fragmentShader" type="x-shader/x-fragment">
  uniform vec3 color1;
  uniform vec3 color2;
  varying vec2 vUv;
  void main() {
    gl_FragColor = vec4(mix(color1, color2, vUv.y),1.0);
  }
</script>

        <script>

            var camera, scene, renderer, mesh, material;
init();
animate();

function init() {
    // Renderer.
    renderer = new THREE.WebGLRenderer();
    //renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    // Add renderer to page
    document.body.appendChild(renderer.domElement);

    // Create camera.
    camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 1, 1000);
    camera.position.z = 400;

    // Create scene.
    scene = new THREE.Scene();

    var uniforms = {
      "color1" : {
        type : "c",
        value : new THREE.Color(0xffffff)
      },
      "color2" : {
        type : "c",
        value : new THREE.Color(0x000000)
      },
    };

    var fShader = document.getElementById('fragmentShader').text;
    var vShader = document.getElementById('vertexShader').text;

    // Create material
    var material = new THREE.ShaderMaterial({
      uniforms: uniforms,
      vertexShader: vShader,
      fragmentShader: fShader
    });

    // Create cube and add to scene.
    var geometry = new THREE.BoxGeometry(200, 200, 200);
    mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    // Create ambient light and add to scene.
    var light = new THREE.AmbientLight(0x404040); // soft white light
    scene.add(light);

    // Create directional light and add to scene.
    var directionalLight = new THREE.DirectionalLight(0xffffff);
    directionalLight.position.set(1, 1, 1).normalize();
    scene.add(directionalLight);

    // Add listener for window resize.
    window.addEventListener('resize', onWindowResize, false);

}

function animate() {
    requestAnimationFrame(animate);
    mesh.rotation.x += 0.005;
    mesh.rotation.y += 0.01;
    renderer.render(scene, camera);
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}



        </script>
    </body>
</html>
