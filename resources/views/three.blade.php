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
            <a href="javascript:cambiarMaterial('c', 0xFF0000)">rojo</a> |
            <a href="javascript:cambiarMaterial('c', 0x00FF00)">verde</a> |
            <a href="javascript:cambiarMaterial('c', 0x0000FF)">azul</a> |
        </div>

        <script src="https://threejs.org/build/three.js"></script>
        <script src="https://threejs.org/examples/js/loaders/OBJLoader.js"></script>
        <script src="https://threejs.org/examples/js/controls/OrbitControls.js"></script>
        {{-- <script src="{{ asset('js/OBJLoader.js') }}"></script> --}}
        {{-- <script src="{{ asset('js/OrbitControls.js') }}"></script> --}}
        <script>

var tamano = 400;
var textura0 = 'https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/tex0.jpg';
var cambiarMaterial;
var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera( 75, tamano / tamano, 0.1, 1000 );

var renderer = new THREE.WebGLRenderer();
renderer.setSize( tamano, tamano );
document.body.appendChild( renderer.domElement );

// load a texture, set wrap mode to repeat
var textureBG = new THREE.TextureLoader().load( "img/fondo.jpg" );
// textureBG.wrapS = THREE.RepeatWrapping;
// textureBG.wrapT = THREE.RepeatWrapping;
// textureBG.repeat.set( 4, 4 );

scene.background = textureBG;

var colorLuz = 0xfbfadd;

var light = new THREE.AmbientLight( colorLuz, 0.1, 100 );
scene.add( light );
var light = new THREE.PointLight( colorLuz, 0.3, 100 ); // arriba
light.position.set( 0, 10, 0 );
scene.add( light );
var light = new THREE.PointLight( colorLuz, 0.5, 100 ); // arriba izq
light.position.set( -10, 10, 0 );
scene.add( light );
var light = new THREE.PointLight( colorLuz, 0.5, 100 ); // arriba der
light.position.set( 10, 10, 0 );
scene.add( light );
var light = new THREE.PointLight( colorLuz, 0.5, 100 ); // arriba atras
light.position.set( 0, 10, -10 );
scene.add( light );
var light = new THREE.PointLight( colorLuz, 0.7, 100 ); // arriba adelante
light.position.set( 0, 10, 10 );
scene.add( light );

// instantiate a loader
var loader = new THREE.OBJLoader();

var objeto;

// load a resource
loader.load(
	// resource URL
	'obj/obj1.obj',
	// 'https://da6npmvqm28oa.cloudfront.net/obj_models/90aeb042-24cb-4334-93b3-95bb03c30183.obj',
	// called when resource is loaded
	function ( object ) {

        cambiarMaterial = function(tipo, valor) {

            switch (tipo) {
                case 't': // textura
                    console.log('textura');
                    var textureLoader = new THREE.TextureLoader();
                    var map = textureLoader.load(valor);
                    var material = new THREE.MeshPhongMaterial({map: map});
                    object.traverse( function( child ) {
                        if ( child instanceof THREE.Mesh ) {
                            child.material = material;
                        }
                    } );
                    break;
                case 'c': //color solido sin textura
                    console.log('color solido');
                    var mat = new THREE.MeshLambertMaterial( {color: valor} );
                    object.traverse( function( child ) {
                        if ( child instanceof THREE.Mesh ) {
                          child.material = mat;
                        }
                    } );
                    break;
            }
        }

        cambiarMaterial('t', textura0);

        objeto = object;

        scene.add( objeto );

	},
	// called when loading is in progresses
	function ( xhr ) {

		console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );

	},
	// called when loading has errors
	function ( error ) {

		console.log( 'An error happened' );

	}
);


var controls = new THREE.OrbitControls( camera, renderer.domElement );

camera.position.set( 0, 0, 1.5 );
controls.target = new THREE.Vector3(0, 0.7, 0);
controls.update();

function animate() {
	requestAnimationFrame( animate );
    if (objeto) objeto.rotation.y += 0.0040;
	renderer.render( scene, camera );
}
animate();
        </script>
    </body>
</html>
