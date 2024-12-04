var nodemailer = require('nodemailer');

async function sendEmail(params, callback){
   
    transport.sendMail(mailOptions, function (error, info){
        if(error){
            return callback(error);
        } else {
            return callback(null, info.response);
        }
    });
}

module.exports = {
    sendEmail
}