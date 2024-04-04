<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <!-- add speaker and viewer button   -->
    <button id="btn-speaker">Join as Speaker</button>
    <button id="btn-viewer">Join as Viewer</button>

    <div id="textDiv"></div>
    <div class="row" id="videoContainer"></div>
    <div id="buttonContainer"></div>

    <!-- add video tag   -->
    <video id="video"></video>

    <script src="index.js"></script>
    <!-- videosdk lib script  -->
    <script src="https://sdk.videosdk.live/js-sdk/0.0.78/videosdk.js"></script>
    <!-- hls lib script  -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js"></script>
	<script>
		// getting Elements from Dom 
const speakerButton = document.getElementById("btn-speaker");
const viewerButton = document.getElementById("btn-viewer");
const buttonContainer = document.getElementById("buttonContainer");
const video = document.getElementById("video");
let videoContainer = document.getElementById("videoContainer");
let textDiv = document.getElementById("textDiv");

// declare variables
let participants = [];
let meeting = null;
let localParticipant;
let localParticipantAudio;
let remoteParticipantId = "";

viewerButton.addEventListener("click", () => {
  return null
});

speakerButton.addEventListener("click", () => {
  viewerButton.style.display = "none";
  speakerButton.style.display = "none";
  textDiv.textContent = "Please wait, we are joining the meeting";

  window.VideoSDK.config("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiIyODNjNjMzZi1mZThkLTQzYTctYmQzZS01ODQ2MzMxOWYyYzEiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTcxMDg3NjQ2MCwiZXhwIjoxNzEwOTYyODYwfQ.g_mieTJ0rSO6IJ1EG0rRqzddEc4Sl79Mzi-iAZsSINM");
  meeting = window.VideoSDK.initMeeting({
    meetingId: "ie0m-za6g-ux0w", // required
    name: "Afrax's Org", // required
    micEnabled: true, // optional, default: true
    webcamEnabled: true, // optional, default: true
    mode:"CONFERENCE"
  });
});
  meeting.join();
   // creating video element
function createVideoElement(pId) {
  let videoElement = document.createElement("video");
  videoElement.classList.add("video-frame");
  videoElement.setAttribute("id", `v-${pId}`);
  videoElement.setAttribute("playsinline", true);
  videoElement.setAttribute("width", "300");
  return videoElement;
}

// creating audio element
function createAudioElement(pId) {
  let audioElement = document.createElement("audio");
  audioElement.setAttribute("autoPlay", "false");
  audioElement.setAttribute("playsInline", "true");
  audioElement.setAttribute("controls", "false");
  audioElement.setAttribute("id", `a-${pId}`);
  return audioElement;
}

// creating local participant
function createLocalParticipant() {
  localParticipant = createVideoElement(meeting.localParticipant.id);
  videoContainer.appendChild(localParticipant);
}

// setting media track
function setTrack(stream, audioElement, participant, isLocal) {
  if (stream.kind == "video") {
    const mediaStream = new MediaStream();
    mediaStream.addTrack(stream.track);
    let videoElm = document.getElementById(`v-${participant.id}`);
    videoElm.srcObject = mediaStream;
    videoElm
      .play()
      .catch((error) =>
        console.error("videoElem.current.play() failed", error)
      );
  }
  if (stream.kind == "audio" && !isLocal) {
    const mediaStream = new MediaStream();
    mediaStream.addTrack(stream.track);
    audioElement.srcObject = mediaStream;
    audioElement
      .play()
      .catch((error) => console.error("audioElem.play() failed", error));
  }
}
speakerButton.addEventListener("click", () => {
// ...
// ...
// ...

// creating local participant
  createLocalParticipant();

// setting local participant stream
  meeting.localParticipant.on("stream-enabled", (stream) => {
    setTrack(
      stream,
      localParticipantAudio,
      meeting.localParticipant,
      (isLocal = true)
    );
  });

// Other participants
  meeting.on("participant-joined", (participant) => {
    if (participant.mode === "CONFERENCE") {
      let videoElement = createVideoElement(participant.id);
      let audioElement = createAudioElement(participant.id);
      remoteParticipantId = participant.id;

      participant.on("stream-enabled", (stream) => {
        setTrack(stream, audioElement, participant, (isLocal = false));
      });
      videoContainer.appendChild(videoElement);
      videoContainer.appendChild(audioElement);
    }
  });

  // participants left
  meeting.on("participant-left", (participant) => {
    let vElement = document.getElementById(`v-${participant.id}`);
    vElement.parentNode.removeChild(vElement);

    let aElement = document.getElementById(`a-${participant.id}`);
    aElement.parentNode.removeChild(aElement);
    //remove it from participant list participantId;
    document.getElementById(`p-${participant.id}`).remove();
  });

})

