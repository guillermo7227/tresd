@extends('home')
@section('contenido')
<div>

    <b>Texturas</b> <br/>

    <a href="javascript:cambiarMaterial('t', 'img/mystic/mystic_3d_plantilla.png')">
        Plantilla
    </a> |
    <a href="javascript:cambiarMaterial('t', 'img/mystic/mos_azul.png')">
        Mosaico azul
    </a> |
    <a href="javascript:cambiarMaterial('t', 'img/mystic/madera.jpg')">
        Madera
    </a> |
    <a href="javascript:cambiarMaterial('t', 'img/mystic/base1.png')">
        Base color diferente
    </a> |
</div>

<hr/>

<div>

    <b>Colores sólidos</b> <br/>

    <a href="javascript:cambiarMaterial('c', 0xFF0000)">Rojo</a> |
    <a href="javascript:cambiarMaterial('c', 0x00FF00)">Verde</a> |
    <a href="javascript:cambiarMaterial('c', 0x0000FF)">Azul</a> |
</div>

@endsection
@push('scripts')
<script>

var tamano = 400; // tamaño del canvas
var resolucion = 400; // resolución de los modelos
var textura0 = 'img/t1.png'; // textura que usa el modelo cuando carga la primera vez
var cambiarMaterial; // funcion para cambiar las texturas y el color


// inicialización
var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera( 75, tamano / tamano, 0.1, 1000 );

var renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize( resolucion, resolucion );
document.body.appendChild( renderer.domElement );


// Carga y aplica el fondo
var textureBG = new THREE.TextureLoader().load( "img/transparent-bg.png", function ( texture ) {

    texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
    texture.offset.set( 0, 0 );
    texture.repeat.set( 5, 5 );

} );

scene.background = textureBG;



agregarLuces();



// instantiate a loader
var loader = new THREE.OBJLoader();

var objeto;

// load a resource
loader.load(
	// URL del modelo
	'obj/mystic.obj',
	// 'https://da6npmvqm28oa.cloudfront.net/obj_models/90aeb042-24cb-4334-93b3-95bb03c30183.obj',
	// called when resource is loaded
	function ( object ) {

        // Esta función recibe dos variables
        // tipo: indica el tipo de material. 't' para textura y 'c' para color
        // valor: es la URL de la textura o el valor del color
        cambiarMaterial = function(tipo, valor) {

            switch (tipo) {
                case 't': // textura
                    console.log('textura');
                    var textureLoader = new THREE.TextureLoader();
                    var map = textureLoader.load(valor);
                    var material = new THREE.MeshPhongMaterial({
                        map: map,
                        specular: 0x333333,
                        shineniness: 0.001,
                    });
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

        // aplica la textura por defecto
        cambiarMaterial('t', textura0);

        objeto = object;

        scene.add( objeto );

        var controls = new THREE.OrbitControls( camera, renderer.domElement );
        controls.enableZoom = false;
        controls.enablePan = false;

        // esta función pone la cámara del tamano del modelo
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

    // esta línea pone a rotar el modelo
    // if (objeto) objeto.rotation.y += 0.0040;

	renderer.render( scene, camera );
}
animate();



function agregarLuces() {
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
    light.position.set( 10, 0, 0 );
    scene.add( light );
    var light = new THREE.PointLight( colorLuz, 0.5, 100 ); // atras
    light.position.set( 0, 0, -10 );
    scene.add( light );
    var light = new THREE.PointLight( colorLuz, 0.5, 100 ); // arriba adelante
    light.position.set( 0, 10, 10 );
    scene.add( light );

}

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
@endpush
