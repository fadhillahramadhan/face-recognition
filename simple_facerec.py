# Import library untuk pengenalan wajah dan pemrosesan gambar
import face_recognition
import cv2
import os
import glob
import numpy as np

class SimpleFacerec:
    def __init__(self):
        self.known_face_encodings = []  # List encoding wajah yang dikenal
        self.known_face_names = []  # List nama wajah yang dikenal

        # Resize frame untuk kecepatan yang lebih cepat
        self.frame_resizing = 0.25

    def load_encoding_images(self, images_path):
        """
        Memuat gambar encoding dari path
        :param images_path:
        :return:
        """
        # Memuat Gambar
        images_path = glob.glob(os.path.join(images_path, "*.*"))

        print("{} gambar encoding ditemukan.".format(len(images_path)))

        # Menyimpan encoding gambar dan nama
        for img_path in images_path:
            img = cv2.imread(img_path)
            rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)

            # Dapatkan nama file dari jalur file awal.
            basename = os.path.basename(img_path)
            (filename, ext) = os.path.splitext(basename)
            # Dapatkan encoding
            img_encoding = face_recognition.face_encodings(rgb_img)[0]

            # Simpan nama file dan encoding file
            self.known_face_encodings.append(img_encoding)
            self.known_face_names.append(filename)
        print("Gambar encoding dimuat")

    def detect_known_faces(self, frame):
        small_frame = cv2.resize(frame, (0, 0), fx=self.frame_resizing, fy=self.frame_resizing)
        # Temukan semua wajah dan encoding wajah dalam frame video saat ini
        # Konversi gambar dari warna BGR (yang digunakan OpenCV) ke warna RGB (yang digunakan face_recognition)
        rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        face_names = []
        for face_encoding in face_encodings:
            # Periksa apakah wajah cocok dengan wajah yang dikenal
            matches = face_recognition.compare_faces(self.known_face_encodings, face_encoding)
            name = "Tidak Dikenal"

            # Atau gunakan wajah yang dikenal dengan jarak terkecil ke wajah baru
            face_distances = face_recognition.face_distance(self.known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)
            if matches[best_match_index]:
                name = self.known_face_names[best_match_index]
            face_names.append(name)

        # Konversi ke array numpy untuk menyesuaikan koordinat dengan perubahan ukuran frame dengan cepat
        face_locations = np.array(face_locations)
        face_locations = face_locations / self.frame_resizing
        return face_locations.astype(int), face_names
