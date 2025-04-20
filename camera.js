async function setupWebRTC() {
    const videoElement = document.getElementById('video');

    // --- Configuration ---
    const mediamtxIp = 'firevision.site';
    const whepUrl = `https://${mediamtxIp}/cctv/whep`;
    // --- End Configuration ---

    console.log("Setting up WebRTC connection to:", whepUrl);

    const peerConnection = new RTCPeerConnection({
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' } // optional STUN server
        ]
    });

    peerConnection.ontrack = (event) => {
        console.log("Track received:", event.track.kind);
        if (event.streams && event.streams[0]) {
            console.log("Attaching stream to video element");
            videoElement.srcObject = event.streams[0];
        }
    };

    peerConnection.oniceconnectionstatechange = () => {
        console.log("ICE connection state:", peerConnection.iceConnectionState);
    };

    peerConnection.onconnectionstatechange = () => {
        console.log("Peer connection state:", peerConnection.connectionState);
    };

    try {
        peerConnection.addTransceiver('video', { direction: 'recvonly' });
        // peerConnection.addTransceiver('audio', { direction: 'recvonly' }); // if audio is available

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        console.log("Local SDP Offer created");

        console.log("Sending Offer to WHEP endpoint...");
        const response = await fetch(whepUrl, {
            method: 'POST',
            headers: {
                'Authorization': 'Basic ' + btoa(username + ':' + password),
                'Content-Type': 'application/sdp'
            },
            body: offer.sdp
        });

        if (!response.ok) {
            throw new Error(`WHEP request failed: ${response.status} ${response.statusText} - ${await response.text()}`);
        }

        const answerSdp = await response.text();
        console.log("Received SDP Answer from WHEP endpoint");

        await peerConnection.setRemoteDescription({
            type: 'answer',
            sdp: answerSdp
        });
        console.log("Remote SDP Answer set successfully.");
    } catch (error) {
        console.error("WebRTC setup failed:", error);
        alert(`WebRTC connection failed: ${error.message}`);
        peerConnection.close();
    }
}

setupWebRTC();

