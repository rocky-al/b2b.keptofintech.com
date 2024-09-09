/* For dagadu messanger 
 Created By Ghanshyam Sharma
 Created Date 2022-12-13
 */

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var request = require('request');
//variable for online users
app.get('/', function (req, res) {
    res.sendFile(__dirname + '/index.html');
});

var base_url = 'http://localhost/urblocklisted/public/'
//process.on('exit');
io.on('connection', function (socket) {
    /*Socket for close chat End */
    /* Send message section start*/
    socket.on('sendmessage', function (data, callback){
        var sender_id = data.sender_id;//Sender  user id
        var receiver_id = data.receiver_id;//Receiver  ID
        var message_type = data.message_type;//type of messaghe
        var chat_type = data.chat_type; //0=> User to user, 1=> user to admin
        var message = data.message;
        var date_time = data.date_time;
        var login_from = data.login_from;
        //receiver preferred language
        console.log('sendmessage one to one');
        console.log(data);
        /* Send one to one message section start*/
        message = escape(message);
        message = message.replace(/\+/g, '9999999');
        message = encodeURIComponent(message);
        var send_data_array = {
            sender_id: sender_id,
            receiver_id: receiver_id,
            message: message,
            message_type: message_type,
            login_from: login_from,
            date_time: date_time,
            media_url: base_url+'storage/chat_media/'
        };
       
        console.log(send_data_array);

        var send_data='login_from=' + login_from + '&sender_id=' + sender_id + "&receiver_id=" + receiver_id  + "&message=" + message + "&message_type=" + message_type +  "&date_time=" + date_time
        /*socket for receive msg*/
        io.emit('getmessage', send_data_array);
        /* start inserting msg into database throgh php*/
        request.post({
            headers: {'content-type': 'application/x-www-form-urlencoded'},
            url: base_url+'api/save_message',
            body: send_data
        }, function (error, response, body) {
            console.log(body);
        });

        /*Message Convertor for receiver section End*/

    });
    /* Send  message section end*/


});

http.listen(3000, function () {
    console.log('listening on *:3000');
});
