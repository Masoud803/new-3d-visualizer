function initTdvVisualizer(containerId, data) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const wrapper = container.querySelector('.tdv-canvas-wrapper');
    const loading = container.querySelector('.tdv-loading-overlay');
    const switchBtns = container.querySelectorAll('.tdv-switch-btn');

    const models = data.models;
    const defaultBg = data.defaultBg;

    if (!models || models.length === 0) return;

    let scene, camera, renderer, controls, currentModel;
    let environmentTexture = null;

    // Initialize Three.js Scene
    scene = new THREE.Scene();

    // Camera
    camera = new THREE.PerspectiveCamera(45, wrapper.clientWidth / wrapper.clientHeight, 0.1, 1000);
    camera.position.set(0, 1.5, 5);

    // Renderer
    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(wrapper.clientWidth, wrapper.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.outputEncoding = THREE.sRGBEncoding;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.0;
    wrapper.appendChild(renderer.domElement);

    // Controls
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.minDistance = 1;
    controls.maxDistance = 20;

    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 10, 7);
    scene.add(directionalLight);

    // Resize Handler
    window.addEventListener('resize', () => {
        if (!wrapper) return;
        camera.aspect = wrapper.clientWidth / wrapper.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(wrapper.clientWidth, wrapper.clientHeight);
    });

    const gltfLoader = new THREE.GLTFLoader();
    const textureLoader = new THREE.TextureLoader();

    function loadModel(index) {
        if (loading) loading.style.display = 'flex';

        const modelData = models[index];
        if (!modelData || !modelData.file) {
            if (loading) loading.style.display = 'none';
            return;
        }

        // Remove existing model
        if (currentModel) {
            scene.remove(currentModel);
            currentModel = null;
        }

        // Handle Background Texture
        const bgUrl = modelData.bg || defaultBg;
        if (bgUrl) {
            textureLoader.load(bgUrl, function(texture) {
                texture.mapping = THREE.EquirectangularReflectionMapping;
                texture.encoding = THREE.sRGBEncoding;
                scene.background = texture;
                scene.environment = texture; // Lighting from environment
            });
        } else {
            scene.background = new THREE.Color(0xf0f0f0); // Fallback color
            scene.environment = null;
        }

        // Load GLB/GLTF
        gltfLoader.load(
            modelData.file,
            function(gltf) {
                currentModel = gltf.scene;

                // Center the model
                const box = new THREE.Box3().setFromObject(currentModel);
                const center = box.getCenter(new THREE.Vector3());
                currentModel.position.x += (currentModel.position.x - center.x);
                currentModel.position.y += (currentModel.position.y - center.y);
                currentModel.position.z += (currentModel.position.z - center.z);

                scene.add(currentModel);
                if (loading) loading.style.display = 'none';
            },
            function(xhr) {
                // Progress
                const percent = (xhr.loaded / xhr.total) * 100;
                if (loading) loading.innerHTML = '<span>Loading ' + Math.round(percent) + '%</span>';
            },
            function(error) {
                console.error('Error loading model', error);
                if (loading) loading.innerHTML = '<span>Error loading model</span>';
            }
        );
    }

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    // Load initial model
    loadModel(0);
    animate();

    // Setup switch buttons
    if (switchBtns) {
        switchBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'));
                loadModel(idx);
            });
        });
    }
}
