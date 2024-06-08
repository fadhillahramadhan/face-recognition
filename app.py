from flask import Flask, jsonify, request
from flask_cors import CORS
import cv2
import face_recognition
import os
import glob

app = Flask(__name__)
CORS(app)

@app.route('/compare', methods=['POST'])
def compare():
    try:
        image_file = request.files['webcam_image']
        image_path_comparer = 'public/compare/' + image_file.filename
        image_file.save(image_path_comparer)

        images_path = glob.glob(os.path.join('public/images/', "*.*"))
        
        img2 = cv2.imread(image_path_comparer)
        rgb_img2 = cv2.cvtColor(img2, cv2.COLOR_BGR2RGB)
        img_encoding2 = face_recognition.face_encodings(rgb_img2)[0]

        name = 'Unknown'
        best_match_score = 1.0  # Initialize with the maximum distance

        for img_path in images_path:
            img = cv2.imread(img_path)
            rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            img_encoding = face_recognition.face_encodings(rgb_img)[0]

            distance = face_recognition.face_distance([img_encoding], img_encoding2)[0]

            if distance < best_match_score:  # Find the best match
                best_match_score = distance
                name = os.path.basename(img_path)

        accuracy = round((1 - best_match_score) * 100, 2)
        response = jsonify({'person': name, 'accuracy': accuracy})
                
        return response
    except Exception as e:
        response = jsonify({'person': 'Unknown'})
        return response

if __name__ == '__main__':
    app.run(debug=True)  # Run the app in debug mode
