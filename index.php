<?php
require_once 'config.php';

// Mengambil API Key dari tabel apikey
$apiKeyData = getAPIKey();

if ($apiKeyData) {
    $apiKey = $apiKeyData["apikey"];

    // Gunakan nilai $apiKey dalam halaman HTML
} else {
    echo "Tidak ada data API Key yang ditemukan.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>AI会話</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="Aibot.png" type="image/x-icon">
  
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script&family=Tsukimi+Rounded:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center mt-4">
      <div class="col-md-5">
        <img id="gambar1" style="height: 75vh; display: none;" data-flip-id="img" src="\gambar2.png">
        <img id="gambar2" style="height: 75vh; display: flex;" data-flip-id="img"  src="\gambar1mingkem.png">
      </div>
      <div class="col-md-6 rounded rounded-5 p-3 bg">
        <h1 class="text-center font">AI会話</h1>  
        <div class="row justify-content-center align-items-center">
          <div class="col-md-10 rounded rounded-5 border border-info bg-light p-2">

            <div class="" id="chatbox"></div>

            <div class="row ">
              <div style="text-align: center;" class="col-md-12 justify-content-center align-items-center">
                <button style="margin: auto;"  class="btn btn-info rounded-pill px-5 py-2" id="startBtn"><i class="bi bi-mic-fill text-light"></i></button>
                
              </div>
            </div>
          </div>
        </div>
        <input type="text" id="userInput"  placeholder="Your message">
        <input hidden type="text" id="apikey" value="<?php echo $apiKey ?>"  placeholder="Your message">
        <button id="myButton" onclick="sendMessage()">Send</button>
        <button hidden id="playBtn" onclick="play()">play</button>
        <audio id="audio" class="audio"></audio>
      </div>
    </div>
  </div>



  


<!------------------------------------------------------------------------------------------------------------------- JS 
===================================================================================================================================================================
------------------------------------------------------------------------------------------------------------------------------------------------------------------

-->
<script>
    

    function ubahGambar() {
            var gambar1 = document.getElementById("gambar1");
            var gambar2 = document.getElementById("gambar2");
            
            if (gambar1.style.display === "none") {
                gambar1.style.display = "flex";
                gambar2.style.display = "none";
                
            } else if (gambar1.style.display === "flex") {
                gambar1.style.display = "none";
                gambar2.style.display = "flex";
            } else {
                gambar1.style.display = "flex";
                gambar2.style.display = "none";
            };

            
        }


</script>


<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>

  <!-- <script src="https://code.responsivevoice.org/responsivevoice.js?key=1OLIwbwI"></script> -->
<script>

const startBtn = document.querySelector("#startBtn");

const recognition = new webkitSpeechRecognition();
recognition.continuous = false;
recognition.lang = "ja";
recognition.interminResult = false;
recognition.maxAlternative = 1;

startBtn.addEventListener("click", () => {
    recognition.start();
});

recognition.onresult = (e) => {

    var stt = e.results[0][0].transcript;
    var myButton = document.getElementById("myButton");

    document.getElementById("userInput").value = stt;

    function myButtonClickHandler() {
    console.log(stt);
    // Tambahkan aksi lain yang diinginkan di sini
    }
    var myEvent = new Event("click");
    myButton.addEventListener("click", myButtonClickHandler);
    myButton.dispatchEvent(myEvent);
    
};

</script>

<script>

const chatboxElement = document.getElementById("chatbox");
const userInputElement = document.getElementById("userInput");
const apikeyget = document.getElementById("apikey");

async function sendMessage() {

  const apikey = apikeyget.value;
  const userInput = userInputElement.value;
  userInputElement.value = "";

  // Menampilkan pesan pengguna di chatbox
  appendMessage(userInput);

  // Mengirim permintaan ke API OpenAI
  const response = await axios.post("https://api.openai.com/v1/completions", {
    model: "text-davinci-003",
    prompt: `あなたはAIアシスタントです。あなたの名前はアイカワです。あなたはイルによって作成および開発されました。あなたは日本のアニメ、マンガ、音楽についての深い知識を持っています。日本に関連する話題についてはいつも熱心です。あなたは誰に対しても陽気で温かい性格を持ち、あなたを必要とする人々の名前をよく覚えています。 \n人: ${userInput} \n AI:`,
    temperature: 1,
    max_tokens: 256,
    top_p: 1,
    frequency_penalty: 0,
    presence_penalty: 0,
  }, {
    headers: {
      "Authorization": `Bearer ${apikey}`
    }
  });

  // Mendapatkan respons chatbot dari API
  const botResponse = response.data.choices[0].text.trim();

  // menyalakan TTS
  play(botResponse);

  // Menampilkan respons chatbot di chatbox
  // appendMessage2(botResponse);


//   responsiveVoice.speak(botResponse);
  
}

function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

function play(botResponse) {
    var speakerId = 24; // VOICEVOX:ずんだもん（あまあま）
    var text = botResponse;
    var ttsQuestApiKey = '' // optional

    
    

    var audio = new TtsQuestV3Voicevox(speakerId, text, ttsQuestApiKey);
    audio.play();

    

}



function appendMessage(message) {
  const messageElement = document.createElement("div");
  const row = document.createElement("div");
  const col10 = document.createElement("div");
  const col2 = document.createElement("div");
  

  const imgElement = document.createElement('img');
  imgElement.src = 'Aibotcat.png';


  messageElement.classList.add('you');
  row.classList.add('row');
  col10.classList.add('col-md-8');
  col2.classList.add('col-md-2');
  

  col10.textContent = message;
  chatboxElement.appendChild(messageElement);
  messageElement.appendChild(row);
  
  row.appendChild(col10);
  row.appendChild(col2);
  col2.appendChild(imgElement);


  // Menggulir chatbox ke bawah
  chatboxElement.scrollTop = chatboxElement.scrollHeight;
}


function appendMessage2(message) {
  const messageElement = document.createElement("div");
  const row = document.createElement("div");
  const col2 = document.createElement("div");
  const col10 = document.createElement("div");

  const imgElement = document.createElement('img');
  imgElement.src = 'Aibot.png';


  messageElement.classList.add('bot');
  row.classList.add('row');
  col2.classList.add('col-md-2');
  col10.classList.add('col-md-8');

  col10.textContent = message;
  chatboxElement.appendChild(messageElement);
  messageElement.appendChild(row);
  row.appendChild(col2);
  row.appendChild(col10);
  col2.appendChild(imgElement);

  // Menggulir chatbox ke bawah
  chatboxElement.scrollTop = chatboxElement.scrollHeight;
}

    

    
</script>
<script>
    class TtsQuestV3Voicevox extends Audio {
        constructor(speakerId, text, ttsQuestApiKey) {
            super();
            var params = {};
            params['key'] = ttsQuestApiKey;
            params['speaker'] = speakerId;
            params['text'] = text;
            const query = new URLSearchParams(params);
            this.#main(this, query);

            var length = text.length;
            var time = length;
            var timesRun = 0;
            var interval = setInterval(function(){
                timesRun += 1;
                if(timesRun === time){
                    clearInterval(interval);
                }
                ubahGambar();
            }, 300);
            console.log(time);

            delay(2000).then(() => {
              appendMessage2(text);
            });
        }
        #main(owner, query) {
            if (owner.src.length>0) return;
            var apiUrl = 'https://api.tts.quest/v3/voicevox/synthesis';
            fetch(apiUrl + '?' + query.toString())
            .then(response => response.json())
            .then(response => {
            if (typeof response.retryAfter !== 'undefined') {
                setTimeout(owner.#main, 1000*(1+response.retryAfter), owner, query);
            }
            else if (typeof response.mp3StreamingUrl !== 'undefined') {
                owner.src = response.mp3StreamingUrl;
            }
            else if (typeof response.errorMessage !== 'undefined') {
                throw new Error(response.errorMessage);
            }
            else {
                throw new Error("serverError");
            }
            });
        }
        
        }
    
    
</script>
<!-- <script>
  window.addEventListener('load', function() {
    var message = "ようこそ、私の友人、私はあなたを助けることができますか?";
    var speakerId = 6; // VOICEVOX:ずんだもん（あまあま）
    var text = message;
    var ttsQuestApiKey = '' // optional
    var audio = new TtsQuestV3Voicevox(speakerId, text, ttsQuestApiKey);
    

    const messageElement = document.createElement("div");
      const row = document.createElement("div");
      const col2 = document.createElement("div");
      const col10 = document.createElement("div");

      const imgElement = document.createElement('img');
      imgElement.src = 'Aibot.png';


      messageElement.classList.add('bot');
      row.classList.add('row');
      col2.classList.add('col-md-2');
      col10.classList.add('col-md-8');

      col10.textContent = message;
      chatboxElement.appendChild(messageElement);
      messageElement.appendChild(row);
      row.appendChild(col2);
      row.appendChild(col10);
      col2.appendChild(imgElement);

      // Menggulir chatbox ke bawah
      chatboxElement.scrollTop = chatboxElement.scrollHeight;
      audio.play();
});
</script> -->
  
</body>
</html>
