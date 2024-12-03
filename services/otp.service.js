const otpGenerator = require("otp-generator");
const crypto = require("crypto");
const key = "Lamintan";
const emailServices = require("../services/emailer.service");

async function sendOTP(params, callback){
   
}

async function verifyOTP(params, callback){
    let [hashValue, expires] = params.hash.split('.');

    let now = Date.now();

    if(now > parseInt(expires)) return callback("OTP Expired");

    let data = `${params.email}.${params.otp}.${expires}`;
    let newCalculateHash = crypto.createHmac("sha256", key).update(data).digest("hex");
    if(newCalculateHash === hashValue) {
        return callback(null, "Success");
    }

    return callback("Invalid OTP");
}

module.exports = {
    sendOTP,
    verifyOTP
}