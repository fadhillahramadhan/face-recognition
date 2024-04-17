from flask import Flask, render_template, Response
import cv2
from simple_facerec import SimpleFacerec

app = Flask(__name__)  # Inisialisasi aplikasi Flask
sfr = SimpleFacerec()  # Inisialisasi objek SimpleFacerec
sfr.load_encoding_images("images/")  # Memuat gambar untuk encoding

@app.route('/')  # Route untuk halaman utama
def index():
    return render_template('index.html')  # Render template index.html

def gen_frames():  # Fungsi untuk menghasilkan frame
    cap = cv2.VideoCapture(0)  # Mengakses kamera
    while True:
        success, frame = cap.read()  # Membaca frame dari kamera
        if not success:
            break
        else:
            face_locations, face_names = sfr.detect_known_faces(frame)  # Mendeteksi wajah yang dikenal
            for face_loc, name in zip(face_locations, face_names):
                y1, x2, y2, x1 = face_loc[0], face_loc[1], face_loc[2], face_loc[3]
                cv2.putText(frame, name, (x1, y1 - 10), cv2.FONT_HERSHEY_DUPLEX, 1, (0, 0, 200), 2)
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 0, 200), 4)
            ret, buffer = cv2.imencode('.jpg', frame)
            frame = buffer.tobytes()
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

@app.route('/video_feed')  # Route untuk feed video
def video_feed():
    return Response(gen_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    app.run(debug=True)  # Menjalankan aplikasi dalam mode debug
