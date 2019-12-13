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
var textureBG = new THREE.TextureLoader().load( "https://raw.githubusercontent.com/guillermo7227/tresd/master/public/img/fondo.jpg" );

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
	'obj/obj2.obj',
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

        var controls = new THREE.OrbitControls( camera, renderer.domElement );

        fitCameraToObject(camera, objeto, 6.5, controls);

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


function animate() {
	requestAnimationFrame( animate );
    // if (objeto) objeto.rotation.y += 0.0040;
	renderer.render( scene, camera );
}
animate();


const fitCameraToObject = function ( camera, object, offset, controls ) {

    offset = offset || 1.25;

    const boundingBox = new THREE.Box3();

    // get bounding box of object - this will be used to setup controls and camera
    boundingBox.setFromObject( object );

    const center = boundingBox.getCenter();

    const size = boundingBox.getSize();

    // get the max side of the bounding box (fits to width OR height as needed )
    const maxDim = Math.max( size.x, size.y, size.z );
    const fov = camera.fov * ( Math.PI / 180 );
    let cameraZ = Math.abs( maxDim / 4 * Math.tan( fov * 2 ) );

    cameraZ *= offset; // zoom out a little so that objects don't fill the screen

    // camera.position.z = cameraZ;
    camera.position.z = center.z + cameraZ
    camera.position.y = center.y;

    const minZ = boundingBox.min.z;
    const cameraToFarEdge = ( minZ < 0 ) ? -minZ + cameraZ : cameraZ - minZ;

    camera.far = cameraToFarEdge * 3;
    camera.updateProjectionMatrix();

    if ( controls ) {

      // set camera to rotate around center of loaded object
      controls.target = center;

      // prevent camera from zooming out far enough to create far plane cutoff
      controls.maxDistance = cameraToFarEdge * 2;

      controls.saveState();

    } else {

        camera.lookAt( center )

   }
}
        </script>
    </body>
</html>
