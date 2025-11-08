 var firebaseConfig = {
      apiKey: "AIzaSyD6RSVw3rKiuw29nYlaGp9u_byWp9PzjIk",
      authDomain: "karyarat-6e69b.firebaseapp.com",
      projectId: "karyarat-6e69b",
      storageBucket: "karyarat-6e69b.appspot.com",
      messagingSenderId: "7085581195",
      appId: "1:7085581195:web:dba002a0b3de301d88858e",
      measurementId: "G-K8Z3RKPTG1"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

function FirebaseMessagingRegistration() {
 navigator.serviceWorker.register('./firebase-messaging-sw.js')
.then((registration) => {
  messaging.useServiceWorker(registration);
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log('Firebase Web Token: '+token);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: 'save_firebase_token',
                    type: 'POST',
                    data: {
                        type:'web',
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        console.log(response);
                        if(response.status == 'success'){
                          console.log('firebase_token saved successfully.');
                        }else if(response.status == 'error'){
                          console.log('firebase_token is not update!');
                        }
                    },
                    error: function (err) {
                        console.log('firebase Token Error'+ err);
                    },
                });
            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });

            });
     }
    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });
FirebaseMessagingRegistration();