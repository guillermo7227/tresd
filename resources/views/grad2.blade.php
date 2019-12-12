<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>


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
            <a href="javascript:cambiarMaterial('gradient')">grdient</a> |
        </div>

        <div id="aqui">
        </div>

        <script src="https://threejs.org/build/three.js"></script>
        <script src="{{ asset('js/OBJLoader.js') }}"></script>
        <script src="{{ asset('js/OrbitControls.js') }}"></script>
        <script>

            var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 1000);
camera.position.set(0, 60, 250);
var renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setClearColor(0x404040);
document.body.appendChild(renderer.domElement);

var controls = new THREE.OrbitControls(camera, renderer.domElement);
controls.target.set(0, 60, 0);
controls.update();

var mat = new THREE.MeshBasicMaterial({
  wireframe: false,
  vertexColors: THREE.VertexColors
}); // the same material for all the children of the object

var loader = new THREE.OBJLoader();
loader.load('https://threejs.org/examples/models/obj/male02/male02.obj', function(obj) {

  var size = new THREE.Vector3();
  var box3 = new THREE.Box3().setFromObject(obj);
  box3.getSize(size);
  console.log(size);

  var v3 = new THREE.Vector3(); // for re-use

  var c = [
    new THREE.Color(0x00ff00),
    new THREE.Color(0xff0000)
  ];
  var cTemp = new THREE.Color(); // for re-use

  obj.traverse(child => {
    if (child.isMesh) {

      let colors = []; // array for color values of the current mesh's geometry

      let pos = child.geometry.attributes.position;

      for(let i = 0; i < pos.count; i++){

        v3.fromBufferAttribute(pos, i);

        obj.localToWorld(v3); // box3 is in world coords so we have to convert coortinates of the vertex from local to world

        let a = (v3.y - box3.min.y) / size.y; // find the value in range 0..1

        cTemp.copy(c[0]).lerp(c[1], a); // lerp the colors

        colors.push(cTemp.r, cTemp.g, cTemp.b); // save values in the array

        child.geometry.setAttribute("color", new THREE.BufferAttribute(new Float32Array(colors), 3)); // add a buffer attribute for colors

        child.material = mat;
      }
    }
  });
  scene.add(obj);
})

renderer.setAnimationLoop(() => {
  renderer.render(scene, camera)
});


        </script>
    </body>
</html>
