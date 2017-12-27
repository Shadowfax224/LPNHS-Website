<!Doctype HTML>
<html>
    <body>
        <p>Checking authentication...</p>
        <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
        <script>
            // Initialize Firebase
            var config = {
                apiKey: "AIzaSyByQW8Cyp9yAIMm5xCrNZqF-5kqJ-w6g-4",
                authDomain: "nhs-project-test.firebaseapp.com",
                databaseURL: "https://nhs-project-test.firebaseio.com",
                projectId: "nhs-project-test",
                storageBucket: "nhs-project-test.appspot.com",
                messagingSenderId: "239221174231"
            };
            firebase.initializeApp(config);
            
            var destination = getParameterByName("destination");
            
            firebase.auth().onAuthStateChanged(firebaseUser =>{
                if(firebaseUser){
                    window.location.replace("https://nhs-project-test.firebaseapp.com/" + destination);
                }
                else{
                    window.location.replace("https://nhs-project-test.firebaseapp.com/login.html?destination=" + destination);
                }
            });

            // Parse the URL parameter
            function getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            }
        </script>
    </body>
</html>