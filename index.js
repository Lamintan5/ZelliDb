const { ONE_SIGNAL_CONFIG } = require("./config/app.config.js")
const pushNotificationService = require("./services/push-notification.services.js");
const express = require("express");
const http = require("http");
const app = express();
const port = process.env.PORT || 5000;
const server = http.createServer(app);
const io = require("socket.io")(server);
const Mpesa = require("mpesa-api").Mpesa;
const axios = require('axios');


// Middleware
app.use(express.json());
const routes = require("./routes");
app.use("/api", routes);
app.use("/uploads", express.static("uploads"));


const clients = {};

io.on("connection", (socket) => {
    console.log("connected");
    console.log(socket.id, "has joined");

    socket.on("signin", (id) => {
        console.log(`User ${id} has signed in`);
        clients[id] = socket;
        console.log("Connected clients:", clients);
    });

    socket.on("signout", (id) => {
        console.log(`User ${id} has signed out`);
        delete clients[id];
        console.log("Connected clients:", clients);
    });
    
    socket.on("message", (msg)=>{
        console.log(msg);
        let targetId = msg.targetId;
        let messageText = msg.message;
        let username = msg.title;
        let recipientToken = msg.token;
        let profile = msg.profile;
        let image = msg.path;

        if (clients[targetId]) {
            clients[targetId].emit("message", msg);
            console.log(`Sent message to user ${targetId}`);
            
        } else {
            console.log(`User ${targetId} not found`);
        }
        var message = {
            app_id: ONE_SIGNAL_CONFIG.APP_ID,
            contents: {en : messageText,},
            headings: {en: username,},
            included_segments: ["Subscribed Users"],
            buttons: [
                { id: "1", text: "Reply", action: { url: `Zelli://message?userId=${targetId}` } },
                { id: "2", text: "Ignore",},
            ],
            include_player_ids: recipientToken,
            content_available: true,
            small_icon: "ic_app_log",
            groupSummaryIcon: "ic_app_log",
            large_icon: profile,
            big_picture: image,
            data: {
                PushTitle: "STUDIO5IVE",
                group: "123456",
            },
            ios_sound: "default", 
            android_sound: "default",
            priority: 10,
        };
        pushNotificationService.SendNotification(message, (error, results) => {
            if(error){
                console.log(`Error`);
            } else {
                console.log(`Success`)
            }
            
        });
    });



    socket.on("group", (msg) => {
    console.log("Received group message:", msg);

    let targetIds = msg.targetId || [];
    let messageText = msg.message;
    let title = msg.title;
    let recipientToken = msg.token;
    let profile = msg.profile;
    let image = msg.path;
    let username = msg.username;

        targetIds.forEach(targetId => {
            if (clients[targetId]) {
                clients[targetId].emit("group", msg);
                console.log(`Sent group message to user ${targetId}`);
            } else {
                console.log(`User ${targetId} not found`);
            }
        });
        var message = {
            app_id: ONE_SIGNAL_CONFIG.APP_ID,
            contents: {en : `${username} : ${messageText}`,},
            headings: {en: title,},
            included_segments: ["Grouped Users"],
            buttons: [
                { id: "1", text: "Reply", action: { url: `Zelli://message?userId=123` } },
                { id: "2", text: "Ignore",},
            ],
            include_player_ids: recipientToken,
            content_available: true,
            small_icon: "ic_app_log",
            large_icon: profile,
            big_picture: image,
            data: {
                PushTitle: "STUDIO5IVE"
            },
            ios_sound: "default", 
            android_sound: "default",
            priority: 10,
        };
        pushNotificationService.SendNotification(message, (error, results) => {
            if(error){
                return console.log(`Error`)
            } else {
                console.log(`Success`)
            }
            
        });
    });
    

    socket.on("notif", (msg)=>{
        console.log(msg);
        let targetIds = msg.pid || [];
        let messageText = msg.message;
        let title = msg.title;
        let recipientToken = msg.token;
        let profile = msg.profile;
        let image = msg.path;

        targetIds.forEach(pid => {
            if (clients[pid]) {
                clients[pid].emit("notif", msg);
                console.log(`Sent Notification to user ${pid}`);
            } else {
                console.log(`User ${pid} not found`);
            }
        });
        var message = {
            app_id: ONE_SIGNAL_CONFIG.APP_ID,
            contents: {en : messageText,},
            headings: {en: title,},
            included_segments: ["Subscribed Users"],
            
            include_player_ids: recipientToken,
            content_available: true,
            small_icon: "ic_app_log",
            groupSummaryIcon: "ic_app_log",
            large_icon: profile,
            big_picture: image,
           
            data: {
                PushTitle: "STUDIO5IVE",
                group: "123456",
            },
            ios_sound: "default", 
            android_sound: "default",
            priority: 10,
        };
        pushNotificationService.SendNotification(message, (error, results) => {
            if(error){
                console.log(`Error`);
            } else {
                console.log(`Success`)
            }
            
        });
    });

    socket.on("disconnect", (_) => {
        console.log("Disconnected. Reconnecting :", new Date().toLocaleTimeString().substring(0, 5));
        // Implement your reconnection logic here
    });

    socket.on("connect_error", (err) => {
        console.log("Connection error: ", err);
        // Implement detailed logging or error handling here
    });

    console.log(`${socket.connected}: ${new Date().toLocaleTimeString().substring(0, 5)}`);
});

