const { ONE_SIGNAL_CONFIG } = require("../config/app.config")
const pushNotificationService = require("../services/push-notification.services.js");


exports.SendNotificationToDevice = (req, res, next) => {
    

    pushNotificationService.SendNotification(message, (error, results) => {
        if(error){
            return next(error);
        }
        return res.status(200).send({
            message: "Success",
            data: results,
        });
    });
};