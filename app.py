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




@app.route('/compare', methods=['POST'])
def compare():
    try:
        image_file = request.files['webcam_image']
        image_path_comparer = 'public/compare/' + image_file.filename
        image_file.save(image_path_comparer)

        images_path = glob.glob(os.path.join('public/images/', "*.*"))

        name = ''

        for img_path in images_path:
            img = cv2.imread(img_path)
            rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            img_encoding = face_recognition.face_encodings(rgb_img)[0]
            
            img2 = cv2.imread(image_path_comparer)
            rgb_img2 = cv2.cvtColor(img2, cv2.COLOR_BGR2RGB)

            img_encoding2 = face_recognition.face_encodings(rgb_img2)[0]

            results = face_recognition.compare_faces([img_encoding], img_encoding2)

            if results[0] == True:
                name = os.path.basename(img_path)

        response = jsonify({'person': name})
    except Exception as e:
        print(e)
        response = jsonify({'person': 'unknown'})

            
    return response


if __name__ == '__main__':
    app.run(debug=True)  # Run the app in debug mode

