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
const moment = require("moment");
const fs = require("fs");


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
       
    });

    socket.on("connect_error", (err) => {
        console.log("Connection error: ", err);
    });

    console.log(`${socket.connected}: ${new Date().toLocaleTimeString().substring(0, 5)}`);
});

//ACCESS TOKEN ROUTE
app.get("/api/access_token", (req, res) => {
    getAccessToken()
      .then((accessToken) => {
        res.send(accessToken);
      })
      .catch(console.log);
});

async function getAccessToken() {
    const consumer_key = "oIdnTxYqW5AfZXTD7BgFnm3OxflAoAIcpKeHvySzdmHnfmbI"; 
    const consumer_secret = "Rl7I9wF8ANexFDLDXw3RpYKaD5K0fPxvsF23gWrMMHCzVLY7XnYc0VDwNoo26Ehp";
    const url =
      "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
    const auth =
      "Basic " +
      new Buffer.from(consumer_key + ":" + consumer_secret).toString("base64");
  
    try {
      const response = await axios.get(url, {
        headers: {
          Authorization: auth,
        },
      });
     
      const dataresponse = response.data;
      const accessToken = dataresponse.access_token;
      return accessToken;
    } catch (error) {
      throw error;
    }
}

// REGISTER URL FOR C2B
app.get("/api/registerurl", (req, resp) => {
    getAccessToken()
      .then((accessToken) => {
        const url = "https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl";
        const auth = "Bearer " + accessToken;
        axios
          .post(
            url,
            {
              ShortCode: "174379",
              ResponseType: "Complete",
              ConfirmationURL: "https://more-crow-hardly.ngrok-free.app/api/confirmation",
              ValidationURL: "http://more-crow-hardly.ngrok-free.app/api/validation",
            },
            {
              headers: {
                Authorization: auth,
              },
            }
          )
          .then((response) => {
            resp.status(200).json(response.data);
          })
          .catch((error) => {
            console.log(error);
            resp.status(500).send("❌ Request failed");
          });
      })
      .catch(console.log);
});

//MPESA STK PUSH ROUTE
app.get("/api/stkpush", (req, res) => {
    getAccessToken()
      .then((accessToken) => {
        const url =
          "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
        const auth = "Bearer " + accessToken;
        const timestamp = moment().format("YYYYMMDDHHmmss");
        const password = new Buffer.from(
          "174379" +
            "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919" +
            timestamp
        ).toString("base64");
  
        axios
          .post(
            url,
            {
              BusinessShortCode: "174379",
              Password: password,
              Timestamp: timestamp,
              TransactionType: "CustomerPayBillOnline",
              Amount: "1",
              PartyA: "254113671997", 
              PartyB: "174379",
              PhoneNumber: "254113671997",
              CallBackURL: "https://more-crow-hardly.ngrok-free.app/api/callback",
              AccountReference: "TNT010310",
              TransactionDesc: "Mpesa Daraja API stk push test",
            },
            {
              headers: {
                Authorization: auth,
              },
            }
          )
          .then((response) => {
            res.send("Request is successful done ✔✔. Please enter mpesa pin to complete the transaction");
          })
          .catch((error) => {
            console.log(error);
            res.status(500).send("❌ Request failed");
          });
      })
      .catch(console.log);
});
  
//STK PUSH CALLBACK ROUTE
app.post("/api/callback", (req, res) => {
    console.log("STK PUSH CALLBACK");

    const CheckoutRequestID = req.body.Body.stkCallback.CheckoutRequestID;
    const ResultCode = req.body.Body.stkCallback.ResultCode;
    const json = JSON.stringify(req.body);

    fs.writeFile("stkcallback.json", json, "utf8", function (err) {
        if (err) {
            return console.log("Error writing JSON file:", err);
        }
        console.log("STK PUSH CALLBACK JSON FILE SAVED");
    });

    console.log(req.body);
    res.status(200).json({ message: "Callback received successfully" });
});
  
app.get("/api/confirmation", (req, res) => {
  console.log("All transaction will be sent to this URL");
  console.log(req.body);
});
  
app.get("/api/validation", (req, resp) => {
   console.log("Validating payment");
   console.log(req.body);
});
  
app.route("/check").get((req, res) => {
    return res.json("Your app is working fine");
});

server.listen(port, "0.0.0.0", () => {
    console.log("server started");
});