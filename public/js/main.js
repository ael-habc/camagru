var video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
//const snap = document.getElementById('snap');


if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia){
    navigator.mediaDevices.getUserMedia({video:true}).then(stream =>{
        video.srcObject = stream;
        video.play()
    });
}
document.getElementById("snap").addEventListener("click", () => {
    context.drawImage(video, 0 , 0, 640, 480);
});