speakerButton.addEventListener("click", () => {
// ...
// ...
// ...
meeting.on("meeting-joined", () => {
  textDiv.style.display = "none";
  // Create a new button element
  var startHlsBtn = document.createElement("button");
  var stopHlsBtn = document.createElement("button");
  // Set the text and attributes of the new button
  startHlsBtn.textContent = "Start HLS";
  stopHlsBtn.textContent = "Stop HLS";
  startHlsBtn.setAttribute("id", "startHlsBtn");
  stopHlsBtn.setAttribute("id", "stopHlsBtn");
  // Add the new button to the button container
  buttonContainer.appendChild(startHlsBtn);
  buttonContainer.appendChild(stopHlsBtn);
  startHlsBtn.addEventListener("click", () => {
    const config = {
      layout: {
        type: "SPOTLIGHT",
        priority: "PIN",
        gridSize: 9,
      },
      theme: "DEFAULT",
    };
    meeting.startHls(config);
  });
  stopHlsBtn.addEventListener("click", () => {
    meeting.stopHls();
  });
});
})

viewerButton.addEventListener("click", () => {
viewerButton.style.display = "none";
speakerButton.style.display = "none";
textDiv.textContent = "Please wait, we are joining the meeting";
window.VideoSDK.config("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiIyODNjNjMzZi1mZThkLTQzYTctYmQzZS01ODQ2MzMxOWYyYzEiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTcxMDg3NjQ2MCwiZXhwIjoxNzEwOTYyODYwfQ.g_mieTJ0rSO6IJ1EG0rRqzddEc4Sl79Mzi-iAZsSINM");
meeting = window.VideoSDK.initMeeting({
  meetingId: "ie0m-za6g-ux0w", // required
  name: "Afrax's Org", // required
  micEnabled: true, // optional, default: true
  webcamEnabled: true, // optional, default: true
  mode: "VIEWER",
});
meeting.join();
meeting.on("meeting-joined", () => {
  textDiv.textContent = "HLS is not started yet or stopped";
});
meeting.on("hls-state-changed", (data) => {
  console.log("hls-state-changed", data);
  const { status } = data;
  textDiv.textContent = status;
  if (status === "HLS_STARTING") {
    console.log("Meeting Hls is starting");
  } else if (status === "HLS_STARTED") {
    console.log("Meeting Hls is started");
  } else if (status === "HLS_PLAYABLE") {
    // when hls is playable you will receive downstreamUrl
    console.log("Meeting Hls is playable");
    const { downstreamUrl } = data;
    if (Hls.isSupported()) {
      var hls = new Hls();
      hls.loadSource(downstreamUrl);
      hls.attachMedia(video);
      hls.on(Hls.Events.MANIFEST_PARSED, function () {
        video.play();
      });
    } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
      video.src = downstreamUrl;
      video.addEventListener("canplay", function () {
        video.play();
      });
    }
  } else if (status === "HLS_STOPPING") {
    console.log("Meeting Hls is stopping");
  } else if (status === "HLS_STOPPED") {
    video.src = "";
    console.log("Meeting Hls is stopped");
  } else {
    //
  }
});
}); 
	</script>
</body>
</html> 