<style>
    .loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    #canvas {
        display: none;
    }

    @media screen and (max-width: 768px) {

        .responsive-video {
            width: 100%;
            height: max-content;
        }

        .responsive-canvas {
            width: 100%;
            height: max-content;
        }
    }

    @media screen and (min-width: 768px) {

        .responsive-video {
            height: calc(100vh - 500px);
        }

        .responsive-canvas {
            height: calc(100vh - 500px);
        }
    }
</style>

<?= $this->section('content') ?>


<div class="card">
    <div class="card-body ">
        <h5 class="card-title align-self-start">Presensi</h5>
        <p class="card-text align-self-start">Ini adalah halaman presensi, silahkan klik tombol dibawah ini untuk melakukan presensi</p>
    </div>
    <div class="row p-5">

        <div class="col-12 lg-12 md-12 sm-12 d-flex flex-column justify-content-center align-items-center">
            <video id="webcam" class="responsive-video" autoplay></video>
            <canvas id="canvas" class="responsive-canvas"></canvas>


        </div>
        <div class="col-12 lg-12 md-12 sm-12">
            <!-- Other content in the second column -->
        </div>
        <div class="col-12 mt-5 text-center">
            <button class="btn btn-primary btn-block" id="captureBtn" onclick="captureAndCompare()">Presensi</button>
            <div id="result"></div>
        </div>
    </div>

</div>


<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    let stream; // Global variable to store the video stream
    const loadingElement = document.getElementById('loading');
    const canvas = document.getElementById('canvas');
    const video = document.getElementById('webcam');
    const resultElement = document.getElementById('result');
    const captureBtn = document.getElementById('captureBtn');

    function showLoading() {
        loadingElement.style.display = 'block';
        captureBtn.disabled = true; // Disable button during loading
    }

    function hideLoading() {
        loadingElement.style.display = 'none';
        captureBtn.disabled = false; // Enable button after loading
    }

    function startVideo() {
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(str) {
                video.srcObject = str;
                stream = str; // Store the stream in the global variable
            })
            .catch(function(err) {
                console.error('Error accessing webcam:', err);
            });
    }

    function stopVideo() {
        if (stream) {
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }
    }

    function captureAndCompare() {
        showLoading(); // Show loading indicator

        // Draw video frame on canvas
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(blob => {
            const formData = new FormData();
            formData.append('webcam_image', blob, 'webcam_image.jpg');

            fetch('http://127.0.0.1:5000    /compare', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    resultElement.innerText = `Person: ${data.person}`;
                    hideLoading(); // Hide loading indicator
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading(); // Hide loading indicator on error
                });
        }, 'image/jpeg');
    }

    // Start the video when the page loads
    window.onload = startVideo;
</script>
<?= $this->endSection() ?>

<?= $this->include('templates/main') ?>