const credentials = {
    clientKey: 'oIdnTxYqW5AfZXTD7BgFnm3OxflAoAIcpKeHvySzdmHnfmbI',
    clientSecret: 'Rl7I9wF8ANexFDLDXw3RpYKaD5K0fPxvsF23gWrMMHCzVLY7XnYc0VDwNoo26Ehp',
    initiatorPassword: 'Safaricom999',
    securityCredential: "aM8d5+FtNsE5SFy0Nk7o9dX9MYkHZGKq1MQ64UZC6wqWcvUcAyPjvst2aYsWZJ5EIvi6gABfeCz5t7W6SZbCABL3jGGyYwnK0BmZI/CSMMu+S5j9KQ63Z07tMnMgFMS5Y3NzlB27IU05vWQD1B5cboC+mY4NkNBkAnBi3FggPj9PW1+BX1UhnelzcAvmvIC4ZclU8ssywMYqDBiOMmGy2X4x0AzdqHqb0x7KJez5hdvob6fNryz9bKrMwf1t2/k+grIMrxMoC5V+oMyYSRBtG4TAxW2gve1TFPW4jQ+JCHVvUu9DfBIfCHusRgXdPC2o34yXAc6hdBUGbHGnQp3TEA==",
    certificatePath: null,
    environment: "sandbox",
};

async function getAccessToken() {
    const url = `https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials`;
    const headers = {
        'Authorization': `Basic ${Buffer.from(`${credentials.clientKey}:${credentials.clientSecret}`).toString('base64')}`,
    };

    try {
        const response = await axios.get(url, { headers });
        return response.data.access_token; // Token to be used in further requests
    } catch (error) {
        console.error('Error getting access token:', error);
        throw new Error('Failed to get access token');
    }
}

const mpesa = new Mpesa(credentials, {
    timeout: 5000,
});

async function lipaNaMpesaOnline(accessToken, amount, phoneNumber, accountNumber, businessNumber) {
    const url = `https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest`;
    const headers = {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json',
    };

    const requestBody = {
        BusinessShortCode: businessNumber,
        Amount: amount,
        PartyA: phoneNumber,
        PartyB: businessNumber,
        PhoneNumber: phoneNumber,
        CallBackURL: `https://more-crow-hardly.ngrok-free.app/api/mpesa-callback`,
        AccountReference: accountNumber,
        TransactionDesc: "Payment",
        TransactionType: "CustomerPayBillOnline",
    };

    try {
        const response = await axios.post(url, requestBody, { headers });
        return response.data; // Handle the response accordingly
    } catch (error) {
        console.error('Error initiating payment:', error);
        throw new Error('Payment initiation failed');
    }
}

// API endpoint to handle the payment initiation
app.post("/api/pay", async (req, res) => {
    const { amount, phoneNumber, accountNumber, businessNumber } = req.body;

    try {
        const accessToken = await getAccessToken(); // Get the access token
        const response = await lipaNaMpesaOnline(accessToken, amount, phoneNumber, accountNumber, businessNumber);
        return res.status(200).json({ message: "Payment initiated", data: response });
    } catch (error) {
        console.error(error);
        return res.status(500).json({ message: "Payment initiation failed", error: error.message });
    }
});


app.post("/api/mpesa-callback", (req, res) => {
    const { Body } = req.body;

    if (Body.stkCallback.ResultCode === 0) {
        const result = Body.stkCallback.CallbackMetadata.Item;
        io.emit("payment-status", { status: "success", result });
    } else {
        io.emit("payment-status", { status: "failed", message: Body.stkCallback.ResultDesc });
    }

    res.status(200).send("Callback received");
});

app.route("/check").get((req, res) => {
    return res.json("Your app is working fine");
});

server.listen(port, "0.0.0.0", () => {
    console.log("server started");
});
