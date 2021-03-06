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
        </div>

        <div id="aqui">
        </div>

        <script src="https://threejs.org/build/three.js"></script>
        <script src="{{ asset('js/OBJLoader.js') }}"></script>
        <script src="{{ asset('js/OrbitControls.js') }}"></script>
        <script>
            var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera(60, 1, 1, 1000);
camera.position.set(0, 0, 10);
var renderer = new THREE.WebGLRenderer({
  antialias: true
});
var canvas = renderer.domElement
document.body.appendChild(canvas);


var geom = new THREE.CylinderBufferGeometry(2, 5, 20, 16, 4, true);

var rev = true;

var cols = [{
  stop: 0,
  color: new THREE.Color(0xf7b000)
}, {
  stop: .25,
  color: new THREE.Color(0xdd0080)
}, {
  stop: .5,
  color: new THREE.Color(0x622b85)
}, {
  stop: .75,
  color: new THREE.Color(0x007dae)
}, {
  stop: 1,
  color: new THREE.Color(0x77c8db)
}];

setGradient(geom, cols, 'z', rev);

function setGradient(geometry, colors, axis, reverse) {

  geometry.computeBoundingBox();

  var bbox = geometry.boundingBox;
  var size = new THREE.Vector3().subVectors(bbox.max, bbox.min);

  var vertexIndices = ['a', 'b', 'c'];
  var face, vertex, normalized = new THREE.Vector3(),
    normalizedAxis = 0;

  for (var c = 0; c < colors.length - 1; c++) {

    var colorDiff = colors[c + 1].stop - colors[c].stop;

    for (var i = 0; i < geometry.faces.length; i++) {
      face = geometry.faces[i];
      for (var v = 0; v < 3; v++) {
        vertex = geometry.vertices[face[vertexIndices[v]]];
        normalizedAxis = normalized.subVectors(vertex, bbox.min).divide(size)[axis];
        if (reverse) {
          normalizedAxis = 1 - normalizedAxis;
        }
        if (normalizedAxis >= colors[c].stop && normalizedAxis <= colors[c + 1].stop) {
          var localNormalizedAxis = (normalizedAxis - colors[c].stop) / colorDiff;
          face.vertexColors[v] = colors[c].color.clone().lerp(colors[c + 1].color, localNormalizedAxis);
        }
      }
    }
  }
}

var mat = new THREE.MeshBasicMaterial({
  vertexColors: THREE.VertexColors,
  wireframe: true
});
var obj = new THREE.Mesh(geom, mat);
scene.add(obj);



render();

function resize(renderer) {
  const canvas = renderer.domElement;
  const width = canvas.clientWidth;
  const height = canvas.clientHeight;
  const needResize = canvas.width !== width || canvas.height !== height;
  if (needResize) {
    renderer.setSize(width, height, false);
  }
  return needResize;
}

function render() {
  if (resize(renderer)) {
    camera.aspect = canvas.clientWidth / canvas.clientHeight;
    camera.updateProjectionMatrix();
  }
  renderer.render(scene, camera);
  obj.rotation.y += .01;
  requestAnimationFrame(render);
}

        </script>
    </body>
</html>
