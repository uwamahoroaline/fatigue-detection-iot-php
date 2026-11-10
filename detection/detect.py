import cv2, dlib, requests, time
from scipy.spatial import distance

# PHP API URL - change to your server
PHP_URL = "http://192.168.1.100/fatigue/api/alert.php"

# Eye Aspect Ratio - if < 0.25, eye is closed
def eye_aspect_ratio(eye):
    A = distance.euclidean(eye[1], eye[5])
    B = distance.euclidean(eye[2], eye[4])
    C = distance.euclidean(eye[0], eye[3])
    return (A + B) / (2.0 * C)

detector = dlib.get_frontal_face_detector()
predictor = dlib.shape_predictor("shape_predictor_68_face_landmarks.dat")

cap = cv2.VideoCapture(0)
closed_frames = 0
last_alert = 0

while True:
    ret, frame = cap.read()
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    faces = detector(gray)

    for face in faces:
        shape = predictor(gray, face)
        # Left eye 36-41, Right eye 42-47
        left_eye = [(shape.part(i).x, shape.part(i).y) for i in range(36, 42)]
        right_eye = [(shape.part(i).x, shape.part(i).y) for i in range(42, 48)]
        ear = (eye_aspect_ratio(left_eye) + eye_aspect_ratio(right_eye)) / 2.0

        if ear < 0.25:
            closed_frames += 1
            cv2.putText(frame, "EYE CLOSED", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0,0,255), 2)
        else:
            closed_frames = 0

        # If eyes closed for 20+ frames = FATIGUE
        if closed_frames > 20 and time.time() - last_alert > 10:
            print("FATIGUE DETECTED! Sending to PHP...")
            try:
                requests.post(PHP_URL, data={
                    "driver_id": 1,
                    "alert_type": "EYE_CLOSED",
                    "ear_value": ear
                })
                print("Alert sent to operator dashboard!")
            except Exception as e:
                print("Failed to send:", e)
            last_alert = time.time()
            closed_frames = 0

    cv2.imshow("Fatigue Detection", frame)
    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
