from flask import Flask, render_template, Response, jsonify, request
import cv2
import imutils
import face_recognition
import os
import glob
from simple_facerec import SimpleFacerec
from concurrent.futures import ThreadPoolExecutor

import numpy as np

app = Flask(__name__)  # Initialize Flask app
sfr = SimpleFacerec()  # Initialize SimpleFacerec object
sfr.load_encoding_images("public/images/")  # Load images for encoding

@app.route('/')  # Route for the main page
def index():
    return render_template('index.html')  # Render template index.html and pass detected_people's values to it


def gen_frames(unique_code):  # Function to generate frames with a unique code
    global detected_people  # Use the global variable
    cap = cv2.VideoCapture(0)  # Access the camera
    while True:
        success, frame = cap.read()  # Read frame from the camera
        if not success:
            break
        else:
            frame = imutils.resize(frame, width=480)  # Resize frame
            face_locations, face_names = sfr.detect_known_faces(frame)  # Detect known faces
            for face_loc, name in zip(face_locations, face_names):
                y1, x2, y2, x1 = face_loc[0], face_loc[1], face_loc[2], face_loc[3]
                cv2.putText(frame, name, (x1, y1 - 10), cv2.FONT_HERSHEY_DUPLEX, 1, (0, 0, 200), 2)
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 0, 200), 4)
                detected_people[unique_code] = name  # Associate the name with the unique code

            ret, buffer = cv2.imencode('.jpg', frame)
            frame = buffer.tobytes()

            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

@app.route('/video_feed/<unique_code>')  # Route for video feed with a unique code in the URL
def video_feed(unique_code):
    return Response(gen_frames(unique_code), mimetype='multipart/x-mixed-replace; boundary=frame')


# Function to compare faces
# Function to compare faces
def compare_faces(known_encodings, img_encoding2):
    for img_path, known_encoding in known_encodings.items():
        result = face_recognition.compare_faces([known_encoding], img_encoding2)
        if result[0]:
            return os.path.splitext(os.path.basename(img_path))[0]  # Extracts name without extension
    return 'Unknown'

@app.route('/compare', methods=['POST'])
def compare():
    # Get the uploaded image file from the request
    image_file = request.files['webcam_image']
    image_path_comparer = 'public/compare/' + image_file.filename
    image_file.save(image_path_comparer)

    # Load and encode known images
    known_encodings = {}
    images_path = glob.glob(os.path.join('public/images/', "*.*"))
    
    for img_path in images_path:
        img = cv2.imread(img_path)
        rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        img_encoding = face_recognition.face_encodings(rgb_img)
        if img_encoding:
            known_encodings[img_path] = img_encoding[0]

    img2 = cv2.imread(image_path_comparer)
    rgb_img2 = cv2.cvtColor(img2, cv2.COLOR_BGR2RGB)
    img_encoding2 = face_recognition.face_encodings(rgb_img2)
    
    if not img_encoding2:
        return jsonify({'person': 'Unknown'})

    img_encoding2 = img_encoding2[0]

    # Compare faces using parallel processing
    with ThreadPoolExecutor() as executor:
        result = executor.submit(compare_faces, known_encodings, img_encoding2)
        current_name = result.result()

    return jsonify({'person': current_name})


if __name__ == '__main__':
    app.run(debug=True)  # Run the app in debug mode

