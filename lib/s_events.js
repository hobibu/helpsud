var player = document.getElementById('audio_alert');
player.src = 'sound/alert.mp3';
var pre_id = -1;

function disp_event(id,mes){
	var elmn;
	var elmp;
	if(pre_id < 0) {
		elmn = document.getElementById(id);
		pre_id = id;
		elmn.style.height = "8em";
		elmn.children[1].style.display = "block";
		elmn.children[2].style.display = "block";
		elmn.children[3].style.display = "none";
		elmn.children[4].style.display = "block";
		elmn.children[5].style.display = "block";
	}
	else if(pre_id != id){
		elmp = document.getElementById(pre_id);
		elmn = document.getElementById(id);
		pre_id = id;
		elmp.style.height = "3.3em";
		elmn.style.height = "8em";
		elmp.children[1].style.display = "none";
		elmn.children[1].style.display = "block";
		elmp.children[2].style.display = "none";
		elmn.children[2].style.display = "block";
		elmp.children[3].style.display = "block";
		elmn.children[3].style.display = "none";
		elmp.children[4].style.display = "none";
		elmn.children[4].style.display = "block";
		elmp.children[5].style.display = "none";
		elmn.children[5].style.display = "block";
	}
	else {
		elmn = document.getElementById(id);
		elmn.style.height = "3.3em";
		elmn.children[1].style.display = "none";
		elmn.children[2].style.display = "none";
		elmn.children[3].style.display = "block";
		elmn.children[4].style.display = "none";
		elmn.children[5].style.display = "none";
		pre_id = -1;
	}
}

function startListening(){
	source = new EventSource('que/server_events.php');
	source.onmessage = receiveMessage;
}

function receiveMessage(event){
	//messageLog.innerHTML = event.data;
	player.play();
	player.onended = function(){
		location.reload(true);
	}
}

window.onload = function(){
	startListening();
}

/*function stopListening(){
	source.close();
	messageLog.innerHTML = " Уведомления отключены.";
}*/


