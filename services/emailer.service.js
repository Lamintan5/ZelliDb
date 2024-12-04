var nodemailer = require('nodemailer');

async function sendEmail(params, callback){
   

    var mailOptions = {
        from: 'billclinton1758@gmail.com',
        to: params.email,
        subject: params.subject,
        text: params.body,
        html: params.html,
    };

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