var nodemailer = require('nodemailer');

async function sendEmail(params, callback){
    var transport = nodemailer.createTransport({
        host: 'mail.privateemail.com',
        port: 465, 
        auth: {
            user: 'info@studio5ive.org',
            pass: 'Eatmyass@2000',
        }
    });

    var mailOptions = {
        from: `"Studio5ive" <info@studio5ive.org>`,
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