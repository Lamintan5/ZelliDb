const {ONE_SIGNAL_CONFIG} = require("../config/app.config");

async function SendNotification(data, callback){
    
    req.on("error", function(e){
        return callback({
            message: e
        });
    });

    req.write(JSON.stringify(data));
    req.end();
}

module.exports = {
    SendNotification
}