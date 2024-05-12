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

    .responsive-video {
        width: 100%;
    }

    .responsive-canvas {
        width: 100%;
    }
</style>

<?= $this->section('content') ?>


<?= $this->endSection() ?>


<?= $this->include('templates/main') ?>