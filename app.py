from flask import Flask, render_template, Response, jsonify, request
from flask_cors import CORS


import cv2
import face_recognition
import os
import glob
from concurrent.futures import ThreadPoolExecutor

import numpy as np

app = Flask(__name__) 
CORS(app)


# Function to compare faces
def compare_faces(known_encodings, img_encoding2):
    for img_path, known_encoding in known_encodings.items():
        result = face_recognition.compare_faces([known_encoding], img_encoding2)
        if result[0]:
            return os.path.splitext(os.path.basename(img_path))[0]  
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

    response = jsonify({'person': current_name})
    response.headers.add('Access-Control-Allow-Origin', '*')

    return response


if __name__ == '__main__':
    app.run(debug=True)  # Run the app in debug mode

