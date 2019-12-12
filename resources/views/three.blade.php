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
        <div enlaces>
            <a href="javascript:cambiarMaterial('t', 'img/tex0.jpg')">material 0</a> |
            <a href="javascript:cambiarMaterial('t', 'img/tex1.jpg')">material 1</a> |
            <a href="javascript:cambiarMaterial('t', 'img/tex2.jpg')">material 2</a> |
            <a href="javascript:cambiarMaterial('t', 'img/tex3.jpg')">material 3</a> |
            <a href="javascript:cambiarMaterial('t', 'img/tex4.jpg')">material 4</a> |
            <a href="javascript:cambiarMaterial('c', 'rgb(255, 0, 0)')">rojo</a> |
            <a href="javascript:cambiarMaterial('c', 'rgb(0, 255, 0)')">verde</a> |
            <a href="javascript:cambiarMaterial('c', 'rgb(0, 0, 255)')">azul</a> |
        </div>

        <script src="https://threejs.org/build/three.js"></script>
        <script src="{{ asset('js/OBJLoader.js') }}"></script>
        <script src="{{ asset('js/OrbitControls.js') }}"></script>
        <script>

var textura0 = '/img/tex0.jpg';
var cambiarMaterial;
var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / 300, 0.1, 1000 );

var renderer = new THREE.WebGLRenderer();
renderer.setSize( window.innerWidth, 300 );
document.body.appendChild( renderer.domElement );


var light = new THREE.AmbientLight( 0xffffff, 1, 100 );
scene.add( light );

// instantiate a loader
var loader = new THREE.OBJLoader();

var objeto;

// load a resource
loader.load(
	// resource URL
	'https://da6npmvqm28oa.cloudfront.net/obj_models/90aeb042-24cb-4334-93b3-95bb03c30183.obj',
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
                case 'c': //color
                    console.log('color');
                    cambiarMaterial('t', textura0);
                    var mat = new THREE.MeshPhongMaterial({
                        color: 0x0044ff,
                        shading: THREE.FlatShading,
                        side: THREE.DoubleSide
                    });
                    object.traverse( function( child ) {
                        if ( child instanceof THREE.Mesh ) {
                          var color = new THREE.Color(valor);
                          child.material.color = color;
                        }
                    } );
                    break;
            }
        }

        cambiarMaterial('t', textura0);

        objeto = object;

        scene.add( objeto );

        // gradient
// var mat = new THREE.MeshBasicMaterial({
//   wireframe: false,
//   vertexColors: THREE.VertexColors
// }); // the same material for all the children of the object
//   var size = new THREE.Vector3();
//   var box3 = new THREE.Box3().setFromObject(object);
//   box3.getSize(size);
//   console.log(size);
//
//   var v3 = new THREE.Vector3(); // for re-use
//
//   var c = [
//     new THREE.Color(0x00ff00),
//     new THREE.Color(0xff0000)
//   ];
//   var cTemp = new THREE.Color(); // for re-use
//
//   object.traverse(child => {
//     if (child.isMesh) {
//
//       let colors = []; // array for color values of the current mesh's geometry
//
//       let pos = child.geometry.attributes.position;
//
//       for(let i = 0; i < pos.count; i++){
//
//         v3.fromBufferAttribute(pos, i);
//
//         object.localToWorld(v3); // box3 is in world coords so we have to convert coortinates of the vertex from local to world
//
//         let a = (v3.y - box3.min.y) / size.y; // find the value in range 0..1
//
//         cTemp.copy(c[0]).lerp(c[1], a); // lerp the colors
//
//         colors.push(cTemp.r, cTemp.g, cTemp.b); // save values in the array
//
//         child.geometry.setAttribute("color", new THREE.BufferAttribute(new Float32Array(colors), 3)); // add a buffer attribute for colors
//
//         child.material = mat;
//       }
//     }
//   });
//   scene.add(object);
// // fin gradient
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

camera.position.set( 0, 0, 2 );
controls.target = new THREE.Vector3(0, 1, 0);
controls.update();

function animate() {
	requestAnimationFrame( animate );
    objeto.rotation.y += 0.0010;
	renderer.render( scene, camera );
}
animate();
        </script>
    </body>
</html>
